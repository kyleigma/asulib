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

if (isset($_POST['approve'])) {
    $request_id = $_POST['request_id'];

    // Fetch the request details
    $qry = mysqli_query($conn, "SELECT * FROM requests WHERE id='$request_id'");
    $row = mysqli_fetch_assoc($qry);

    if (!$row) {
        $_SESSION['error'] = "Request not found!";
        header('location: adminreq.php');
        exit();
    }

    // Check if the request is from a student or faculty
    if ($row['faculty_id']) {
        // Faculty Request
        $faculty_id = $row['faculty_id'];
        $book_id = $row['book_id'];

        // Fetch faculty details
        $faculty_query = mysqli_query($conn, "SELECT * FROM faculty WHERE faculty_id='$faculty_id'");
        $faculty = mysqli_fetch_assoc($faculty_query);
        $position = $faculty['position'];

        // Set circulation period based on faculty position
        switch ($position) {
            case 0: $max_days_circulating = 60; break; // Teaching Faculty
            case 1: $max_days_circulating = 30; break; // COS Faculty
            case 2: $max_days_circulating = 7; break;  // External Faculty
            case 3: $max_days_circulating = 3; break;  // Non-Teaching Staff
            default: $max_days_circulating = 3; break;
        }

        // Insert borrowing transaction
        $date_borrow = date('Y-m-d');
        $status = 1; // Borrowed status
        $due_date = calculate_due_date($date_borrow, $max_days_circulating);

        // Insert into borrow table
        $insert_query = "INSERT INTO borrow (faculty_id, book_id, date_borrow, due_date, status) 
                         VALUES ('$faculty_id', '$book_id', '$date_borrow', '$due_date', '$status')";
        if (mysqli_query($conn, $insert_query)) {
            // Update the book status to borrowed (1)
            mysqli_query($conn, "UPDATE books SET status=1 WHERE id='$book_id'");

            $_SESSION['success'] = "Borrow request approved successfully!";
        } else {
            $_SESSION['error'] = "An error occurred while processing the borrow transaction.";
        }
    } elseif ($row['student_id']) {
        // Student Request
        $student_id = $row['student_id'];
        $book_id = $row['book_id'];

        // Fetch student details
        $student_query = mysqli_query($conn, "SELECT * FROM students WHERE student_id='$student_id'");
        $student = mysqli_fetch_assoc($student_query);

        // Insert borrowing transaction for student
        $date_borrow = date('Y-m-d');
        $status = 1; // Borrowed status
        $max_days_circulating = 3; // Default for students
        $due_date = calculate_due_date($date_borrow, $max_days_circulating);

        // Insert into borrow table
        $insert_query = "INSERT INTO borrow (student_id, book_id, date_borrow, due_date, status) 
                         VALUES ('$student_id', '$book_id', '$date_borrow', '$due_date', '$status')";
        if (mysqli_query($conn, $insert_query)) {
            // Update the book status to borrowed (1)
            mysqli_query($conn, "UPDATE books SET status=1 WHERE id='$book_id'");

            $_SESSION['success'] = "Borrow request approved successfully!";
        } else {
            $_SESSION['error'] = "An error occurred while processing the borrow transaction.";
        }
    } else {
        $_SESSION['error'] = "Invalid request!";
        header('location: adminreq.php');
        exit();
    }

    // Update the request status to 'approved' and set the decision_date
    $update_query = "UPDATE requests SET status='approved', decision_date=NOW() WHERE id='$request_id'";
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['success'] = "Request approved and status updated!";
    } else {
        $_SESSION['error'] = "An error occurred while updating the request status.";
    }

    header('location: adminreq.php');
} else {
    $_SESSION['error'] = "No action was taken!";
    header('location: adminreq.php');
}
?>
