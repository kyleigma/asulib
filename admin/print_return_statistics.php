<?php
include 'includes/session.php';
include 'includes/conn.php';

// Get the selected month from the query string, default to current month if not set
$selected_month = isset($_GET['return_month_filter']) ? $_GET['return_month_filter'] : date('m');

// Define the category ranges
$categories = [
    '000s' => [0, 99],
    '100s' => [100, 199],
    '200s' => [200, 299],
    '300s' => [300, 399],
    '400s' => [400, 499],
    '500s' => [500, 599],
    '600s' => [600, 699],
    '700s' => [700, 799],
    '800s' => [800, 899],
    '900s' => [900, 999]
];

// Fetch all programs from the `program` table
$programs_query = "SELECT id, code FROM program"; // Ensure the table name is correct
$programs_result = mysqli_query($conn, $programs_query);
$programs = [];
$program_codes = [];

while ($program_row = mysqli_fetch_assoc($programs_result)) {
    $programs[] = $program_row['code'];
    $program_codes[$program_row['id']] = $program_row['code'];
}

$programs[] = 'FACULTY'; // Add 'FACULTY' to the program list

// HTML Header
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Circulation Statistics - Print</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.cdnfonts.com/css/arial-2" rel="stylesheet">
    <style>
    @media print {
        .no-print {
            display: none;
        }
        @page {
            size: 13in 8.5in; /* Set portrait orientation with 8.5 x 11 inches */
            margin: 10mm; /* Smaller margins */
        }
        header, footer {
            display: none; /* Remove header and footer */
        }
    }
    body {
        font-family: 'Open Sans', sans-serif; /* Use Open Sans font */
    }
    table {
        width: 100%;
        border-collapse: collapse; /* Merge borders for a cleaner look */
    }
    th, td {
        border: 1px solid #000; /* Solid black borders */
        padding: 8px; /* Padding for table cells */
        text-align: center; /* Center align text */
    }
    th {
        background-color: #f8f9fa; /* Light background color for header */
        color: black; /* black text for headers */
        border: 2px solid #000; /* Thicker border for header */
    }
    .total-row {
        font-weight: bold; /* Bold text for total row */
        border: 2px solid #000; /* Thicker border for total row */
    }
    h1 {
        text-align: center; /* Center align heading */
        margin-bottom: 20px; /* Space below heading */
    }
    .container p {
        text-align: center; /* Center align all paragraph text */
        margin: 5px 0; /* Adjust margins for paragraphs */
        line-height: 1.2; /* Reduce line height for less spacing */
    }
</style>
</head>
<body>
<div class="container d-flex flex-column align-items-center">
    <b><p class="text-center mb-1">AKLAN STATE UNIVERSITY – KALIBO, AKLAN</p>
    <p class="text-center mb-1">LIBRARY AND INFORMATION SERVICES</p></b>
    <br>
    <b><p class="text-center mb-1">DAILY CIRCULATION STATISTICS</p></b>
    <p class="text-center mb-1">(Returned Books)</p>
    <p class="text-center mb-1">
        <strong>MONTH OF:</strong> <?php echo date('F', mktime(0, 0, 0, $selected_month, 1)); ?> <?php echo date('Y'); ?>
    </p>
    <br>
</div>
    
<div class="container mt-5">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>CLASS</th>
                    <?php foreach ($programs as $program) { echo "<th>$program</th>"; } ?>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($categories as $category_label => $range) {
                    echo "<tr>
                            <td>$category_label</td>";
                    $total_returned_per_category = 0;

                    foreach ($programs as $program) {
                        if ($program === 'FACULTY') {
                            $faculty_return_query = "SELECT COUNT(returns.id) as count
                                                     FROM returns
                                                     JOIN books ON returns.book_id = books.id
                                                     WHERE books.category_no BETWEEN {$range[0]} AND {$range[1]} 
                                                           AND returns.student_id IS NULL";
                            if ($selected_month !== 'ALL') {
                                $faculty_return_query .= " AND MONTH(returns.date_return) = '$selected_month'";
                            }

                            $faculty_return_result = mysqli_query($conn, $faculty_return_query);
                            $faculty_return_row = mysqli_fetch_assoc($faculty_return_result);
                            $returned_count = $faculty_return_row['count'];
                        } else {
                            $program_id = array_search($program, $program_codes);
                            $program_return_query = "SELECT COUNT(returns.id) as count
                                                     FROM returns
                                                     JOIN books ON returns.book_id = books.id
                                                     JOIN students ON returns.student_id = students.id
                                                     WHERE books.category_no BETWEEN {$range[0]} AND {$range[1]} 
                                                           AND students.program_id = '$program_id'";
                            if ($selected_month !== 'ALL') {
                                $program_return_query .= " AND MONTH(returns.date_return) = '$selected_month'";
                            }

                            $program_return_result = mysqli_query($conn, $program_return_query);
                            $program_return_row = mysqli_fetch_assoc($program_return_result);
                            $returned_count = $program_return_row['count'];
                        }

                        $display_value = ($returned_count == 0) ? '' : $returned_count;
                        echo "<td>$display_value</td>";
                        $total_returned_per_category += $returned_count;
                    }

                    $display_total = ($total_returned_per_category == 0) ? '0' : $total_returned_per_category;
                    echo "<td><strong>$display_total</strong></td>";
                    echo "</tr>";
                }

                echo "<tr class='total-row'>
                        <td>TOTAL</td>";

                $grand_total = 0;

                foreach ($programs as $program) {
                    if ($program === 'FACULTY') {
                        $faculty_total_query = "SELECT COUNT(returns.id) as count
                                                FROM returns
                                                JOIN books ON returns.book_id = books.id
                                                WHERE returns.student_id IS NULL";
                        if ($selected_month !== 'ALL') {
                            $faculty_total_query .= " AND MONTH(returns.date_return) = '$selected_month'";
                        }

                        $faculty_total_result = mysqli_query($conn, $faculty_total_query);
                        $faculty_total_row = mysqli_fetch_assoc($faculty_total_result);
                        $total_returned = $faculty_total_row['count'];
                    } else {
                        $program_id = array_search($program, $program_codes);
                        $program_total_query = "SELECT COUNT(returns.id) as count
                                                FROM returns
                                                JOIN students ON returns.student_id = students.id
                                                WHERE students.program_id = '$program_id'";
                        if ($selected_month !== 'ALL') {
                            $program_total_query .= " AND MONTH(returns.date_return) = '$selected_month'";
                        }

                        $program_total_result = mysqli_query($conn, $program_total_query);
                        $program_total_row = mysqli_fetch_assoc($program_total_result);
                        $total_returned = $program_total_row['count'];
                    }

                    $display_value = ($total_returned == 0) ? '0' : $total_returned;
                    echo "<td><strong>$display_value</strong></td>";
                    $grand_total += $total_returned;
                }

                $display_grand_total = ($grand_total == 0) ? '0' : $grand_total;
                echo "<td><strong>$display_grand_total</strong></td>";
                echo '</tr>';
                ?>
            </tbody>
        </table>
    </div>
    <br>
    
    <button class="btn btn-primary no-print" onclick="window.print();">Print this page</button>
</div>
<script>
    window.onload = function() {
        window.print();
    }
</script>
</body>
</html>
