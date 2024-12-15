<?php
// Start session and include database connection
session_start();
include 'includes/conn.php';

if (isset($_POST['add'])) {
    // Get form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $role = $_POST['role'];
    $created_on = $_POST['created'];

    // Handle photo upload
    $filename = !empty($_FILES['photo']['name']) ? $_FILES['photo']['name'] : 'default.jpg';
    if (!empty($_FILES['photo']['name'])) {
        $target_directory = __DIR__ . '/../images/'; // Set the target directory using __DIR__
        $target_file = $target_directory . basename($filename);

        // Move uploaded file to target directory
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $_SESSION['error'] = 'Error uploading photo.';
            header('Location: accounts.php');
            exit();
        }
    }

    // Insert new user data into the database
    $sql = "INSERT INTO admin (firstname, lastname, username, password, photo, role, created_on) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssis", $firstname, $lastname, $username, $password, $filename, $role, $created_on);

    // Execute and check the result
    if ($stmt->execute()) {
        $_SESSION['success'] = 'New user added successfully';
    } else {
        $_SESSION['error'] = 'Something went wrong while adding the user';
    }

    // Close the statement
    $stmt->close();
}

// Redirect to accounts page
header('Location: accounts.php');
exit();
?>
