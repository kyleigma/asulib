<?php
include 'includes/session.php';
include 'includes/conn.php';

if (isset($_POST['fine_id'])) {
    $fine_id = $_POST['fine_id'];
    $borrower_id = $_POST['student_id'] ?? $_POST['faculty_id'];
    $fine_amount = $_POST['fine_amount'];

    // Get the associated borrow_id from the fines table
    $fine_query = mysqli_query($conn, "SELECT borrow_id FROM fines WHERE id = '$fine_id'");

    if (mysqli_num_rows($fine_query) > 0) {
        $fine_data = mysqli_fetch_assoc($fine_query);
        $borrow_id = $fine_data['borrow_id'];

        // Check the status of the transaction in the borrow table using borrow_id
        $borrow_query = mysqli_query($conn, "SELECT status FROM borrow WHERE id = '$borrow_id'");

        if (mysqli_num_rows($borrow_query) > 0) {
            $borrow_data = mysqli_fetch_assoc($borrow_query);
            $borrow_status = $borrow_data['status']; // 1 = borrowed, 0 = returned

            // Check borrow status to determine if the fine can be marked as paid
            if ($borrow_status == 1) {
                // Book is still borrowed
                $_SESSION['error'] = "The book has not been returned yet. Fine cannot be marked as paid.";
            } elseif ($borrow_status == 0) {
                // Book has been returned, update the fine record to mark it as paid
                $update_fine_sql = "UPDATE fines SET status = 'paid', date_paid = NOW() WHERE id = '$fine_id'";

                if (mysqli_query($conn, $update_fine_sql)) {
                    $_SESSION['success'] = "Fine has been marked as paid.";
                } else {
                    $_SESSION['error'] = "Error updating fine: " . mysqli_error($conn);
                }
            } else {
                $_SESSION['error'] = "Invalid borrow status.";
            }
        } else {
            $_SESSION['error'] = "No borrowing record found for this transaction.";
        }
    } else {
        $_SESSION['error'] = "Fine record not found.";
    }

    header('location: fines.php');
    exit(); // It's a good practice to exit after a header redirect
}
?>
