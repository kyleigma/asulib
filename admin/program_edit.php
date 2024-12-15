<?php
  include 'includes/session.php';

  if(isset($_POST['edit'])){
    $id = $_GET['id'];
    $code = $_POST['code'];
    $title = $_POST['title'];

    $sql = "UPDATE program SET code = '$code', title = '$title' WHERE id = '$id'";
    if($conn->query($sql)){
      $_SESSION['success'] = 'Program updated successfully.';
    }
    else{
      $_SESSION['error'] = $conn->error;
    }
  }
  else{
    $_SESSION['error'] = 'Fill up edit form first';
  }

  header('location:program.php');
?>