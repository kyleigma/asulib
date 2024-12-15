<?php
include 'includes/session.php';
include 'includes/conn.php';
include 'includes/header.php';

// Get the current year
$currentYear = date('Y');

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
$programs = [];  // Array to store program codes dynamically
$program_codes = []; // Array to map program ID to codes

while ($program_row = mysqli_fetch_assoc($programs_result)) {
    $programs[] = $program_row['code']; // Store program codes
    $program_codes[$program_row['id']] = $program_row['code']; // Map program ID to code
}

// Add 'FACULTY' as a special category (removed 'ALL')
$programs[] = 'FACULTY';

// Get the selected month for borrowing and returns
$selected_borrow_month = isset($_GET['borrow_month_filter']) ? $_GET['borrow_month_filter'] : date('m'); // Default to current month
$selected_return_month = isset($_GET['return_month_filter']) ? $_GET['return_month_filter'] : date('m'); // Default to current month

// Create an array for month names
$months = [
    '01' => 'January',
    '02' => 'February',
    '03' => 'March',
    '04' => 'April',
    '05' => 'May',
    '06' => 'June',
    '07' => 'July',
    '08' => 'August',
    '09' => 'September',
    '10' => 'October',
    '11' => 'November',
    '12' => 'December'
];

// Initialize monthly returns and borrows array
$returns_per_month = array_fill(0, 12, 0);
$borrows_per_month = array_fill(0, 12, 0);

// Set the start and end dates for the current year
$start_date = "$currentYear-01-01";
$end_date = "$currentYear-12-31";

// Query for returns in the selected year
$return_sql = "SELECT MONTH(date_return) as month, COUNT(*) as total 
               FROM returns 
               WHERE date_return BETWEEN '$start_date' AND '$end_date'";

// Adjust query based on selected month for returns
if ($selected_return_month !== 'ALL') {
    $return_sql .= " AND MONTH(date_return) = '$selected_return_month'";
}

$return_sql .= " GROUP BY MONTH(date_return)";
$return_query = $conn->query($return_sql);

while ($row = $return_query->fetch_assoc()) {
    $returns_per_month[$row['month'] - 1] = $row['total'];
}

// Query for borrows in the selected year
$borrow_sql = "SELECT MONTH(date_borrow) as month, COUNT(*) as total 
               FROM borrow 
               WHERE date_borrow BETWEEN '$start_date' AND '$end_date'";

// Adjust query based on selected month for borrows
if ($selected_borrow_month !== 'ALL') {
    $borrow_sql .= " AND MONTH(date_borrow) = '$selected_borrow_month'";
}

$borrow_sql .= " GROUP BY MONTH(date_borrow)";
$borrow_query = $conn->query($borrow_sql);

while ($row = $borrow_query->fetch_assoc()) {
    $borrows_per_month[$row['month'] - 1] = $row['total'];
}
?>

<body id="page-top">
    <div id="wrapper">
        <?php include 'includes/frame.php'; ?>
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Circulation Statistics</h1>
            </div>
                        
            <div class="card shadow mb-4">
                <div class="container mt-4">
                    <!-- Bootstrap Tabs -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">Borrowing</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">Returning</a>
                        </li>
                        <!-- Add more tabs as necessary -->
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                            <br>
                            <!-- Filter form to select month -->
                            <div class="form-group d-flex justify-content-between mb-4">
                                <!-- Borrowing Month Filter -->
                                <form id="borrowFilterForm">
                                    <label for="borrow_month_filter">Borrowing Month:</label>
                                    <select class="form-control" name="borrow_month_filter" id="borrow_month_filter" style="width: 100%;"> <!-- Responsive width -->
                                        <option value="ALL" <?php echo ($selected_borrow_month === 'ALL') ? 'selected' : ''; ?>>ALL</option>
                                        <?php
                                        // Create the month filter dropdown options for borrowing
                                        foreach ($months as $month_key => $month_name) {
                                            if ($month_name === 'ALL MONTHS') continue; // Skip this option
                                            $selected = ($month_key == $selected_borrow_month) ? 'selected' : '';
                                            echo "<option value='$month_key' $selected>$month_name</option>";
                                        }
                                        ?>
                                    </select>
                                </form>
                                <a href="print_borrow_statistics.php?borrow_month_filter=<?php echo $selected_borrow_month; ?>" class="btn btn-secondary" target="_blank">
                                    <i class="fas fa-print"></i> Print
                                </a>
                            </div>

                            <div class="table-responsive mt-3" id="filteredResults">
                                <!-- Circulation Statistics Table -->
                                <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>CLASS</th>
                                            <?php
                                            // Generate table headers dynamically based on programs and faculty
                                            foreach ($programs as $program) {
                                                echo "<th>$program</th>";
                                            }
                                            ?>
                                            <th>TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Loop through each category range and display counts
                                        foreach ($categories as $category_label => $range) {
                                            echo "<tr>
                                                    <td>$category_label</td>";
                                            $total_borrowed_per_category = 0; // Initialize category total

                                            // Loop through each program and calculate counts for each category
                                            foreach ($programs as $program) {
                                                if ($program === 'FACULTY') {
                                                    // Count books borrowed by faculty in the category range for the selected month
                                                    $faculty_borrow_query = "SELECT COUNT(borrow.id) as count
                                                                            FROM borrow
                                                                            JOIN books ON borrow.book_id = books.id
                                                                            WHERE books.category_no BETWEEN {$range[0]} AND {$range[1]} 
                                                                                AND borrow.student_id IS NULL";

                                                    // Adjust query based on selected month
                                                    if ($selected_borrow_month !== 'ALL') {
                                                        $faculty_borrow_query .= " AND MONTH(borrow.date_borrow) = '$selected_borrow_month'";
                                                    }

                                                    $faculty_borrow_result = mysqli_query($conn, $faculty_borrow_query);
                                                    $faculty_borrow_row = mysqli_fetch_assoc($faculty_borrow_result);
                                                    $borrowed_count = $faculty_borrow_row['count'];
                                                } else {
                                                    // Get program ID based on the program code
                                                    $program_id = array_search($program, $program_codes);

                                                    // Count books borrowed by students in the category range and program for the selected month
                                                    $program_borrow_query = "SELECT COUNT(borrow.id) as count
                                                                            FROM borrow
                                                                            JOIN books ON borrow.book_id = books.id
                                                                            JOIN students ON borrow.student_id = students.id
                                                                            WHERE books.category_no BETWEEN {$range[0]} AND {$range[1]} 
                                                                                AND students.program_id = '$program_id'";

                                                    // Adjust query based on selected month
                                                    if ($selected_borrow_month !== 'ALL') {
                                                        $program_borrow_query .= " AND MONTH(borrow.date_borrow) = '$selected_borrow_month'";
                                                    }

                                                    $program_borrow_result = mysqli_query($conn, $program_borrow_query);
                                                    $program_borrow_row = mysqli_fetch_assoc($program_borrow_result);
                                                    $borrowed_count = $program_borrow_row['count'];
                                                }

                                                // Display borrowed count for each program, and replace 0 with empty cells
                                                $display_value = ($borrowed_count == 0) ? '' : $borrowed_count;
                                                echo "<td>$display_value</td>";
                                                $total_borrowed_per_category += $borrowed_count;
                                            }

                                            // Display total borrowed books for the current category, replace 0 with empty cells
                                            $display_total = ($total_borrowed_per_category == 0) ? '0' : $total_borrowed_per_category;
                                            echo "<td><strong>$display_total</strong></td>"; // Make total bold
                                            echo "</tr>";
                                        }

                                        // Display total row
                                        echo "<tr>
                                                <td><strong>TOTAL</strong></td>";

                                        $grand_total = 0; // Initialize grand total for all programs

                                        foreach ($programs as $program) {
                                            if ($program === 'FACULTY') {
                                                // Get total books borrowed by faculty for the selected month
                                                $faculty_total_query = "SELECT COUNT(borrow.id) as count
                                                                        FROM borrow
                                                                        JOIN books ON borrow.book_id = books.id
                                                                        WHERE borrow.student_id IS NULL";

                                                // Adjust query based on selected month
                                                if ($selected_borrow_month !== 'ALL') {
                                                    $faculty_total_query .= " AND MONTH(borrow.date_borrow) = '$selected_borrow_month'";
                                                }

                                                $faculty_total_result = mysqli_query($conn, $faculty_total_query);
                                                $faculty_total_row = mysqli_fetch_assoc($faculty_total_result);
                                                $total_borrowed = $faculty_total_row['count'];
                                            } else {
                                                // Get program ID based on program code
                                                $program_id = array_search($program, $program_codes);

                                                // Get total books borrowed by students in the program for the selected month
                                                $program_total_query = "SELECT COUNT(borrow.id) as count
                                                                        FROM borrow
                                                                        JOIN students ON borrow.student_id = students.id
                                                                        WHERE students.program_id = '$program_id'";

                                                // Adjust query based on selected month
                                                if ($selected_borrow_month !== 'ALL') {
                                                    $program_total_query .= " AND MONTH(borrow.date_borrow) = '$selected_borrow_month'";
                                                }

                                                $program_total_result = mysqli_query($conn, $program_total_query);
                                                $program_total_row = mysqli_fetch_assoc($program_total_result);
                                                $total_borrowed = $program_total_row['count'];
                                            }

                                            // Display total borrowed count for each program/faculty, show 0 if applicable
                                            $display_value = ($total_borrowed == 0) ? '0' : $total_borrowed; // Show 0
                                            echo "<td><strong>$display_value</strong></td>"; // Make total bold
                                            $grand_total += $total_borrowed;
                                        }

                                        // Display the grand total, show 0 if applicable
                                        $display_grand_total = ($grand_total == 0) ? '0' : $grand_total; // Show 0
                                        echo "<td><strong>$display_grand_total</strong></td>"; // Make grand total bold
                                        echo '</tr>';
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                            <br>
                            <!-- Filter form to select month -->
                            <div class="form-group d-flex justify-content-between mb-4">
                            <form id="returnFilterForm">
                                    <label for="return_month_filter">Return Month:</label>
                                    <select class="form-control" name="return_month_filter" id="return_month_filter" style="width: 100%;"> <!-- Responsive width -->
                                        <option value="ALL" <?php echo ($selected_return_month === 'ALL') ? 'selected' : ''; ?>>ALL</option>
                                        <?php
                                        // Create the month filter dropdown options for returns
                                        foreach ($months as $month_key => $month_name) {
                                            if ($month_name === 'ALL MONTHS') continue; // Skip this option
                                            $selected = ($month_key == $selected_return_month) ? 'selected' : '';
                                            echo "<option value='$month_key' $selected>$month_name</option>";
                                        }
                                        ?>
                                    </select>
                                </form>
                                <a href="print_return_statistics.php?return_month_filter=<?php echo $selected_return_month; ?>" class="btn btn-secondary" target="_blank">
                                    <i class="fas fa-print"></i> Print
                                </a>
                            </div>


                            <div class="table-responsive mt-3" id="filteredResults">
                                <!-- Returns Statistics Table -->
                                <table class="table table-bordered" id="dataTableStats" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>CLASS</th>
                                            <?php
                                            // Generate table headers dynamically based on programs and faculty
                                            foreach ($programs as $program) {
                                                echo "<th>$program</th>";
                                            }
                                            ?>
                                            <th>TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Loop through each category range and display counts
                                        foreach ($categories as $category_label => $range) {
                                            echo "<tr>
                                                    <td>$category_label</td>";
                                            $total_returned_per_category = 0; // Initialize category total

                                            // Loop through each program and calculate counts for each category
                                            foreach ($programs as $program) {
                                                if ($program === 'FACULTY') {
                                                    // Count books returned by faculty in the category range for the selected month
                                                    $faculty_return_query = "SELECT COUNT(returns.id) as count
                                                                            FROM returns
                                                                            JOIN books ON returns.book_id = books.id
                                                                            WHERE books.category_no BETWEEN {$range[0]} AND {$range[1]} 
                                                                                AND returns.student_id IS NULL";

                                                    // Adjust query based on selected month
                                                    if ($selected_return_month !== 'ALL') {
                                                        $faculty_return_query .= " AND MONTH(returns.date_return) = '$selected_return_month'";
                                                    }

                                                    $faculty_return_result = mysqli_query($conn, $faculty_return_query);
                                                    $faculty_return_row = mysqli_fetch_assoc($faculty_return_result);
                                                    $returned_count = $faculty_return_row['count'];
                                                } else {
                                                    // Get program ID based on the program code
                                                    $program_id = array_search($program, $program_codes);

                                                    // Count books returned by students in the category range and program for the selected month
                                                    $program_return_query = "SELECT COUNT(returns.id) as count
                                                                            FROM returns
                                                                            JOIN books ON returns.book_id = books.id
                                                                            JOIN students ON returns.student_id = students.id
                                                                            WHERE books.category_no BETWEEN {$range[0]} AND {$range[1]} 
                                                                                AND students.program_id = '$program_id'";

                                                    // Adjust query based on selected month
                                                    if ($selected_return_month !== 'ALL') {
                                                        $program_return_query .= " AND MONTH(returns.date_return) = '$selected_return_month'";
                                                    }

                                                    $program_return_result = mysqli_query($conn, $program_return_query);
                                                    $program_return_row = mysqli_fetch_assoc($program_return_result);
                                                    $returned_count = $program_return_row['count'];
                                                }

                                                // Display returned count for each program, and replace 0 with empty cells
                                                $display_value = ($returned_count == 0) ? '' : $returned_count;
                                                echo "<td>$display_value</td>";
                                                $total_returned_per_category += $returned_count;
                                            }

                                            // Display total returned books for the current category, replace 0 with empty cells
                                            $display_total = ($total_returned_per_category == 0) ? '0' : $total_returned_per_category;
                                            echo "<td><strong>$display_total</strong></td>"; // Make total bold
                                            echo "</tr>";
                                        }

                                        // Display total row
                                        echo "<tr>
                                                <td><strong>TOTAL</strong></td>";

                                        $grand_return_total = 0; // Initialize grand total for all programs

                                        foreach ($programs as $program) {
                                            if ($program === 'FACULTY') {
                                                // Get total books returned by faculty for the selected month
                                                $faculty_return_total_query = "SELECT COUNT(returns.id) as count
                                                                                FROM returns
                                                                                JOIN books ON returns.book_id = books.id
                                                                                WHERE returns.student_id IS NULL";

                                                // Adjust query based on selected month
                                                if ($selected_return_month !== 'ALL') {
                                                    $faculty_return_total_query .= " AND MONTH(returns.date_return) = '$selected_return_month'";
                                                }

                                                $faculty_return_total_result = mysqli_query($conn, $faculty_return_total_query);
                                                $faculty_return_total_row = mysqli_fetch_assoc($faculty_return_total_result);
                                                $total_returned = $faculty_return_total_row['count'];
                                            } else {
                                                // Get program ID based on program code
                                                $program_id = array_search($program, $program_codes);

                                                // Get total books returned by students in the program for the selected month
                                                $program_return_total_query = "SELECT COUNT(returns.id) as count
                                                                                FROM returns
                                                                                JOIN students ON returns.student_id = students.id
                                                                                WHERE students.program_id = '$program_id'";

                                                // Adjust query based on selected month
                                                if ($selected_return_month !== 'ALL') {
                                                    $program_return_total_query .= " AND MONTH(returns.date_return) = '$selected_return_month'";
                                                }

                                                $program_return_total_result = mysqli_query($conn, $program_return_total_query);
                                                $program_return_total_row = mysqli_fetch_assoc($program_return_total_result);
                                                $total_returned = $program_return_total_row['count'];
                                            }

                                            // Display total returned count for each program/faculty, show 0 if applicable
                                            $display_value = ($total_returned == 0) ? '0' : $total_returned; // Show 0
                                            echo "<td><strong>$display_value</strong></td>"; // Make total bold
                                            $grand_return_total += $total_returned;
                                        }

                                        // Display the grand total, show 0 if applicable
                                        $display_grand_return_total = ($grand_return_total == 0) ? '0' : $grand_return_total; // Show 0
                                        echo "<td><strong>$display_grand_return_total</strong></td>"; // Make grand total bold
                                        echo '</tr>';
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Add more tab-pane as necessary -->
                    </div>
                </div>
            </div>


        <script>
            $(document).ready(function() {
                $('#dataTable1').DataTable({
                    "pageLength": 25  // Set default entries to 25
                });
                $('#dataTable2').DataTable({
                    "pageLength": 25  // Set default entries to 25
                });
            });
        </script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Function to filter borrowing results
        $('#borrow_month_filter').on('change', function() {
            var selectedMonth = $(this).val();
            $.ajax({
                url: 'your-borrow-action-url.php', // URL to your PHP script that handles filtering
                type: 'POST',
                data: { borrow_month_filter: selectedMonth },
                success: function(data) {
                    $('#filteredResults').html(data); // Update the filtered results section with the response
                }
            });
        });

        // Function to filter return results
        $('#return_month_filter').on('change', function() {
            var selectedMonth = $(this).val();
            $.ajax({
                url: 'your-return-action-url.php', // URL to your PHP script that handles filtering
                type: 'POST',
                data: { return_month_filter: selectedMonth },
                success: function(data) {
                    $('#filteredResults').html(data); // Update the filtered results section with the response
                }
            });
        });
    });
</script>


    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/logout_modal.php'; ?>
    <?php include 'includes/scripts.php'; ?>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

</body>
</html>