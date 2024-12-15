<?php
include 'includes/session.php'; // Include session management and database connection

if (isset($_POST['edit'])) {
    $id = $_GET['id'];
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    $password = $_POST['password'];
    $filename = $_FILES['photo']['name'] ?? ''; // Check if a file is uploaded

    // Initialize SQL query
    $sql = "UPDATE admin SET username = ?, firstname = ?, lastname = ?, role = ?, status = ?";
    $params = [$username, $firstname, $lastname, $role, $status];

    // Add password to SQL if provided
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", password = ?";
        $params[] = $hashedPassword;
    }

    // Handle photo upload if provided
    if (!empty($filename)) {
        // Set the correct folder path using __DIR__
        $targetDirectory = __DIR__ . '/../images/';
        $targetFile = $targetDirectory . basename($filename);

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
            $sql .= ", photo = ?";
            $params[] = $filename;
        } else {
            $_SESSION['error'] = 'Failed to upload photo.';
            header('Location: accounts.php');
            exit();
        }
    }

    // Complete the SQL query
    $sql .= " WHERE id = ?";
    $params[] = $id;

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        $_SESSION['error'] = 'Failed to prepare SQL statement.';
        header('Location: accounts.php');
        exit();
    }

    // Dynamically bind the parameters
    $types = str_repeat('s', count($params) - 1) . 'i'; // Build types: 's' for strings, 'i' for id
    $stmt->bind_param($types, ...$params);

    // Execute the query and check for success
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Admin updated successfully.';
    } else {
        $_SESSION['error'] = 'Failed to update admin: ' . $stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['error'] = 'Fill up the edit form first.';
}

header('Location: accounts.php');
exit;
?>
