<?php
include 'includes/session.php';
include 'includes/conn.php';

if (isset($_GET['fine_id'])) {
    $fine_id = $_GET['fine_id'];

    // Prepare the SQL query to fetch fine details
    $sql = "SELECT fd.id AS fine_id, 
                f.firstname AS faculty_firstname, f.middlename AS faculty_middlename, f.lastname AS faculty_lastname, 
                s.firstname AS student_firstname, s.middlename AS student_middlename, s.lastname AS student_lastname, 
                f.faculty_id, s.student_id, 
                f.position, b.accession, b.title, fd.date_borrow, fd.due_date, fd.fine_amount, fd.overdue_days, fd.status, 
                fd.date_paid, p.code AS program_code
            FROM fines fd
            LEFT JOIN faculty f ON fd.faculty_id = f.id
            LEFT JOIN students s ON fd.student_id = s.id
            LEFT JOIN books b ON fd.book_id = b.id
            LEFT JOIN program p ON s.program_id = p.id
            WHERE fd.id = ?";  // Use prepared statement to bind fine_id

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $fine_id);  // Bind the fine_id to the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Define the position mappings
        $position_mapping = [
            0 => 'Faculty',
            1 => 'COS Faculty',
            2 => 'External Faculty',
            3 => 'Non-Teaching Staff'
        ];

        // Prepare data for JSON response
        $borrower_type = $row['faculty_id'] ? 'Faculty' : 'Student';
        $borrower_name = $row['faculty_id'] 
                        ? $row['faculty_firstname'] . ' ' . $row['faculty_middlename'] . ' ' . $row['faculty_lastname'] 
                        : $row['student_firstname'] . ' ' . $row['student_middlename'] . ' ' . $row['student_lastname'];
        $borrower_id = $row['faculty_id'] ? $row['faculty_id'] : $row['student_id'];

        $borrower_program = $row['faculty_id'] 
            ? (isset($position_mapping[$row['position']]) ? $position_mapping[$row['position']] : 'Unknown Faculty Position') 
            : $row['program_code'];

        $formatted_date_borrow = date('M j, Y', strtotime($row['date_borrow']));
        $formatted_date_paid = ($row['date_paid'] != null) ? date('M j, Y', strtotime($row['date_paid'])) : 'Not Paid';
        $status_badge = ($row['status'] == 'unpaid') 
                        ? '<span class="badge badge-danger">Unpaid</span>' 
                        : '<span class="badge badge-success">Paid</span>';

        // Fetch the fine_amount
        $fine_amount = $row['fine_amount'] !== null ? $row['fine_amount'] : 0;  // Ensure fine_amount is not null

        // Prepare JSON response
        echo json_encode([
            'borrower_name' => $borrower_name,
            'borrower_id' => $borrower_id,
            'borrower_type' => $borrower_type,
            'borrower_program' => $borrower_program,
            'accession' => $row['accession'],
            'title' => $row['title'],
            'date_borrowed' => $formatted_date_borrow,
            'due_date' => date('M j, Y', strtotime($row['due_date'])),
            'overdue_days' => $row['overdue_days'],
            'fineamount' => number_format($fine_amount, 2), // Ensure it is correctly formatted
            'status_badge' => $status_badge,
            'date_paid' => $formatted_date_paid
        ]);
    } else {
        echo json_encode(['error' => 'Fine not found.']);
    }
}
?>
