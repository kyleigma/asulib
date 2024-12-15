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
    $faculty_id = $_POST['faculty_id'];
    $accessions = $_POST['accession']; // Expecting an array of accession numbers

    // Validate each accession number
    foreach ($accessions as $accession) {
        // Validate if the book with the accession exists and is available
        $book_query = mysqli_query($conn, "SELECT * FROM books WHERE accession='$accession' AND status=0");
        if (mysqli_num_rows($book_query) == 0) {
            $_SESSION['error'] = "Book with accession number: $accession is either not found or not available!";
            header('location: borrow_faculty.php');
            exit();
        }
    }

    // Validate faculty_id
    $faculty_query = mysqli_query($conn, "SELECT * FROM faculty WHERE faculty_id='$faculty_id'");
    if (mysqli_num_rows($faculty_query) == 0) {
        $_SESSION['error'] = "Faculty not found!";
        header('location: borrow_faculty.php');
        exit();
    }
    $faculty = mysqli_fetch_assoc($faculty_query);
    $faculty_db_id = $faculty['id'];
    $position = $faculty['position'];

    // Set book limits based on faculty position
    $max_books = 5; // Default for regular faculty
    if ($position == 1 || $position == 2) {
        $max_books = 2; // COS and external campus faculty
    } elseif ($position == 3) {
        $max_books = 1; // Non-teaching staff
    }

    // Check if the number of books exceeds the limit
    if (count($accessions) > $max_books) {
        $_SESSION['error'] = "You can only borrow up to $max_books books!";
        header('location: borrow_faculty.php');
        exit();
    }

    // Check if the faculty has any unreturned books
    $outstanding_books_query = mysqli_query($conn, "SELECT * FROM borrow WHERE faculty_id='$faculty_db_id' AND status=1");
    if (mysqli_num_rows($outstanding_books_query) > 0) {
        $_SESSION['error'] = "You cannot borrow more books until all previously borrowed books are returned!";
        header('location: borrow_faculty.php');
        exit();
    }

    // Track borrowed book IDs
    $borrowed_books = [];

    foreach ($accessions as $accession) {
        // Find book by accession number and ensure it is available
        $book_query = mysqli_query($conn, "SELECT * FROM books WHERE accession='$accession' AND status=0");
        if (mysqli_num_rows($book_query) == 0) {
            $_SESSION['error'] = "Book with accession number: <strong>$accession</strong> is not available!";
            header('location: borrow_faculty.php');
            exit();
        }
        $book = mysqli_fetch_assoc($book_query);
        $book_db_id = $book['id'];
        $borrowed_books[] = $book_db_id; // Collect the book IDs for borrowing
    }

    // Insert borrowing transactions
    $date_borrow = date('Y-m-d'); // Storing the current date (Y-m-d format)
    $status = 1; // Borrowed status
    $due_date = null; // Initialize due date

    foreach ($borrowed_books as $book_db_id) {
        // Calculate the due date based on faculty position
        switch ($position) {
            case 0: // Teaching Faculty
                $max_days_circulating = 60; // 60 days for teaching faculty
                break;
            case 1: // COS Faculty
                $max_days_circulating = 30; // 30 days for COS faculty
                break;
            case 2: // External Faculty
                $max_days_circulating = 7; // 7 days for external faculty
                break;
            case 3: // Non-Teaching Staff
                $max_days_circulating = 3; // 3 days for non-teaching staff
                break;
            default:
                $max_days_circulating = 3; // Default for unknown faculty position
                break;
        }

        // Calculate the due date excluding weekends
        $due_date = calculate_due_date($date_borrow, $max_days_circulating);

        // Insert the borrow record
        $insert_query = "INSERT INTO borrow (faculty_id, book_id, date_borrow, status, due_date) 
                         VALUES ('$faculty_db_id', '$book_db_id', '$date_borrow', '$status', '$due_date')";
        if (!mysqli_query($conn, $insert_query)) {
            $_SESSION['error'] = "An error occurred during the borrowing transaction!";
            header('location: borrow_faculty.php');
            exit();
        }

        // Update book status to borrowed (1)
        $update_book_query = "UPDATE books SET status = 1 WHERE id='$book_db_id'";
        mysqli_query($conn, $update_book_query);
    }

    $_SESSION['success'] = "Books borrowed successfully!";
    header('location: borrow_faculty.php');
} else {
    $_SESSION['error'] = "Fill up the borrow form first!";
    header('location: borrow_faculty.php');
}
?>
