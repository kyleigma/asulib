<?php
include 'includes/session.php';
include 'includes/conn.php';

if (isset($_POST['add'])) {
    $student_id = $_POST['student_id'];
    $accession = $_POST['accession'];

    // Find student by student_id
    $student_query = mysqli_query($conn, "SELECT * FROM students WHERE student_id='$student_id'");
    if (mysqli_num_rows($student_query) == 0) {
        $_SESSION['error'] = "Student not found!";
        header('location: return_student.php');
        exit();
    }
    $student = mysqli_fetch_assoc($student_query);
    $student_db_id = $student['id'];

    // Find book by accession number
    $book_query = mysqli_query($conn, "SELECT * FROM books WHERE accession='$accession'");
    if (mysqli_num_rows($book_query) == 0) {
        $_SESSION['error'] = "Book not found!";
        header('location: return_student.php');
        exit();
    }
    $book = mysqli_fetch_assoc($book_query);
    $book_db_id = $book['id'];

    // Check if the book is borrowed by the student
    $borrow_query = mysqli_query($conn, "SELECT * FROM borrow WHERE student_id='$student_db_id' AND book_id='$book_db_id' AND status=1");
    if (mysqli_num_rows($borrow_query) == 0) {
        $_SESSION['error'] = "This student did not borrow this book or it has already been returned!";
        header('location: return_student.php');
        exit();
    }

    // Insert return transaction
    $date_return = date('Y-m-d'); // Storing the current date (Y-m-d format)
    $insert_query = "INSERT INTO returns (student_id, book_id, date_return) VALUES ('$student_db_id', '$book_db_id', '$date_return')";

    if (mysqli_query($conn, $insert_query)) {
        // Update the borrow record to mark it as returned
        $update_borrow_query = "UPDATE borrow SET status=0 WHERE student_id='$student_db_id' AND book_id='$book_db_id'";
        mysqli_query($conn, $update_borrow_query);

        // Update the book status to available (status=0)
        $update_book_query = "UPDATE books SET status=0 WHERE id='$book_db_id'";
        mysqli_query($conn, $update_book_query);

        $_SESSION['success'] = "Book returned successfully!";
    } else {
        $_SESSION['error'] = "An error occurred during the return transaction!";
    }

    header('location: return_student.php');
} else {
    $_SESSION['error'] = "Fill up the return form first!";
    header('location: return_student.php');
}
?>
