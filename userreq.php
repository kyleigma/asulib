<?php include 'includes/session.php'; ?>
<?php include 'includes/conn.php'; ?>
<?php include 'includes/header.php'; ?>

<?php
// Redirect if no user is logged in
if (!isset($_SESSION['student']) && !isset($_SESSION['faculty'])) {
    header('location: index.php');
}

// Determine if the user is a student or faculty, and get the respective ID
$user_id = null;
$is_student = false;
$faculty_id = null;

if (isset($_SESSION['student'])) {
    $user_id = $_SESSION['student'];
    $is_student = true;
} elseif (isset($_SESSION['faculty'])) {
    $user_id = $_SESSION['faculty'];
    $faculty_sql = "SELECT id FROM faculty WHERE faculty_id = '$user_id'";
    $faculty_result = $conn->query($faculty_sql);
    
    if ($faculty_result->num_rows > 0) {
        $faculty_row = $faculty_result->fetch_assoc();
        $faculty_id = $faculty_row['id'];
    } else {
        $_SESSION['error'] = 'Invalid faculty ID.';
        header('Location: catalog.php');
        exit();
    }
}

// Fetch Requests Data
$request_sql = "SELECT r.request_date AS date, b.accession, b.title, 
    r.status, r.decision_date
FROM requests r
LEFT JOIN books b ON r.book_id = b.id
LEFT JOIN students s ON r.student_id = s.id
LEFT JOIN faculty f ON r.faculty_id = f.id
WHERE ";

// Filter for student or faculty
if ($is_student) {
    $request_sql .= "r.student_id = '$user_id'";
} else {
    $request_sql .= "r.faculty_id = '$faculty_id'";
}

$result_query = $conn->query($request_sql);
?>

<body id="page-top">
    <div id="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <div style="padding-top: 105px;"></div>
        
        <div>
            <!-- Begin Page Content -->
            <div class="container-fluid">
                <?php
                if (isset($_SESSION['error'])) {
                    echo "
                        <div class='alert alert-danger alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h5><i class='icon fa fa-warning'></i> Error!</h5>
                        " . $_SESSION['error'] . "
                        </div>
                    ";
                    unset($_SESSION['error']);
                }
                ?>
                
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10 col-sm-12">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                    <h4 class="card-title mb-0">Book Requests</h4>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Request Date</th>
                                                <th>Accession Number</th>
                                                <th>Title</th>
                                                <th>Decision Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php
                                                while ($row = $result_query->fetch_assoc()) {
                                                    $status = ucfirst($row['status']);
                                                    $badge_class = '';
                                                    $decision_date = '-';

                                                    // Determine badge class and decision date
                                                    if ($row['status'] == 'approved') {
                                                        $badge_class = 'badge-success';
                                                        $decision_date = date('M d, Y', strtotime($row['decision_date']));
                                                    } elseif ($row['status'] == 'declined') {
                                                        $badge_class = 'badge-danger';
                                                        $decision_date = date('M d, Y', strtotime($row['decision_date']));
                                                    } elseif ($row['status'] == 'pending') {
                                                        $badge_class = 'badge-warning';
                                                    } else {
                                                        $badge_class = 'badge-secondary';
                                                    }

                                                    echo "<tr>";
                                                    echo "<td>" . date('M d, Y h:i A', strtotime($row['date'])) . "</td>";
                                                    echo "<td>" . $row['accession'] . "</td>";
                                                    echo "<td style='text-align: justify; text-justify: inter-word;'>" . $row['title'] . "</td>";
                                                    echo "<td>" . $decision_date . "</td>";
                                                    echo "<td><span class='badge $badge_class'>$status</span></td>";
                                                    echo "</tr>";
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
            <!-- End of Main Content -->
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/scripts.php'; ?>
</body>
</html>
