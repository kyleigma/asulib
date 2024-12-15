<?php
include 'includes/session.php';
include 'includes/conn.php';
include 'includes/header.php';

// Retrieve fine ID from URL
if (isset($_GET['fine_id'])) {
    $fine_id = $_GET['fine_id'];
    
    // Fetch fine details
    $sql = "SELECT fd.id AS fine_id, 
                f.firstname AS faculty_firstname, f.middlename AS faculty_middlename, f.lastname AS faculty_lastname, 
                s.firstname AS student_firstname, s.middlename AS student_middlename, s.lastname AS student_lastname, 
                f.faculty_id, s.student_id, 
                f.position, b.accession, b.title, fd.date_borrow, fd.overdue_days, fd.fine_amount, fd.status, 
                fd.date_paid, p.code AS program_code
            FROM fines fd
            LEFT JOIN faculty f ON fd.faculty_id = f.id
            LEFT JOIN students s ON fd.student_id = s.id
            LEFT JOIN books b ON fd.book_id = b.id
            LEFT JOIN program p ON s.program_id = p.id
            WHERE fd.id = '$fine_id'";

    $result = mysqli_query($conn, $sql);
    
    if ($row = mysqli_fetch_array($result)) {
        // Define the position mappings
        $position_mapping = [
            0 => 'Faculty',
            1 => 'COS Faculty',
            2 => 'External Faculty',
            3 => 'Non-Teaching Staff'
        ];
        
        // Prepare data
        $borrower_type = $row['faculty_id'] ? 'Faculty' : 'Student';
        $borrower_name = $row['faculty_id'] 
                        ? $row['faculty_firstname'] . ' ' . $row['faculty_middlename'] . ' ' . $row['faculty_lastname'] 
                        : $row['student_firstname'] . ' ' . $row['student_middlename'] . ' ' . $row['student_lastname'];
        $borrower_id = $row['faculty_id'] ? $row['faculty_id'] : $row['student_id'];
        
        // Determine the borrower program or faculty position
        $borrower_program = $row['faculty_id'] 
            ? (isset($position_mapping[$row['position']]) ? $position_mapping[$row['position']] : 'Unknown Faculty Position') 
            : $row['program_code'];

        $formatted_date_borrow = date('M j, Y', strtotime($row['date_borrow']));
        $formatted_date_paid = ($row['date_paid'] != null) ? date('M j, Y', strtotime($row['date_paid'])) : 'Not Paid';
        $status_badge = ($row['status'] == 'unpaid') 
                        ? '<span class="badge badge-danger">Unpaid</span>' 
                        : '<span class="badge badge-success">Paid</span>';
    } else {
        echo "Fine not found!";
        exit();
    }
} else {
    echo "Invalid request!";
    exit();
}

?>

<body id="page-top">
    <div id="wrapper">
    <?php include 'includes/frame.php';?>
        <div class="container-fluid">
            <div class="col-md-8 offset-md-2">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Invoice</h6>
                    </div>
                    <div class="card-body">
                        <h5 class="mb-4 text-center font-weight-bold">Fine Invoice</h5>
                        <div class="mb-3">
                            <strong>Full Name:</strong> <?php echo $borrower_name; ?><br>
                            <strong>
                                <?php echo $row['student_id'] ? 'Student ID:' : 'Faculty ID:'; ?>
                            </strong> 
                            <?php echo $borrower_id; ?><br>
                            <strong>Borrower Type:</strong> <?php echo $borrower_type; ?><br>
                            <strong>
                                <?php echo $row['faculty_id'] ? 'Position:' : 'Program:'; ?>
                            </strong> 
                            <?php echo $borrower_program; ?><br>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <strong>Accession No:</strong> <?php echo $row['accession']; ?><br>
                            <strong>Title:</strong> <?php echo $row['title']; ?><br>
                            <strong>Date Borrowed:</strong> <?php echo $formatted_date_borrow; ?><br>
                            <strong>Overdue:</strong> <?php echo $row['overdue_days']; ?> days<br>
                            <strong>Total Fine:</strong> ₱<?php echo $row['fine_amount']; ?><br>
                            <strong>Status:</strong> <?php echo $status_badge; ?><br>
                            <strong>Date Paid:</strong> <?php echo $formatted_date_paid; ?><br>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <!-- Back to Fines Button on the Left -->
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='fines.php'">Back</button>
                            
                            <!-- Mark as Paid Button on the Right (if unpaid) -->
                            <?php if ($row['status'] == 'unpaid'): ?>
                                <button type='button' class='btn btn-sm btn-success' data-toggle='modal' data-target='#confirmPaymentModal'
                                        onclick="setPaymentData('<?php echo $row['fine_id']; ?>', '<?php echo $borrower_id; ?>', '<?php echo $row['fine_amount']; ?>')">
                                    <span class='fas fa-check'></span> Mark as Paid
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/scripts.php'; ?>
    <?php include 'includes/confirm_payment_modal.php'; ?>
    <?php include 'includes/logout_modal.php'; ?>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
</html>
