<?php
include 'includes/session.php';
include 'includes/conn.php';

if (!isset($_GET['accession']) || (!isset($_SESSION['student']) && !isset($_SESSION['faculty']))) {
    $_SESSION['error'] = 'Invalid request or not logged in.';
    header('Location: catalog.php');
    exit();
}

// Get the book ID (accession) and user ID
$accession = $_GET['accession'];
$user_id = null;
$is_student = false;

if (isset($_SESSION['student'])) {
    $user_id = $_SESSION['student'];
    $is_student = true;
} elseif (isset($_SESSION['faculty'])) {
    // For faculty, retrieve the actual faculty ID from the faculty table
    $faculty_id = $_SESSION['faculty'];
    
    // Fetch the actual faculty id from the faculty table using the faculty_id stored in the session
    $check_faculty_sql = "SELECT id FROM faculty WHERE faculty_id = '$faculty_id'";
    $faculty_result = $conn->query($check_faculty_sql);
    
    if ($faculty_result->num_rows > 0) {
        $faculty_data = $faculty_result->fetch_assoc();
        $user_id = $faculty_data['id'];  // Get the actual 'id' of the faculty from the table
    } else {
        $_SESSION['error'] = 'Invalid faculty ID.';
        header('Location: catalog.php');
        exit();
    }
}

// Check if the book is already borrowed or requested
$check_borrow_sql = "
    SELECT * 
    FROM borrow 
    WHERE book_id = (SELECT id FROM books WHERE accession = ?) 
    AND (student_id = ? OR faculty_id = ?) 
    AND NOT EXISTS (
        SELECT 1 
        FROM returns 
        WHERE returns.book_id = borrow.book_id 
        AND (returns.student_id = borrow.student_id OR returns.faculty_id = borrow.faculty_id)
    )";
$stmt = $conn->prepare($check_borrow_sql);
$stmt->bind_param("iii", $accession, $user_id, $user_id);
$stmt->execute();
$check_borrow_query = $stmt->get_result();

if ($check_borrow_query->num_rows > 0) {
    $_SESSION['error'] = 'You have already borrowed this book and not returned it yet.';
    header('Location: catalog.php');
    exit();
}

// Check if the book is already requested
$check_request_sql = "SELECT * FROM requests 
                      WHERE book_id = (SELECT id FROM books WHERE accession = ?) 
                      AND (" . ($is_student ? "student_id" : "faculty_id") . " = ?) 
                      AND status = 'pending'";
$stmt = $conn->prepare($check_request_sql);
$stmt->bind_param("ii", $accession, $user_id);
$stmt->execute();
$check_request_query = $stmt->get_result();

if ($check_request_query->num_rows > 0) {
    $_SESSION['error'] = 'You have already requested this book.';
    header('Location: catalog.php');
    exit();
}

// Insert the borrow request with timestamp
$request_sql = "INSERT INTO requests (book_id, " . ($is_student ? "student_id" : "faculty_id") . ", request_date, status) 
                VALUES ((SELECT id FROM books WHERE accession = ?), ?, NOW(), 'pending')";
$stmt = $conn->prepare($request_sql);
$stmt->bind_param("ii", $accession, $user_id);

if ($stmt->execute()) {
    // Update the book status to 'reserved' (status 2)
    $update_status_sql = "UPDATE books SET status = 2 WHERE accession = ?";
    $update_stmt = $conn->prepare($update_status_sql);
    $update_stmt->bind_param("s", $accession);

    if ($update_stmt->execute()) {
        $_SESSION['success'] = 'Book reserved successfully.';
    } else {
        $_SESSION['error'] = 'Failed to reserve the book: ' . $update_stmt->error;
    }
} else {
    $_SESSION['error'] = 'Failed to submit borrow request: ' . $stmt->error;
}

header('Location: catalog.php');
exit();
?>
