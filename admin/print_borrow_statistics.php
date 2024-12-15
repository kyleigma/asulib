<?php
include 'includes/session.php';
include 'includes/conn.php';

// Get the selected month from the query string, default to current month if not set
$selected_month = isset($_GET['borrow_month_filter']) ? $_GET['borrow_month_filter'] : date('m');

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

$programs[] = 'FACULTY';

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
            size: 13in 8.5in; /* Set the page size to 8.5x13 inches */
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
        background-color: #fff; /* Bootstrap primary color */
        color: black; /* black text for headers */
        border: 2px solid #000; /* Thicker border for header */
    }
    /* New styles for the total row */
    .total-row {
        font-weight: bold; /* Bold text */
        border: 2px solid #000; /* Thicker border for total row */
    }
    h1 {
        text-align: center; /* Center align heading */
        margin-bottom: 20px; /* Space below heading */
    }
    /* New styles to center the text and adjust line height */
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
    <p class="text-center mb-1">(Borrowed Books)</p>
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
                    $total_borrowed_per_category = 0;

                    foreach ($programs as $program) {
                        if ($program === 'FACULTY') {
                            $faculty_borrow_query = "SELECT COUNT(borrow.id) as count
                                                     FROM borrow
                                                     JOIN books ON borrow.book_id = books.id
                                                     WHERE books.category_no BETWEEN {$range[0]} AND {$range[1]} 
                                                           AND borrow.student_id IS NULL";
                            if ($selected_month !== 'ALL') {
                                $faculty_borrow_query .= " AND MONTH(borrow.date_borrow) = '$selected_month'";
                            }

                            $faculty_borrow_result = mysqli_query($conn, $faculty_borrow_query);
                            $faculty_borrow_row = mysqli_fetch_assoc($faculty_borrow_result);
                            $borrowed_count = $faculty_borrow_row['count'];
                        } else {
                            $program_id = array_search($program, $program_codes);
                            $program_borrow_query = "SELECT COUNT(borrow.id) as count
                                                     FROM borrow
                                                     JOIN books ON borrow.book_id = books.id
                                                     JOIN students ON borrow.student_id = students.id
                                                     WHERE books.category_no BETWEEN {$range[0]} AND {$range[1]} 
                                                           AND students.program_id = '$program_id'";
                            if ($selected_month !== 'ALL') {
                                $program_borrow_query .= " AND MONTH(borrow.date_borrow) = '$selected_month'";
                            }

                            $program_borrow_result = mysqli_query($conn, $program_borrow_query);
                            $program_borrow_row = mysqli_fetch_assoc($program_borrow_result);
                            $borrowed_count = $program_borrow_row['count'];
                        }

                        $display_value = ($borrowed_count == 0) ? '' : $borrowed_count;
                        echo "<td>$display_value</td>";
                        $total_borrowed_per_category += $borrowed_count;
                    }

                    $display_total = ($total_borrowed_per_category == 0) ? '0' : $total_borrowed_per_category;
                    echo "<td><strong>$display_total</strong></td>";
                    echo "</tr>";
                }

                echo "<tr class='total-row'>
                        <td>TOTAL</td>";

                $grand_total = 0;

                foreach ($programs as $program) {
                    if ($program === 'FACULTY') {
                        $faculty_total_query = "SELECT COUNT(borrow.id) as count
                                                FROM borrow
                                                JOIN books ON borrow.book_id = books.id
                                                WHERE borrow.student_id IS NULL";
                        if ($selected_month !== 'ALL') {
                            $faculty_total_query .= " AND MONTH(borrow.date_borrow) = '$selected_month'";
                        }

                        $faculty_total_result = mysqli_query($conn, $faculty_total_query);
                        $faculty_total_row = mysqli_fetch_assoc($faculty_total_result);
                        $total_borrowed = $faculty_total_row['count'];
                    } else {
                        $program_id = array_search($program, $program_codes);
                        $program_total_query = "SELECT COUNT(borrow.id) as count
                                                FROM borrow
                                                JOIN students ON borrow.student_id = students.id
                                                WHERE students.program_id = '$program_id'";
                        if ($selected_month !== 'ALL') {
                            $program_total_query .= " AND MONTH(borrow.date_borrow) = '$selected_month'";
                        }

                        $program_total_result = mysqli_query($conn, $program_total_query);
                        $program_total_row = mysqli_fetch_assoc($program_total_result);
                        $total_borrowed = $program_total_row['count'];
                    }

                    $display_value = ($total_borrowed == 0) ? '0' : $total_borrowed;
                    echo "<td><strong>$display_value</strong></td>";
                    $grand_total += $total_borrowed;
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
