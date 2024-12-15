<?php
include 'includes/session.php';
include 'includes/conn.php'; // Include the database connection

if (isset($_POST['login'])) {
    $id_number = $_POST['id_number']; // The ID number for both students and faculty

    // Sanitize the input to prevent SQL injection
    $id_number = $conn->real_escape_string($id_number);

    // Check in students table first
    $student_sql = "SELECT * FROM students WHERE student_id = '$id_number'";
    $student_query = $conn->query($student_sql);

    if ($student_query && $student_query->num_rows > 0) {
        $student_row = $student_query->fetch_assoc();
        $_SESSION['student'] = $student_row['id']; // Set session with student 'id'
        header('location: catalog.php');
        exit();
    }

    // If not found in students, check in faculty table
    $faculty_sql = "SELECT * FROM faculty WHERE faculty_id = '$id_number'";
    $faculty_query = $conn->query($faculty_sql);

    if ($faculty_query && $faculty_query->num_rows > 0) {
        $faculty_row = $faculty_query->fetch_assoc();
        $_SESSION['faculty'] = $faculty_row['faculty_id']; // Set session with faculty_id
        header('location: catalog.php');
        exit();
    }

    // Debugging: If no student or faculty match, output error message
    $_SESSION['error'] = 'User not found';
    header('location: loginpage.php');
    exit();

} else {
    $_SESSION['error'] = 'Enter ID number first';
    header('location: loginpage.php');
    exit();
}
