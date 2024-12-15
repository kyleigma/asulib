<?php
include 'includes/session.php';
include 'includes/conn.php';

if (isset($_POST['add'])) {
    $faculty_id = $_POST['faculty_id'];
    $accession = $_POST['accession'];

    // Find faculty by faculty_id
    $faculty_query = mysqli_query($conn, "SELECT * FROM faculty WHERE faculty_id='$faculty_id'");
    if (mysqli_num_rows($faculty_query) == 0) {
        $_SESSION['error'] = "Faculty not found!";
        header('location: return_faculty.php');
        exit();
    }
    $faculty = mysqli_fetch_assoc($faculty_query);
    $faculty_db_id = $faculty['id'];

    // Find book by accession number
    $book_query = mysqli_query($conn, "SELECT * FROM books WHERE accession='$accession'");
    if (mysqli_num_rows($book_query) == 0) {
        $_SESSION['error'] = "Book not found!";
        header('location: return_faculty.php');
        exit();
    }
    $book = mysqli_fetch_assoc($book_query);
    $book_db_id = $book['id'];

    // Check if the book is borrowed by the faculty and is still borrowed (status=1)
    $borrow_query = mysqli_query($conn, "SELECT * FROM borrow WHERE faculty_id='$faculty_db_id' AND book_id='$book_db_id' AND status=1");
    if (mysqli_num_rows($borrow_query) == 0) {
        $_SESSION['error'] = "This faculty did not borrow this book or it has already been returned!";
        header('location: return_faculty.php');
        exit();
    }

    // Insert return transaction
    $date_return = date('Y-m-d'); // Storing the current date (Y-m-d format)
    $insert_query = "INSERT INTO returns (faculty_id, book_id, date_return) VALUES ('$faculty_db_id', '$book_db_id', '$date_return')";

    if (mysqli_query($conn, $insert_query)) {
        // Update the borrow record to mark it as returned
        $update_borrow_query = "UPDATE borrow SET status=0 WHERE faculty_id='$faculty_db_id' AND book_id='$book_db_id'";
        mysqli_query($conn, $update_borrow_query);

        // Update the book status to available (0)
        $update_book_query = "UPDATE books SET status=0 WHERE id='$book_db_id'";
        mysqli_query($conn, $update_book_query);

        $_SESSION['success'] = "Book returned successfully!";
    } else {
        $_SESSION['error'] = "An error occurred during the return transaction!";
    }

    header('location: return_faculty.php');
} else {
    $_SESSION['error'] = "Fill up the return form first!";
    header('location: return_faculty.php');
}
?>
