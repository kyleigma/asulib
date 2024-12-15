<?php include 'includes/session.php'; ?>
<?php include 'includes/conn.php'; ?>
<?php include 'includes/header.php'; ?>

<?php 
  include 'includes/timezone.php'; 
  $today = date('Y-m-d');
  $year = date('Y');
  if(isset($_GET['year'])){
    $year = $_GET['year'];
  }
?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
    <?php include 'includes/frame.php'; ?>
        <div> 
        
            <div>
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <?php
                        // Check and display the welcome message
                        if (isset($_SESSION['welcome_message'])) {
                            echo "
                                <div class='alert alert-success alert-dismissible'>
                                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                    <span class='mx-auto h5'><i class='icon fa fa-home'> </i>&nbsp;".$_SESSION['welcome_message']."</span>
                                </div>
                            ";
                            // Unset the welcome message to prevent it from showing again
                            unset($_SESSION['welcome_message']);
                        }
                    ?>

                    <!-- Page Heading -->
                    <div class="d-flex flex-column align-items-start justify-content-start mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                    <div class="col-xl-3 col-md-6 mb-4">
                        <a href="book.php" title="ⓘ More info" style="text-decoration: none; color: inherit;">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Books</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php
                                                    $sql = "SELECT * FROM books";
                                                    $query = $conn->query($sql);

                                                    echo $query->num_rows;
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-book fa-4x text-gray-300 scale-icon primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <a href="book.php" title="ⓘ More info" style="text-decoration: none; color: inherit;">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Available Books</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php
                                                $sql = "SELECT COUNT(*) as total_books 
                                                        FROM books 
                                                        WHERE status = 0"; // Only count books with status = 0 (available)
                                                $query = $conn->query($sql);

                                                $result = $query->fetch_assoc();
                                                echo $result['total_books'];
                                            ?>

                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-book fa-4x text-gray-300 scale-icon primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <a href="student.php" title="ⓘ More info" style="text-decoration: none; color: inherit;">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Total Students</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php
                                                    $sql = "SELECT * FROM students";
                                                    $query = $conn->query($sql);

                                                    echo $query->num_rows;
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-graduation-cap fa-4x text-gray-300 scale-icon info"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <a href="faculty.php" title="ⓘ More info" style="text-decoration: none; color: inherit;">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Total Faculty & Staff</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php
                                                    $sql = "SELECT * FROM faculty";
                                                    $query = $conn->query($sql);

                                                    echo $query->num_rows;
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-4x text-gray-300 scale-icon info"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-4 col-md-6 mb-4">
                        <a href="borrow.php" title="ⓘ More info" style="text-decoration: none; color: inherit;">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Borrows Today</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php
                                                $sql = "SELECT * FROM borrow WHERE date_borrow = '$today'";
                                                $query = $conn->query($sql);

                                                echo "<h3 class='h5 mb-0 font-weight-bold text-gray-800'>".$query->num_rows."</h3>";
                                            ?>

                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-share fa-4x text-gray-300 scale-icon danger"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-4 col-md-6 mb-4">
                        <a href="return.php" title="ⓘ More info" style="text-decoration: none; color: inherit;">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Returns Today</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php
                                                $sql = "SELECT * FROM returns WHERE date_return = '$today'";
                                                $query = $conn->query($sql);

                                                echo "<h3 class='h5 mb-0 font-weight-bold text-gray-800'>".$query->num_rows."</h3>";
                                            ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-reply fa-4x text-gray-300 scale-icon success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-4 col-md-6 mb-4">
                        <a href="fines.php" title="ⓘ More info" style="text-decoration: none; color: inherit;">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                Overdue</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php
                                            $today = date("Y-m-d"); // assuming you want to get the current date
                                            $sql = "SELECT COUNT(*) as total_overdue 
                                                    FROM fines 
                                                    WHERE overdue_days > 4 AND status = 'unpaid' AND date_borrow <= '$today'";
                                            $query = $conn->query($sql);
                                            $result = $query->fetch_assoc();
                                            echo "<h3 class='h5 mb-0 font-weight-bold text-gray-800'>".$result['total_overdue']."</h3>";
                                        ?>

                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-exclamation-circle fa-4x text-gray-300 scale-icon danger"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>


                    <!-- Content Row -->

                    <!-- Monthly Transactions -->
                    <div class="col-xl-8 col-md-12">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Monthly Transactions</h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    </a>           
                                </div>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <!-- Canvas for Bar Chart -->
                                <div class="chart-bar pb-3">
                                    <div class="col pb-2 d-flex align-items-center justify-content-end">
                                        <label for="select_year" class="mr-2 mb-0" style="line-height: 2.5;">Filter:</label>
                                        <select id="select_year" class="form-control" style="width: auto;">
                                            <?php for($i = 2020; $i <= 2080; $i++): ?>
                                                <option value="<?php echo $i; ?>" <?php if($i == $year) echo 'selected'; ?>><?php echo $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>

                                    <canvas id="barChart"></canvas>
                                </div>
                                <!-- Chart Legend -->
                                <div id="legend" class="mt-4 text-center small"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Borrower Statistics -->
                    <div class="col-xl-4 ">
                        <div class="card shadow mb-4" style="height: 437px;">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Borrower Statistics</h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                    </div>
                                </div>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <p class="text-center">
                                    <?php
                                    // Get the current month in a readable format
                                    echo "Month of: <b>".date('F'). "</b>";
                                    ?>
                                </p>
                                <div class="chart-pie pb-2">
                                    <canvas id="pieChart" width="400" height="400"></canvas>
                                </div>
                                <div class="mt-4 text-center small">
                                    <!-- Legend will be generated dynamically with JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Book Circulation Card -->
                    <div class="col-xl-12 mb-4">
                        <div class="card shadow h-100">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Recent Book Circulation</h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                    </div>
                                </div>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body d-flex flex-column p-3">
                                <div class="table-responsive flex-grow-1">
                                    <table id="recentCirculationTable" class="table table-striped table-bordered" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="300">Title</th>
                                                <th width="150">Date Borrowed</th>
                                                <th width="150">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        // Fetch book circulations for today only
                                        $sql = "SELECT b.title, b.accession, br.date_borrow, br.status
                                                FROM books b
                                                LEFT JOIN borrow br ON br.book_id = b.id 
                                                WHERE br.date_borrow = CURDATE()
                                                ORDER BY br.date_borrow DESC";

                                        $query = $conn->query($sql);

                                        function getStatusInfo($status) {
                                            if ($status == 1) {
                                                return ['status' => 'borrowed', 'class' => 'badge-danger'];
                                            } else {
                                                return ['status' => 'returned', 'class' => 'badge-success'];
                                            }
                                        }

                                        if ($query->num_rows > 0) {
                                            while ($row = $query->fetch_assoc()) {
                                                $formatted_date_borrow = date('M j, Y', strtotime($row['date_borrow']));
                                                $statusInfo = getStatusInfo($row['status']);

                                                echo "<tr>
                                                        <td>
                                                            <div class='font-weight-bold'>{$row['title']}</div>
                                                            <div class='text-muted' style='font-size: 0.8em;'>Accession No.: {$row['accession']}</div>
                                                        </td>
                                                        <td>{$formatted_date_borrow}</td>
                                                        <td><span class='badge {$statusInfo['class']}' style='font-size: 0.9em;'>{$statusInfo['status']}</span></td>
                                                    </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='3' class='text-center'>No recent book circulations.</td></tr>";
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Overdue Book Loans Card -->
                    <div class="col-xl-12 mb-4 hidden">
                        <div class="card shadow h-100">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Overdue Book Loans</h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                    </div>
                                </div>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body d-flex flex-column">
                                <table id="overdueBooksTable" class="table table-striped table-bordered" style="width: 100%;">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Full Name</th>
                                            <th width="140px">Borrower Type</th>
                                            <th>Accession No.</th>
                                            <th>Title</th>
                                            <th>Date Borrowed</th>
                                            <th>Days Overdue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    include 'includes/conn.php';

                                    // Fetch overdue books with student and faculty details
                                    $sql = "SELECT DISTINCT 
                                        CONCAT(s.firstname, ' ', s.middlename, ' ', s.lastname) AS student_fullname, 
                                        CONCAT(fa.firstname, ' ', fa.middlename, ' ', fa.lastname) AS faculty_fullname,
                                        b.accession, 
                                        b.title, 
                                        f.overdue_days, 
                                        br.date_borrow
                                    FROM fines f
                                    LEFT JOIN students s ON f.student_id = s.id  -- Allow fines for both students and faculty by using LEFT JOIN
                                    LEFT JOIN faculty fa ON f.faculty_id = fa.id  -- Allow fines for both students and faculty by using LEFT JOIN
                                    INNER JOIN books b ON f.book_id = b.id
                                    INNER JOIN borrow br ON f.book_id = br.book_id AND (f.student_id = br.student_id OR f.faculty_id = br.faculty_id)
                                    WHERE f.status = 'unpaid'
                                    ORDER BY f.overdue_days DESC;
                                    ";

                                    $query = $conn->query($sql);

                                    if ($query->num_rows > 0) {
                                    while ($row = $query->fetch_assoc()) {
                                    $formatted_date_borrow = date('M j, Y', strtotime($row['date_borrow']));
                                    $typeBadge = !empty($row['student_fullname']) ? "<span class='badge bg-primary text-light'>Student</span>" : "<span class='badge bg-secondary text-light'>Faculty</span>";
                                    echo "<tr>
                                        <td class='text-center'>
                                            " . (!empty($row['student_fullname']) ? $row['student_fullname'] : $row['faculty_fullname']) . "
                                        </td>
                                        <td class='text-center'>{$typeBadge}</td>
                                        <td class='text-center'>{$row['accession']}</td>
                                        <td class='text-center'>{$row['title']}</td>
                                        <td class='text-center'>{$formatted_date_borrow}</td>
                                        <td class='text-center'>{$row['overdue_days']}</td>
                                    </tr>";
                                    }
                                    } else {
                                    echo "<tr><td colspan='5' class='text-center'>No overdue loans found.</td></tr>";
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
            
        </div>
        <!-- End of Content Wrapper -->
        
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/logout_modal.php'; ?>
    <?php include 'includes/scripts.php'; ?>

    <style>
    .scale-icon {
    display: inline-block;
    transition: transform 0.2s ease-in-out, margin 0.2s ease-in-out, color 0.2s ease-in-out;
    position: relative;
    cursor: pointer;
    }

    .scale-icon:hover {
    transform: scale(1.2) translateX(-5px); /* Increased scale factor to 1.2 */
    color: var(--hover-color) !important;
    }

    .scale-icon.primary:hover {
    --hover-color: #4e73df; /* Primary color */
    }

    .scale-icon.info:hover {
    --hover-color: #36b9cc; /* Info color */
    }

    .scale-icon.success:hover {
    --hover-color: #1cc88a; /* Success color */
    }

    .scale-icon.warning:hover {
    --hover-color: #f6c23e; /* Warning color*/
    }

    .scale-icon.danger:hover {
    --hover-color: #e74a3b; /* Danger color */
    }


    .card.border-left-primary:hover,
    .card.border-left-info:hover,
    .card.border-left-success:hover,
    .card.border-left-warning:hover,
    .card.border-left-danger:hover {
    border-left-color: #fff; /* White border */
    }

    .card.border-left-primary:hover:before,
    .card.border-left-info:hover:before,
    .card.border-left-success:hover:before,
    .card.border-left-warning:hover:before,
    .card.border-left-danger:hover:before {
    width: 100%;
    }

    .card.border-left-primary:hover:before {
    background-color: #4e73df; /* Primary color */
    }

    .card.border-left-info:hover:before {
    background-color: #36b9cc; /* Info color */
    }

    .card.border-left-success:hover:before {
    background-color: #1cc88a; /* Success color */
    }

    .card.border-left-warning:hover:before {
    background-color: #f6c23e; /* Warning color */
    }

    .card.border-left-danger:hover:before {
    background-color: #e74a3b; /* Danger color */
    }

    .card:hover .card-body .text-xs,
    .card:hover .scale-icon {
    color: #fff !important; /* White text and icon */
    }

    .card:hover .card-body .h5 {
    color: #fff !important;
    }

    .card {
    position: relative; /* Add this to create a containing block for the pseudo-element */
    }

    .card:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 0;
    height: 100%;
    background-color: transparent;
    transition: width 0.2s ease-in-out;
    }

    .card.border-left-primary:hover,
    .card.border-left-info:hover,
    .card.border-left-success:hover,
    .card.border-left-warning:hover,
    .card.border-left-danger:hover {
    transition: background-color 0.2s ease-in-out 0.2s;
    }

    .card.border-left-primary:hover {
    background-color: #4e73df; /* Primary color background */
    }

    .card.border-left-info:hover {
    background-color: #36b9cc; /* Info color background */
    }

    .card.border-left-success:hover {
    background-color: #1cc88a; /* Success color background */
    }

    .card.border-left-success:hover {
    background-color: #f6c23e; /* Warning color background */
    }

    .card.border-left-danger:hover {
    background-color: #e74a3b; /* Danger color background */
    }

    .hidden {
    display: none;
    }
    </style>

    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Include jQuery and Chart.js -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    $(function() {
        function updateChart(year) {
            $.ajax({
                url: 'bar_report.php', // Your PHP backend to fetch data
                type: 'GET',
                data: { year: year },
                dataType: 'json',
                success: function(response) {
                    // Bar chart data
                    var barChartData = {
                        labels  : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [
                            {
                                label               : 'Borrow',
                                backgroundColor     : 'rgba(128, 128, 128, 1)', // Gray color
                                borderColor         : 'rgba(128, 128, 128, 1)', // Gray color
                                data                : response.borrows  // Borrow data from the backend
                            },
                            {
                                label               : 'Return',
                                backgroundColor     : '#4e73df', // Color for returns
                                borderColor         : '#4e73df', // Color for returns
                                data                : response.returns  // Return data from the backend
                            },
                            {
                                label               : 'Overdue',
                                backgroundColor     : '#e74a3b', // Red color for overdue fines
                                borderColor         : '#e74a3b', // Red color for overdue fines
                                data                : response.overdues  // Overdue data from the backend
                            }
                        ]
                    };

                    // Bar chart options
                    var barChartOptions = {
                        responsive: true,
                        maintainAspectRatio: false, // Allow the chart to resize
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1 // Set y-axis to whole numbers
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        }
                    };

                    // Get context for the canvas
                    var barChartCanvas = $('#barChart').get(0).getContext('2d');

                    // Initialize or update the bar chart
                    if (window.myBarChart) {
                        window.myBarChart.destroy(); // Destroy the previous chart instance
                    }
                    window.myBarChart = new Chart(barChartCanvas, {
                        type: 'bar',
                        data: barChartData,
                        options: barChartOptions
                    });

                    // Update the chart's legend
                    document.getElementById('legend').innerHTML = window.myBarChart.generateLegend();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error: " + textStatus + ": " + errorThrown);
                }
            });
        }

        // Initially load the chart for the current year
        var currentYear = new Date().getFullYear();
        $('#select_year').val(currentYear);
        updateChart(currentYear);

        // Year change event to fetch and update data
        $('#select_year').change(function() {
            var selectedYear = $(this).val();
            updateChart(selectedYear); // Update the chart for the selected year
        });
    });
</script>



    <style>
        #barChart {
            height: 50vh; /* Adjust height to fit half the vertical space of the row */
        }
    </style>


<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('pie_report.php')
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data) && data.length > 0) {
                // Separate data into student and faculty categories
                const studentData = data.filter(item => item.type === 'student');
                const facultyData = data.filter(item => item.type === 'faculty');

                // Map labels and values for students and faculty
                const studentLabels = studentData.map(item => item.program_code);
                const studentValues = studentData.map(item => item.borrower_count);
                
                const facultyLabels = facultyData.map(item => item.faculty_position);
                const facultyValues = facultyData.map(item => item.borrower_count);

                // Get canvas context for chart
                const ctx = document.getElementById('pieChart').getContext('2d');
                
                // Create the chart
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: [...studentLabels, ...facultyLabels],  // Combine student and faculty labels
                        datasets: [{
                            label: 'Number of Borrowers',
                            data: [...studentValues, ...facultyValues],  // Combine student and faculty values
                            backgroundColor: [
                                '#0074D9', '#39CCCC', '#3D9970', 
                                '#7FDBFF', '#001f3f', '#2ECC40', 
                                '#6D9EEB', '#5CB85C', '#17A2B8', 
                                '#4D8FAC', '#1E4B73', '#3CB371'
                            ],
                            hoverBackgroundColor: [
                                '#00509E', '#2AA198', '#337D5C', 
                                '#6EC1E4', '#001A33', '#26A65B', 
                                '#5A8AC0', '#489E48', '#148F9E', 
                                '#3B7C8E', '#163C55', '#339966'
                            ],
                            hoverBorderColor: "rgba(234, 236, 244, 1)"
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        }
                    }
                });
            } else {
                console.error('No data available for the pie chart.');
            }
        })
        .catch(error => console.error('Error fetching data:', error));
});
</script>




    
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>


</body>

</html>