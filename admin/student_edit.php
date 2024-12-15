<?php
  include 'includes/session.php';

  if(isset($_POST['edit'])){
    $id = $_GET['id'];
    $studentid = $_POST['studentid'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $program = $_POST['program'];

    $sql = "UPDATE students SET student_id = '$studentid', firstname = '$firstname', middlename = '$middlename', lastname = '$lastname', program_id = '$program' WHERE id = '$id'";
    if($conn->query($sql)){
      $_SESSION['success'] = 'Student updated successfully.';
    }
    else{
      $_SESSION['error'] = $conn->error;
    }
  }
  else{
    $_SESSION['error'] = 'Fill up edit form first';
  }

  header('location:student.php');
?>