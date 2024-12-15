<?php
    include 'includes/session.php';
    include 'includes/conn.php';

    if(isset($_POST['delete'])){
        $id = $_GET['id'];
        $sql = "DELETE FROM program WHERE id = '$id'";
        if($conn->query($sql)){
            $_SESSION['success'] = 'Program deleted successfully.';
        }
        else{
            $_SESSION['error'] = $conn->error;
        }
    }
    else{
        $_SESSION['error'] = 'Select item to delete first';
    }

    header('location: program.php');