<?php
  include 'includes/session.php';

  if(isset($_POST['delete'])){
    $id = $_GET['id'];
    
    // Delete faculty record
    $sql = "DELETE FROM faculty WHERE id = '$id'";
    
    if($conn->query($sql)){
      $_SESSION['success'] = 'Faculty deleted successfully.';
    }
    else{
      $_SESSION['error'] = $conn->error;
    }
  }
  else{
    $_SESSION['error'] = 'Select item to delete first';
  }

  header('location: faculty.php');  // Redirect to the faculty listing page
?>
