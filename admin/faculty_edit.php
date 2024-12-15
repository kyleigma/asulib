<?php
include 'includes/session.php';

if (isset($_POST['edit'])) {
    $id = $_GET['id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $position = $_POST['position'];

    // Update faculty information in the database
    $sql = "UPDATE faculty SET firstname = '$firstname', middlename = '$middlename', lastname = '$lastname', position = '$position' WHERE id = '$id'";
    
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Faculty updated successfully.';
    } else {
        $_SESSION['error'] = $conn->error;
    }
} else {
    $_SESSION['error'] = 'Fill up edit form first.';
}

header('location: faculty.php');
?>
