<?php
include 'includes/session.php';
include 'includes/conn.php';

if (isset($_GET['fine_id'])) {
    $fine_id = $_GET['fine_id'];

    // Fetch fine details from the database
    $sql = "SELECT fd.id AS fine_id, 
                f.firstname AS faculty_firstname, f.middlename AS faculty_middlename, f.lastname AS faculty_lastname, 
                s.firstname AS student_firstname, s.middlename AS student_middlename, s.lastname AS student_lastname, 
                f.faculty_id, s.student_id, 
                f.position, b.accession, b.title, fd.date_borrow, fd.due_date, fd.overdue_days, fd.status, 
                fd.date_paid, p.code AS program_code
            FROM fines fd
            LEFT JOIN faculty f ON fd.faculty_id = f.id
            LEFT JOIN students s ON fd.student_id = s.id
            LEFT JOIN books b ON fd.book_id = b.id
            LEFT JOIN program p ON s.program_id = p.id
            WHERE fd.id = '$fine_id'";

    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_array($result)) {
        // Prepare an array for debugging
        $data_array = [
            'fine_id' => $row['fine_id'],
            'faculty' => [
                'firstname' => $row['faculty_firstname'],
                'middlename' => $row['faculty_middlename'],
                'lastname' => $row['faculty_lastname'],
                'faculty_id' => $row['faculty_id'],
                'position' => $row['position'], // Include position
            ],
            'student' => [
                'firstname' => $row['student_firstname'],
                'middlename' => $row['student_middlename'],
                'lastname' => $row['student_lastname'],
                'student_id' => $row['student_id'],
            ],
            'book' => [
                'accession' => $row['accession'],
                'title' => $row['title'],
            ],
            'dates' => [
                'date_borrow' => $row['date_borrow'],
                'due_date' => $row['due_date'],
                'date_paid' => $row['date_paid'],
            ],
            'overdue_days' => $row['overdue_days'],
            'status' => $row['status'],
            'program_code' => $row['program_code'],
        ];

        // Calculate the fine amount
        $overdue_days = $row['overdue_days'];
        
        // Fine rate logic
        if ($row['faculty_id']) { // If it's a faculty member
            switch ($row['position']) {
                case 0: // Teaching Faculty
                case 1: // COS Faculty
                case 2: // External Faculty
                case 3: // Non-Teaching Staff
                    $fine_rate = 10; // Php 10/day
                    break;
                default:
                    $fine_rate = 10; // Default fine rate
                    break;
            }
        } else { // If it's a student
            $fine_rate = 5; // Php 5/day for students
        }

        // Calculate fine amount
        $fine_amount = $overdue_days * $fine_rate;

        // Add fine amount to the data array
        $data_array['fine_amount'] = number_format($fine_amount, 2); // Format fine amount to 2 decimal places

        // Debug: Print the array
        echo '<pre>';
        print_r($data_array); // Display the data array
        echo '</pre>';

        // Now prepare the data for JSON response
        // Here you can add your existing logic to prepare the JSON response

    } else {
        echo json_encode(['error' => 'Fine not found.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request.']);
}

