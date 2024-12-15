<?php
include 'includes/conn.php';
session_start();

// Initialize user variables
$user = null;

// Check for student login
if (isset($_SESSION['student'])) {
    $sql = "SELECT * FROM students WHERE id = '" . $_SESSION['student'] . "'";
    $query = $conn->query(query: $sql);
    if ($query && $query->num_rows > 0) {
        $user = $query->fetch_assoc();
        if (!isset($_SESSION['logged_in_student'])) {
            $_SESSION['logged_in_student'] = true;
            $_SESSION['welcome_message'] = "Welcome to ASU Kalibo Library, " . $user['firstname'] . ' ' . $user['lastname'] . "!";
        }
    }
}

// Check for faculty login
elseif (isset($_SESSION['faculty'])) {
    $sql = "SELECT * FROM faculty WHERE id = '" . $_SESSION['faculty'] . "'";
    $query = $conn->query($sql);
    if ($query && $query->num_rows > 0) {
        $user = $query->fetch_assoc();
        if (!isset($_SESSION['logged_in_faculty'])) {
            $_SESSION['logged_in_faculty'] = true;
            $_SESSION['welcome_message'] = "Welcome to ASU Kalibo Library, " . $user['firstname'] . ' ' . $user['lastname'] . "!";
        }
    }
}

// Check for admin login
elseif (isset($_SESSION['admin'])) {
    $sql = "SELECT * FROM admin WHERE id = '" . $_SESSION['admin'] . "'";
    $query = $conn->query($sql);
    if ($query && $query->num_rows > 0) {
        $user = $query->fetch_assoc();
        if (!isset($_SESSION['logged_in_admin'])) {
            $_SESSION['logged_in_admin'] = true;
            $_SESSION['welcome_message'] = "Welcome, " . $user['firstname'] . ' ' . $user['lastname'] . "!";
        }
    }
}

?>
