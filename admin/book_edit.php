<?php
  include 'includes/session.php';
  include 'includes/conn.php';

  if (isset($_POST['edit'])) {
    $id = $_GET['id'];
    $category = $_POST['category'];
    $category_no = $_POST['category_no'];
    $volume = $_POST['volume'];
    $accession = $_POST['accession'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $publish_date = $_POST['publish_date'];

    // Prepare the update statement without quantity
    $stmt = $conn->prepare("UPDATE books SET category_no = ?, category_id = ?, volume = ?, accession = ?, title = ?, author = ?, publisher = ?, publish_date = ? WHERE id = ?");
    $stmt->bind_param("ssssssssi", $category_no, $category, $volume, $accession, $title, $author, $publisher, $publish_date, $id);

    if ($stmt->execute()) {
      $_SESSION['success'] = 'Book updated successfully.';
    } else {
      $_SESSION['error'] = $conn->error;
    }

    // Close the statement
    $stmt->close();
  } else {
    $_SESSION['error'] = 'Fill up edit form first';
  }

  header('location: book.php');
?>
