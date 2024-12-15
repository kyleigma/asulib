<?php
include 'includes/session.php';
include 'includes/conn.php';

// Check if 'borrow_id' is passed in the URL
if (isset($_GET['borrow_id']) && !empty($_GET['borrow_id'])) {
    // Sanitize the input to prevent SQL injection
    $borrow_id = mysqli_real_escape_string($conn, $_GET['borrow_id']);
    
    // Prepare and execute the SQL query
    $qry = mysqli_query($conn, "
        SELECT 
            borrow.*, 
            students.student_id AS student_id, 
            students.firstname AS student_firstname, 
            students.lastname AS student_lastname, 
            faculty.faculty_id AS faculty_id, 
            faculty.firstname AS faculty_firstname, 
            faculty.lastname AS faculty_lastname, 
            books.accession, 
            books.title 
        FROM borrow 
        LEFT JOIN students ON students.id = borrow.student_id 
        LEFT JOIN faculty ON faculty.id = borrow.faculty_id 
        LEFT JOIN books ON books.id = borrow.book_id 
        WHERE borrow.id = '$borrow_id'
    ") or die(mysqli_error($conn));

    // Check if the query returns a result
    if ($qry && mysqli_num_rows($qry) > 0) {
        $qry2 = mysqli_fetch_array($qry);

        // Determine the borrow status
        $status = ($qry2['status'] == 1) ? 'Borrowed' : 'Returned';

    } else {
        die("No record found for this borrow ID.");
    }
} else {
    die("No borrow ID provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>

    <!-- Include Open Sans font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <style>
        @page {
            size: 8.5in 11in; /* Set page size to letter */
            margin: 0.5in; /* Remove default margin */
        }
        body { 
            font-family: 'Open Sans', sans-serif;
            line-height: 1; /* Remove line spacing */
        }
        .receipt-container { 
            height: 3in; /* Set height to 3 inches */
            width: 600px; 
            margin: 0 auto; 
            padding: 20px; 
            border: 1px solid #ccc; 
        }
        h2 { 
            text-align: center; 
            margin: 0; /* Remove margin for tighter layout */
        }
        p { 
            margin: 0; /* Remove all margins for tighter spacing */
        }
        .flex-container {
            display: flex; 
            justify-content: space-between; /* Align items at both ends */
            margin-bottom: 5px; /* Adjust spacing if needed */
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; /* Adjust spacing */
        }
        table th, table td { 
            padding: 8px; 
            border: 1px solid #ccc; 
            text-align: left; 
        }
        .btn-print { 
            display: block; 
            text-align: center; 
            margin-top: 20px; 
        }
        /* Right-aligned heading */
        .header-right {
            text-align: right;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .spaced-heading {
            letter-spacing: 2px; /* Adjust the value to your preference */
            text-align: center; /* Center the heading */
        }
        /* Hide the print button when printing */
        @media print {
            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print();">

    <div class="receipt-container">
        <!-- Right-aligned heading -->
        <div class="header-right">
            <p>AKLAN STATE UNIVERSITY</p>
            <p>LIBRARY AND INFORMATION SERVICES</p>
            <p>KALIBO, AKLAN</p>
        </div>

        <h2 class="spaced-heading">BORROWING RECEIPT</h2>
        <hr> <br>
        <div class="flex-container">
            <?php if (!empty($qry2['student_id'])): ?>
                <p><strong>Student ID:</strong> <?php echo $qry2['student_id']; ?></p>
            <?php elseif (!empty($qry2['faculty_id'])): ?>
                <p><strong>Faculty ID:</strong> <?php echo $qry2['faculty_id']; ?></p>
            <?php endif; ?>
            <p><strong>Date:</strong> <?php echo date('M d, Y', strtotime($qry2['date_borrow'])); ?></p>
        </div>
        <p><strong>Name:</strong> 
            <?php 
                if (!empty($qry2['student_firstname'])) {
                    echo $qry2['student_firstname'] . ' ' . $qry2['student_lastname']; 
                } elseif (!empty($qry2['faculty_firstname'])) {
                    echo $qry2['faculty_firstname'] . ' ' . $qry2['faculty_lastname'];
                } else {
                    echo "N/A"; // In case both are null
                }
            ?>
        </p>
        
        <table>
            <thead>
                <tr>
                    <th width="118">Accession No.</th>
                    <th>Book Title</th>
                    <th width="20">Status</th>
                    <th width="100">Due Date</th> <!-- New Due Date Header -->
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $qry2['accession']; ?></td>
                    <td><?php echo $qry2['title']; ?></td>
                    <td><?php echo $status; ?></td>
                    <td><?php echo date('M d, Y', strtotime($qry2['due_date'])); ?></td> <!-- Due Date Display -->
                </tr>
            </tbody>
        </table>
        <br>
        <div class="flex-container">
            <p><strong>Issued by: &nbsp;</strong><span style="border-bottom: 1px solid black; padding-left: 300px">&nbsp;</span></p>
        </div>

        <div class="btn-print">
            <button onclick="window.print();">Print Receipt</button>
        </div>
    </div>

</body>
</html>
