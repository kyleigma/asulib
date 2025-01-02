<?php include 'includes/session.php'; ?>
<?php include 'includes/conn.php'; ?>
<?php include 'includes/header.php'; ?>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include 'includes/frame.php'; ?>

        <div>
            <div>
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Borrow Requests</h1>
                        <nav style="--bs-breadcrumb-divider: '>';font-size:85%;" aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class=""><a href="home.php">Dashboard</a></li>&nbsp;&nbsp;&nbsp;
                                <li class=""><i class="fas fa-angle-right"></i></li>&nbsp;&nbsp;&nbsp;
                                <li class="active" aria-current="page">Borrow Requests</a></li>&nbsp;&nbsp;&nbsp;
                            </ol>
                        </nav>
                    </div>

                    <?php
                        // Automatically decline requests older than 1 day and set decision_date
                        $auto_decline_sql = "
                            UPDATE requests 
                            SET status = 'declined', decision_date = NOW() 
                            WHERE status = 'pending' 
                            AND request_date < NOW() - INTERVAL 1 DAY";

                        if ($conn->query($auto_decline_sql)) {
                            $_SESSION['success'] = 'Old requests have been automatically declined.';
                        } else {
                            $_SESSION['error'] = 'Failed to auto-decline old requests: ' . $conn->error;
                        }

                        if(isset($_SESSION['error'])){
                            echo "
                                <div class='alert alert-danger alert-dismissible'>
                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                <h5><i class='icon fa fa-exclamation-triangle'></i> Error!</h5>
                                ".$_SESSION['error']."</div>";
                            unset($_SESSION['error']);
                        }
                        if(isset($_SESSION['success'])){
                            echo "
                                <div class='alert alert-success alert-dismissible'>
                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                <h5><i class='icon fa fa-check'></i> Success!</h5>
                                ".$_SESSION['success']."</div>";
                            unset($_SESSION['success']);
                        }

                        // Get the filter value
                        $filter = isset($_GET['position_filter']) ? $_GET['position_filter'] : 'all';

                        // Adjust the query based on the filter
                        if ($filter == 'faculty') {
                            // Query for faculty only
                            $sql = "SELECT r.id AS req_id, r.request_date, r.decision_date, r.status, r.book_id, f.faculty_id, f.firstname, f.lastname, b.accession, b.title
                                    FROM requests r
                                    LEFT JOIN books b ON r.book_id = b.id
                                    LEFT JOIN faculty f ON r.faculty_id = f.id
                                    WHERE r.faculty_id IS NOT NULL
                                    ORDER BY r.request_date DESC";
                            $id_column = "Faculty ID";
                        } elseif ($filter == 'student') {
                            // Query for students only
                            $sql = "SELECT r.id AS req_id, r.request_date, r.decision_date, r.status, r.book_id, s.student_id, s.firstname, s.lastname, b.accession, b.title
                                    FROM requests r
                                    LEFT JOIN books b ON r.book_id = b.id
                                    LEFT JOIN students s ON r.student_id = s.id
                                    WHERE r.student_id IS NOT NULL
                                    ORDER BY r.request_date DESC";
                            $id_column = "Student ID";
                        } else {
                            // Union query for both faculty and students (when filter is 'all')
                            $sql = "(SELECT r.id AS req_id, r.request_date, r.decision_date, r.status, r.book_id, f.faculty_id AS id, f.firstname, f.lastname, b.accession, b.title
                                        FROM requests r
                                        LEFT JOIN books b ON r.book_id = b.id
                                        LEFT JOIN faculty f ON r.faculty_id = f.id
                                        WHERE r.faculty_id IS NOT NULL)
                                    UNION
                                    (SELECT r.id AS req_id, r.request_date, r.decision_date, r.status, r.book_id, s.student_id AS id, s.firstname, s.lastname, b.accession, b.title
                                        FROM requests r
                                        LEFT JOIN books b ON r.book_id = b.id
                                        LEFT JOIN students s ON r.student_id = s.id
                                        WHERE r.student_id IS NOT NULL)
                                    ORDER BY request_date DESC";
                            $id_column = "ID";
                        }

                        $qry = mysqli_query($conn, $sql);
                    ?>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <!-- Dropdown filter form -->
                            <form method="GET" action="">
                                <div class="form-group mb-0 d-flex align-items-center justify-content-end">
                                    <label for="filter" class="mr-2 mb-0" style="line-height: 2.5;">Filter:</label>
                                    <select class="form-control" name="position_filter" onchange="this.form.submit()" style="width: auto;">
                                        <option value="all" <?php echo (isset($_GET['position_filter']) && $_GET['position_filter'] == 'all') ? 'selected' : ''; ?>>All</option>
                                        <option value="student" <?php echo (isset($_GET['position_filter']) && $_GET['position_filter'] == 'student') ? 'selected' : ''; ?>>Student</option>
                                        <option value="faculty" <?php echo (isset($_GET['position_filter']) && $_GET['position_filter'] == 'faculty') ? 'selected' : ''; ?>>Faculty/Staff</option>
                                    </select>
                                </div>
                            </form>
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Date</th>
                                            <th><?php echo $id_column; ?></th>
                                            <th>Name</th>
                                            <th>Accession No.</th>
                                            <th>Title</th>
                                            <th>Decision Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            while ($row = mysqli_fetch_array($qry)) {
                                                $status = '';
                                                $badge_class = '';
                                                $decision_date = $row['decision_date'] ? date('M d, Y', strtotime($row['decision_date'])) : '-';
                                            
                                                if ($row['status'] == 'pending') {
                                                    $status = 'Pending';
                                                    $badge_class = 'badge-warning';
                                                    $decision_date = ''; // Empty for pending status
                                                } elseif ($row['status'] == 'approved') {
                                                    $status = 'Approved';
                                                    $badge_class = 'badge-success';
                                                } elseif ($row['status'] == 'declined') {
                                                    $status = 'Declined';
                                                    $badge_class = 'badge-danger';
                                                }
                                            
                                                // Check if student_id or faculty_id exists based on the filter
                                                $id = '';
                                                $name = '';
                                                if ($filter == 'student') {
                                                    if (isset($row['student_id'])) {
                                                        $id = $row['student_id'];  // Display student_id
                                                    }
                                                    $name = $row['firstname'] . ' ' . $row['lastname'];
                                                } elseif ($filter == 'faculty') {
                                                    if (isset($row['faculty_id'])) {
                                                        $id = $row['faculty_id'];  // Display faculty_id
                                                    }
                                                    $name = $row['firstname'] . ' ' . $row['lastname'];
                                                } else {
                                                    // For 'all' filter, we expect an 'id' column that can either be student_id or faculty_id
                                                    $id = isset($row['id']) ? $row['id'] : '';  // Use the common 'id' field
                                                    $name = $row['firstname'] . ' ' . $row['lastname'];
                                                }
                                        ?>

                                        <tr>
                                            <td class="text-center"><?php echo date('M d, Y h:i A', strtotime($row['request_date'])); ?></td>
                                            <td class="text-center"><?php echo $id; ?></td>
                                            <td class="text-center"><?php echo $name; ?></td>
                                            <td class="text-center"><?php echo $row['accession']; ?></td>
                                            <td style="text-align: justify; text-justify: inter-word;"><?php echo $row['title']; ?></td>

                                            <!-- Display decision date; show '-' if status is 'pending' -->
                                            <?php if ($row['status'] != 'pending') { ?>
                                                <td class="text-center"><?php echo $decision_date ?: '-'; ?></td>
                                            <?php } else { ?>
                                                <td class="text-center">-</td> <!-- Show '-' for pending status -->
                                            <?php } ?>

                                            <td><span class='badge <?php echo $badge_class; ?>'><?php echo $status; ?></span></td>
                                            <td class='text-center'>
                                                <?php if ($row['status'] !== 'approved' && $row['status'] !== 'declined'): ?>
                                                    <a href="#approve<?php echo $row['req_id']; ?>" data-toggle="modal" class="btn btn-sm btn-success mb-2"><span class="fas fa-check"></span> </a>
                                                    <a href="#decline<?php echo $row['req_id']; ?>" data-toggle="modal" class="btn btn-sm btn-danger mb-2"><span class="fas fa-ban"></span> </a>
                                                <?php else: ?>
                                                    <span class="text-muted"></span> <!-- You can display something else, like "Approved" or leave it blank -->
                                                <?php endif; ?>
                                            </td>
                                        </tr>

                                        <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

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
        <?php include 'includes/requests_modal.php'; ?>
        <?php include 'includes/scripts.php'; ?>

        <script src="vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

        <script>
        $('#dataTable').DataTable({
            "responsive": true,
            "order": [[0, 'desc']],  // Sort by date in descending order by default
        });
        </script>
    </body>
</html>
