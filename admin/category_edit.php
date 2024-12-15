<?php
  include 'includes/session.php';

  if(isset($_POST['edit'])){
    $id = $_GET['id'];
    $name = $_POST['name'];

    $stmt = $conn->prepare("UPDATE category SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $id);
    $stmt->execute();

    if($stmt->affected_rows > 0){
      $_SESSION['success'] = 'Category updated successfully.';
    }
    else{
      $_SESSION['error'] = $conn->error;
    }
  }
  else{
    $_SESSION['error'] = 'Fill up edit form first';
  }

  header('location:category.php');
?>