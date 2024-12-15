<?php
include 'includes/session.php';
include 'includes/conn.php';

if (isset($_POST['add'])) {
    $student_id = $_POST['studentid'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $program = $_POST['program'];
    $created_on = $_POST['created'];

    // Default level
    $level = null;

    // Define the MAED program ID (replace 'YOUR_MAED_PROGRAM_ID' with the actual ID)
    $maed_program_id = '9';

    // Check if the selected program is MAED
    if ($program === $maed_program_id) {
        $level = 1;  // Set level to 1 for MAED program
    }

    // Check if student ID already exists
    $check_sql = mysqli_query($conn, "SELECT * FROM students WHERE student_id = '$student_id'");

    if (mysqli_num_rows($check_sql) > 0) {
        $_SESSION['exist'] = 'Student ID already exists.';
    } else {
        // Insert new student data
        $sql = mysqli_query($conn, "INSERT INTO students (student_id, firstname, middlename, lastname, program_id, level, created_on) 
            VALUES ('$student_id', '$firstname', '$middlename', '$lastname', '$program', '$level', '$created_on')");

        if ($sql) {
            $_SESSION['success'] = 'Student added successfully.';
        } else {
            $_SESSION['error'] = $conn->error;
        }
    }
} else {
    $_SESSION['error'] = 'Fill up add form first.';
}

header('location: student.php');

