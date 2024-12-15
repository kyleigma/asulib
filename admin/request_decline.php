<?php
include 'includes/session.php';
include 'includes/conn.php';

if (isset($_POST['decline'])) {
    $request_id = $_POST['request_id'];

    // Fetch the request details
    $qry = mysqli_query($conn, "SELECT * FROM requests WHERE id='$request_id'");
    $row = mysqli_fetch_assoc($qry);

    if (!$row) {
        $_SESSION['error'] = "Request not found!";
        header('location: adminreq.php');
        exit();
    }

    // Get the book ID from the declined request
    $book_id = $row['book_id'];

    // Update the request status to 'declined' and set the decision_date
    $update_query = "UPDATE requests SET status='declined', decision_date=NOW() WHERE id='$request_id'";
    if (mysqli_query($conn, $update_query)) {
        // Change the book status to 'available' (0) since the request was declined
        mysqli_query($conn, "UPDATE books SET status=0 WHERE id='$book_id'");

        $_SESSION['success'] = "Request declined and book status updated to available!";
    } else {
        $_SESSION['error'] = "An error occurred while updating the request status.";
    }

    header('location: adminreq.php');
} else {
    $_SESSION['error'] = "No action was taken!";
    header('location: adminreq.php');
}
?>
