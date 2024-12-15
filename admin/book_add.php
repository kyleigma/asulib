<?php
    include 'includes/session.php';
    include 'includes/conn.php';

    if (isset($_POST['add'])) {
        $category = $_POST['category'];
        $category_no = $_POST['category_no'];
        $volume = $_POST['volume'];
        $accession = $_POST['accession'];
        $title = $_POST['title'];
        $author = $_POST['author'];
        $publisher = $_POST['publisher'];
        $publish_date = $_POST['publish_date'];

        // Check if book with the same accession already exists
        $check_sql = $conn->prepare("SELECT * FROM books WHERE accession = ?");
        $check_sql->bind_param("s", $accession);
        $check_sql->execute();
        $check_result = $check_sql->get_result();

        if ($check_result->num_rows > 0) {
            $_SESSION['exist'] = 'Book with this Accession Number already exists.';
        } else {
            // Prepare the insert statement with status set to 0 (available)
            $stmt = $conn->prepare("INSERT INTO books (category_no, category_id, volume, accession, title, author, publisher, publish_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)");
            $stmt->bind_param("ssssssss", $category_no, $category, $volume, $accession, $title, $author, $publisher, $publish_date);

            if ($stmt->execute()) {
                $_SESSION['success'] = 'Book added successfully.';
            } else {
                $_SESSION['error'] = $conn->error;
            }

            // Close the statement
            $stmt->close();
        }

        // Close the check statement
        $check_sql->close();
    } else {
        $_SESSION['error'] = 'Fill up add form first';
    }

    header('location: book.php');
?>
