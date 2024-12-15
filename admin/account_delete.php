<?php
include 'includes/session.php'; // Include session management and database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the SQL delete statement
    $sql = "DELETE FROM admin WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $_SESSION['error'] = 'Failed to prepare SQL statement.';
        header('Location: accounts.php');
        exit;
    }

    $stmt->bind_param('i', $id);

    // Execute the query
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Account successfully deleted.';
    } else {
        $_SESSION['error'] = 'Failed to delete account: ' . $stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['error'] = 'No ID specified.';
}

// Redirect back to the admin panel
header('Location: accounts.php');
exit;
?>
