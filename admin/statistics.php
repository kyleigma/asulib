<?php
include 'includes/session.php';
include 'includes/conn.php';
include 'includes/header.php';

// Define the range of years
$minYear = 2024;
$maxYear = 2050;

// Get the current year or the selected year from the session/URL
if (isset($_GET['year']) && $_GET['year'] >= $minYear && $_GET['year'] <= $maxYear) {
    $selectedYear = $_GET['year'];
    $_SESSION['selectedYear'] = $selectedYear;
} else {
    $selectedYear = isset($_SESSION['selectedYear']) ? $_SESSION['selectedYear'] : date('Y');
}

// Fetch distinct years from the borrowing and returns data
$years = range($minYear, $maxYear);

// Fetch borrowing statistics filtered by the selected year
$borrowStatsQuery = "SELECT * FROM borrow WHERE YEAR(date_borrow) = '$selectedYear'";
$borrowStats = $conn->query($borrowStatsQuery);

// Fetch return statistics filtered by the selected year
$returnStatsQuery = "SELECT * FROM returns WHERE YEAR(date_return) = '$selectedYear'";
$returnStats = $conn->query($returnStatsQuery);

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

$selected_borrow_month = isset($_GET['borrow_month_filter']) ? $_GET['borrow_month_filter'] : 'ALL';
$selected_return_month = isset($_GET['return_month_filter']) ? $_GET['return_month_filter'] : 'ALL';

// Array of months for dropdown
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
    '12' => 'December',
];

// Initialize monthly returns and borrows array
$returns_per_month = array_fill(0, 12, 0);
$borrows_per_month = array_fill(0, 12, 0);

// Set the start and end dates for the selected year
$start_date = "$selectedYear-01-01";
$end_date = "$selectedYear-12-31";

// Query for borrows in the selected year
$borrow_sql = "SELECT MONTH(date_borrow) as month, COUNT(*) as total 
               FROM borrow 
               WHERE date_borrow BETWEEN '$start_date' AND '$end_date'";

// Adjust query based on selected month for borrows
if ($selected_borrow_month !== 'ALL') {
    $borrow_sql .= " AND MONTH(date_borrow) = '$selected_borrow_month'";
}

$borrow_sql .= " GROUP BY MONTH(date_borrow )";
$borrow_query = $conn->query($borrow_sql);

while ($row = $borrow_query->fetch_assoc()) {
    $borrows_per_month[$row['month'] - 1] = $row['total'];
}

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
?>

<body id="page-top">
    <div id="wrapper">
        <?php include 'includes/frame.php'; ?>
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Circulation Statistics</h1>
                <nav style="--bs-breadcrumb-divider: '>';font-size:85%;" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class=""><a href="home.php">Dashboard</a></li>&nbsp;&nbsp;&nbsp;
                        <li class=""><i class="fas fa-angle-right"></i></li>&nbsp;&nbsp;&nbsp;
                        <li class="active" aria-current="page">Circulation Statistics</li>
                    </ol>
                </nav>
            </div>

            <!-- Year Filter inside Card Body -->
            <div class="card-header">
                <div class="form-group mb-2">
                    <form method="GET" action="statistics.php" class="d-flex align-items-center">
                        <label for="year" class="mr-2 mb-0">Select Year:</label>
                        <select name="year" id="year" class="form-control" onchange="this.form.submit()" style="width: auto;">
                            <?php foreach ($years as $year): ?>
                                <option value="<?php echo $year; ?>" <?php echo ($year == $selectedYear) ? 'selected' : ''; ?>>
                                    <?php echo $year; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>

            <div class="card shadow mb-4">
                <!-- Card Header - Accordion -->
                <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse"
                    role="button" aria-expanded="true" aria-controls="collapseCardExample">
                    <h5 class="m-0 font-weight-bold text-primary">Borrowing</h5>
                </a>
                <!-- Card Content - Collapse -->
                <div class="collapse show" id="collapseCardExample">
                    <div class="card-body">
                        <!-- Borrow Month Filter -->
                        <div class="form-group d-flex justify-content-between mb-4">
                            <form method="GET" action="" class="d-flex align-items-center">
                                <label for="borrow_month_filter" class="mr-2 mb-0">Filter:</label>
                                <select class="form-control" name="borrow_month_filter" id="borrow_month_filter" onchange="this.form.submit()" style="width: auto;">
                                    <option value="ALL" <?php echo ($selected_borrow_month === 'ALL') ? 'selected' : ''; ?>>ALL</option>
                                    <?php
                                    foreach ($months as $month_key => $month_name) {
                                        $selected = ($month_key == $selected_borrow_month) ? 'selected' : '';
                                        echo "<option value='$month_key' $selected>$month_name</option>";
                                    }
                                    ?>
                                </select>
                                <!-- Preserve return_month_filter -->
                                <input type="hidden" name="return_month_filter" value="<?php echo $selected_return_month; ?>">
                            </form>
                            <a href="print_borrow_statistics.php?borrow_month_filter=<?php echo $selected_borrow_month; ?>" class="btn btn-secondary" onclick="window.open(this.href, '_blank', 'width=1000, height=600'); return false;">
                                <i class="fas fa-print"></i> Print
                            </a>
                        </div>

                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-striped" id="dataTableStats1" width="100%" cellspacing="0">
                                <thead class="text-center">
                                    <tr>
                                        <th>CLASS</th>
                                        <?php
                                        foreach ($programs as $program) {
                                            echo "<th>$program</th>";
                                        }
                                        ?>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
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
                                                                            AND borrow.student_id IS NULL
                                                                            AND YEAR(borrow.date_borrow) = '$selectedYear'";

                                                if ($selected_borrow_month !== 'ALL') {
                                                    $faculty_borrow_query .= " AND MONTH(borrow.date_borrow) = '$selected_borrow_month'";
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
                                                                            AND students.program_id = '$program_id'
                                                                            AND YEAR(borrow.date_borrow) = '$selectedYear'";

                                                if ($selected_borrow_month !== 'ALL') {
                                                    $program_borrow_query .= " AND MONTH(borrow.date_borrow) = '$selected_borrow_month'";
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

                                    echo "<tr>
                                            <td><strong>TOTAL</strong></td>";

                                    $grand_total = 0;

                                    foreach ($programs as $program) {
                                        if ($program === 'FACULTY') {
                                            $faculty_total_query = "SELECT COUNT(borrow.id) as count
                                                                    FROM borrow
                                                                    JOIN books ON borrow.book_id = books.id
                                                                    WHERE borrow.student_id IS NULL
                                                                    AND YEAR(borrow.date_borrow) = '$selectedYear'";

                                            if ($selected_borrow_month !== 'ALL') {
                                                $faculty_total_query .= " AND MONTH(borrow.date_borrow) = '$selected_borrow_month'";
                                            }

                                            $faculty_total_result = mysqli_query($conn, $faculty_total_query);
                                            $faculty_total_row = mysqli_fetch_assoc($faculty_total_result);
                                            $total_borrowed = $faculty_total_row['count'];
                                        } else {
                                            $program_id = array_search($program, $program_codes);
                                            $program_total_query = "SELECT COUNT(borrow.id) as count
                                                                    FROM borrow
                                                                    JOIN students ON borrow.student_id = students.id
                                                                    WHERE students.program_id = '$program_id'
                                                                    AND YEAR(borrow.date_borrow) = '$selectedYear'";

                                            if ($selected_borrow_month !== 'ALL') {
                                                $program_total_query .= " AND MONTH(borrow.date_borrow) = '$selected_borrow_month'";
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
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <a href="#collapseCardExample1" class="d-block card-header py-3" data-toggle="collapse"
                    role=" button" aria-expanded="true" aria-controls="collapseCardExample1">
                    <h5 class="m-0 font-weight-bold text-primary">Returns</h5>
                </a>
                <div class="collapse show" id="collapseCardExample1">
                    <div class="card-body">
                        <!-- Return Month Filter -->
                        <div class="form-group d-flex justify-content-between mb-4">
                            <form method="GET" action="" class="d-flex align-items-center">
                                <label for="return_month_filter" class="mr-2 mb-0">Filter:</label>
                                <select class="form-control" name="return_month_filter" id="return_month_filter" onchange="this.form.submit()" style="width: auto;">
                                    <option value="ALL" <?php echo ($selected_return_month === 'ALL') ? 'selected' : ''; ?>>ALL</option>
                                    <?php
                                    foreach ($months as $month_key => $month_name) {
                                        $selected = ($month_key == $selected_return_month) ? 'selected' : '';
                                        echo "<option value='$month_key' $selected>$month_name</option>";
                                    }
                                    ?>
                                </select>
                                <!-- Preserve borrow_month_filter -->
                                <input type="hidden" name="borrow_month_filter" value="<?php echo $selected_borrow_month; ?>">
                            </form>
                            <a href="print_return_statistics.php?return_month_filter=<?php echo $selected_return_month; ?>" class="btn btn-secondary" onclick="window.open(this.href, '_blank', 'width=1000, height=600'); return false;">
                                <i class="fas fa-print"></i> Print
                            </a>
                        </div>

                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-striped" id="dataTableStats2" width="100%" cellspacing="0">
                                <thead class="text-center">
                                    <tr>
                                        <th>CLASS</th>
                                        <?php
                                        foreach ($programs as $program) {
                                            echo "<th>$program</th>";
                                        }
                                        ?>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
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
                                                                            AND returns.student_id IS NULL
                                                                            AND YEAR(returns.date_return) = '$selectedYear'";

                                                if ($selected_return_month !== 'ALL') {
                                                    $faculty_return_query .= " AND MONTH(returns.date_return) = '$selected_return_month'";
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
                                                                            AND students.program_id = '$program_id'
                                                                            AND YEAR(returns.date_return) = '$selectedYear'";

                                                if ($selected_return_month !== 'ALL') {
                                                    $program_return_query .= " AND MONTH(returns.date_return) = '$selected_return_month'";
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

                                    echo "<tr>
                                            <td><strong>TOTAL</strong></td>";

                                    $grand_return_total = 0;

                                    foreach ($programs as $program) {
                                        if ($program === 'FACULTY') {
                                            $faculty_return_total_query = "SELECT COUNT(returns.id) as count
                                                                            FROM returns
                                                                            JOIN books ON returns.book_id = books.id
                                                                            WHERE returns.student_id IS NULL
                                                                            AND YEAR(returns.date_return) = '$ selectedYear'";

                                            if ($selected_return_month !== 'ALL') {
                                                $faculty_return_total_query .= " AND MONTH(returns.date_return) = '$selected_return_month'";
                                            }

                                            $faculty_return_total_result = mysqli_query($conn, $faculty_return_total_query);
                                            $faculty_return_total_row = mysqli_fetch_assoc($faculty_return_total_result);
                                            $total_returned = $faculty_return_total_row['count'];
                                        } else {
                                            $program_id = array_search($program, $program_codes);
                                            $program_return_total_query = "SELECT COUNT(returns.id) as count
                                                                            FROM returns
                                                                            JOIN students ON returns.student_id = students.id
                                                                            WHERE students.program_id = '$program_id'
                                                                            AND YEAR(returns.date_return) = '$selectedYear'";

                                            if ($selected_return_month !== 'ALL') {
                                                $program_return_total_query .= " AND MONTH(returns.date_return) = '$selected_return_month'";
                                            }

                                            $program_return_total_result = mysqli_query($conn, $program_return_total_query);
                                            $program_return_total_row = mysqli_fetch_assoc($program_return_total_result);
                                            $total_returned = $program_return_total_row['count'];
                                        }

                                        $display_value = ($total_returned == 0) ? '0' : $total_returned;
                                        echo "<td><strong>$display_value</strong></td>";
                                        $grand_return_total += $total_returned;
                                    }

                                    $display_grand_return_total = ($grand_return_total == 0) ? '0' : $grand_return_total;
                                    echo "<td><strong>$display_grand_return_total</strong></td>";
                                    echo '</tr>';
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <a href="#collapseCardExample2" class="d-block card-header py-3" data-toggle="collapse"
                    role="button" aria-expanded="true" aria-controls="collapseCardExample2">
                    <h5 class="m-0 font-weight-bold text-primary">Popular Books</h5>
                </a>
                <div class="collapse show" id="collapseCardExample2">
                    <div class="card-body">
                        <div class="table-responsive flex-grow-1">
                            <table id="popularBooksTable" class="table table-striped table-bordered" style="width: 100%;">
                                <thead class="text-center">
                                    <tr>
                                        <th width="25">Accession No.</th>
                                        <th width="300">Title</th>
                                        <th width="150">Author</th>
                                        <th width="50">Borrow Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT b.title, b.author, b.accession, COUNT(br.book_id) AS borrow_count
                                            FROM borrow br
                                            INNER JOIN books b ON br.book_id = b.id
                                            GROUP BY b.title, b.author, b.accession
                                            ORDER BY borrow_count DESC";

                                    $query = $conn->query($sql);

                                    while ($row = $query->fetch_assoc()) {
                                        echo "<tr>
                                                <td>{$row['accession']}</td>
                                                <td>{$row['title']}</td>
                                                <td class='text-center'>{$row['author']}</td>
                                                <td class='text-center'>{$row['borrow_count']}</td>
                                            </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/logout_modal.php'; ?>
    <?php include 'includes/scripts.php'; ?>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

</body>
</html>