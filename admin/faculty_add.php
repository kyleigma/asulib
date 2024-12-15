<?php
include 'includes/session.php';
include 'includes/conn.php';

function generateFacultyID($conn) {
    // Retrieve the last faculty_id
    $query = "SELECT faculty_id FROM faculty ORDER BY faculty_id DESC LIMIT 1";
    $result = $conn->query($query);
    $lastID = ($result->num_rows > 0) ? $result->fetch_assoc()['faculty_id'] : null;

    if ($lastID) {
        // Remove the 'F' prefix and increment the numeric part
        $numericPart = (int)substr($lastID, 1);
        $newID = 'F' . str_pad($numericPart + 1, 4, '0', STR_PAD_LEFT);
    } else {
        // Start from F0001 if no ID exists
        $newID = 'F0001';
    }

    return $newID;
}

if (isset($_POST['add'])) {
    // Generate a new faculty_id
    $faculty_id = generateFacultyID($conn);
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $position = $_POST['position'];
    $created_on = $_POST['created'];

    // Set program_id if position is Faculty or COS Faculty, else set it to NULL
    $program_id = ($position == 0 || $position == 1) ? $_POST['program'] : 'NULL';


    // Prepare the SQL statement
    $sql = "INSERT INTO faculty (faculty_id, firstname, middlename, lastname, position, created_on, program_id) 
            VALUES ('$faculty_id', '$firstname', '$middlename', '$lastname', '$position', '$created_on', $program_id)";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = 'Faculty added successfully.';
    } else {
        error_log("MySQL error: " . mysqli_error($conn));
        $_SESSION['error'] = 'Failed to add faculty. Please try again.';
    }
} else {
    $_SESSION['error'] = 'Fill up add form first.';
}

header('location: faculty.php');
?>
