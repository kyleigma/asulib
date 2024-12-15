<?php
include 'includes/session.php';
include 'includes/conn.php';

// Function to calculate the due date excluding weekends
function calculate_due_date($start_date, $days) {
    $due_date = strtotime($start_date);
    $count = 0;

    while ($count < $days) {
        $due_date = strtotime("+1 day", $due_date);
        $day_of_week = date('N', $due_date); // 1 (Monday) to 7 (Sunday)
        if ($day_of_week < 6) { // Exclude weekends (6 = Saturday, 7 = Sunday)
            $count++;
        }
    }

    return date('Y-m-d', $due_date);
}

if (isset($_POST['add'])) {
    $student_id = $_POST['student_id'];
    $accessions = $_POST['accession']; // Expecting an array of accession numbers

    // Validate each accession number and check if the book is available
    foreach ($accessions as $accession) {
        $book_query = mysqli_query($conn, "SELECT * FROM books WHERE accession='$accession' AND status=0");
        if (mysqli_num_rows($book_query) == 0) {
            $_SESSION['error'] = "Book with accession number: $accession is either not found or not available!";
            header('location: borrow_student.php');
            exit();
        }
    }

    // Validate student_id
    $student_query = mysqli_query($conn, "SELECT * FROM students WHERE student_id='$student_id'");
    if (mysqli_num_rows($student_query) == 0) {
        $_SESSION['error'] = "Student not found!";
        header('location: borrow_student.php');
        exit();
    }
    $student = mysqli_fetch_assoc($student_query);
    $student_db_id = $student['id'];

    // Check if the student has any unreturned books
    $outstanding_books_query = mysqli_query($conn, "SELECT * FROM borrow WHERE student_id='$student_db_id' AND status=1");
    if (mysqli_num_rows($outstanding_books_query) > 0) {
        $_SESSION['error'] = "You cannot borrow more books until all previously borrowed books are returned!";
        header('location: borrow_student.php');
        exit();
    }

    // Check if the number of books exceeds the limit
    if (count($accessions) > 2) {
        $_SESSION['error'] = "You can only borrow up to 2 books at a time.";
        header('location: borrow_student.php');
        exit();
    }

    // Track borrowed book IDs
    $borrowed_books = [];

    foreach ($accessions as $accession) {
        $book_query = mysqli_query($conn, "SELECT * FROM books WHERE accession='$accession' AND status=0");
        if (mysqli_num_rows($book_query) == 0) {
            $_SESSION['error'] = "Book with accession number: <strong>$accession</strong> is not available!";
            header('location: borrow_student.php');
            exit();
        }
        $book = mysqli_fetch_assoc($book_query);
        $book_db_id = $book['id'];
        $borrowed_books[] = $book_db_id; // Collect the book IDs for borrowing
    }

    // Insert borrowing transactions
    $date_borrow = date('Y-m-d');
    $status = 1; // Borrowed status
    $max_days_circulating = 3;
    $due_date = calculate_due_date($date_borrow, $max_days_circulating);

    foreach ($borrowed_books as $book_db_id) {
        $insert_query = "INSERT INTO borrow (student_id, book_id, date_borrow, due_date, status) 
                         VALUES ('$student_db_id', '$book_db_id', '$date_borrow', '$due_date', '$status')";
        if (!mysqli_query($conn, $insert_query)) {
            $_SESSION['error'] = "An error occurred during the borrowing transaction!";
            header('location: borrow_student.php');
            exit();
        }

        // Update book status to borrowed (1)
        $update_book_query = "UPDATE books SET status = 1 WHERE id='$book_db_id'";
        mysqli_query($conn, $update_book_query);
    }

    $_SESSION['success'] = "Books borrowed successfully!";
    header('location: borrow_student.php');
} else {
    $_SESSION['error'] = "Fill up the borrow form first!";
    header('location: borrow_student.php');
}
?>
