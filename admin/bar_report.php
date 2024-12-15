<?php
// Include necessary files for DB connection and session
include 'includes/session.php';
include 'includes/conn.php';

// Handle the selected year from AJAX
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Initialize monthly transactions arrays
$borrows_per_month = array_fill(0, 12, 0);
$returns_per_month = array_fill(0, 12, 0);
$overdues_per_month = array_fill(0, 12, 0);

// Set the start and end dates for the selected year
$start_date = "$year-01-01";
$end_date = "$year-12-31";

// Query for borrows in the selected year
$borrow_sql = "SELECT MONTH(date_borrow) as month, COUNT(*) as total 
               FROM borrow 
               WHERE date_borrow BETWEEN '$start_date' AND '$end_date'
               GROUP BY MONTH(date_borrow)";
$borrow_query = $conn->query($borrow_sql);

while($row = $borrow_query->fetch_assoc()) {
    $borrows_per_month[$row['month'] - 1] = $row['total'];
}

// Query for returns in the selected year
$return_sql = "SELECT MONTH(date_return) as month, COUNT(*) as total 
               FROM returns 
               WHERE date_return BETWEEN '$start_date' AND '$end_date'
               GROUP BY MONTH(date_return)";
$return_query = $conn->query($return_sql);

while($row = $return_query->fetch_assoc()) {
    $returns_per_month[$row['month'] - 1] = $row['total'];
}

// Query for overdue fines in the selected year
$overdue_sql = "SELECT MONTH(date_borrow) as month, COUNT(*) as total 
                FROM fines 
                WHERE date_borrow BETWEEN '$start_date' AND '$end_date' AND status = 'unpaid'
                GROUP BY MONTH(date_borrow)";
$overdue_query = $conn->query($overdue_sql);

while($row = $overdue_query->fetch_assoc()) {
    $overdues_per_month[$row['month'] - 1] = $row['total'];
}

// Return JSON response for AJAX
echo json_encode(array(
    'borrows' => $borrows_per_month,
    'returns' => $returns_per_month,
    'overdues' => $overdues_per_month
));
?>
