<?php
// Include necessary files for DB connection and session
include 'includes/session.php';
include 'includes/conn.php';

// Initialize an array to hold program codes, positions, and borrower counts
$program_borrowers = [];

// Get the current month's start and end date
$current_year = date('Y');
$current_month = date('m');
$first_day_of_month = "$current_year-$current_month-01"; // First day of the current month
$last_day_of_month = date("Y-m-t"); // Last day of the current month

// SQL query to get the student borrower count grouped by program code for the current month
$sql_students = "SELECT p.code AS program_code, COUNT(b.student_id) AS borrower_count
                 FROM borrow b
                 INNER JOIN students s ON b.student_id = s.id
                 INNER JOIN program p ON s.program_id = p.id
                 WHERE b.status IN (0, 1)  -- Fetch all borrowed records with status 0 or 1
                 AND b.date_borrow BETWEEN '$first_day_of_month' AND '$last_day_of_month'
                 GROUP BY p.code";

// SQL query to get the faculty borrower count grouped by position for the current month
$sql_faculty = "SELECT f.position AS faculty_position, COUNT(b.faculty_id) AS borrower_count
                FROM borrow b
                INNER JOIN faculty f ON b.faculty_id = f.id
                WHERE b.status IN (0, 1)  -- Fetch all borrowed records with status 0 or 1
                AND b.date_borrow BETWEEN '$first_day_of_month' AND '$last_day_of_month'
                GROUP BY f.position";

// Execute the student query
$result_students = $conn->query($sql_students);

// Check if the student query returned results
if ($result_students->num_rows > 0) {
    // Fetch data and store it in the array
    while ($row = $result_students->fetch_assoc()) {
        $program_borrowers[] = [
            'type' => 'student',
            'program_code' => $row['program_code'],
            'borrower_count' => $row['borrower_count']
        ];
    }
}

// Execute the faculty query
$result_faculty = $conn->query($sql_faculty);

// Define position labels for faculty
$position_labels = [
    0 => 'Faculty',
    1 => 'COS Faculty',
    2 => 'External Faculty',
    3 => 'Non-teaching Staff'
];

// Check if the faculty query returned results
if ($result_faculty->num_rows > 0) {
    // Fetch data and store it in the array
    while ($row = $result_faculty->fetch_assoc()) {
        $program_borrowers[] = [
            'type' => 'faculty',
            'faculty_position' => $position_labels[$row['faculty_position']],
            'borrower_count' => $row['borrower_count']
        ];
    }
}

// In case no data is found for both students and faculty, add a message
if (empty($program_borrowers)) {
    $program_borrowers[] = ['message' => 'No borrowers found for the current month'];
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($program_borrowers);
?>
