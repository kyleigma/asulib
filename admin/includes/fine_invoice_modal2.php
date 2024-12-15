<?php
include 'includes/session.php';
include 'includes/conn.php';
include 'includes/header.php';

// Retrieve fine ID from URL
if (isset($_GET['fine_id'])) {
    $fine_id = $_GET['fine_id'];
    
    // Fetch fine details
    $sql = "SELECT fd.id AS fine_id, f.firstname, f.middlename, f.lastname, f.faculty_id, s.student_id, 
                f.position, b.accession, b.title, fd.date_borrow, fd.overdue_days, fd.due_date, fd.fine_amount, fd.status, 
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
        $borrower_name = $row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname'];
        $borrower_id = $row['faculty_id'] ? $row['faculty_id'] : $row['student_id'];
        
        // Determine the borrower program or faculty position
        $borrower_program = $row['faculty_id'] 
            ? (isset($position_mapping[$row['position']]) ? $position_mapping[$row['position']] : 'Unknown Faculty Position') 
            : $row['program_code'];

        $formatted_date_borrow = date('M j, Y', strtotime($row['date_borrow']));
        $formatted_due_date = (!is_null($row['due_date'])) ? date('M j, Y', strtotime($row['due_date'])) : 'No Due Date';
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

<!-- Fine Details Modal -->
<div class="modal fade" id="fineDetailsModal" tabindex="-1" role="dialog" aria-labelledby="fineDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="fineDetailsModalLabel"><b>Fine Invoice</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Fine details will be populated here -->
                <div class="mb-0 mx-3">
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
                <div class="mb-3 mx-3">
                <strong>Accession No:</strong> <?php echo $row['accession']; ?><br>
                    <strong>Title:</strong> <?php echo $row['title']; ?><br>
                    <strong>Date Borrowed:</strong> <?php echo $formatted_date_borrow; ?><br>
                    <strong>Date Borrowed:</strong> <?php echo $formatted_due_date; ?><br>
                    <strong>Overdue:</strong> <?php echo $row['overdue_days']; ?> days<br>
                    <strong>Total Fine:</strong> ₱<?php echo $row['fine_amount']; ?><br>
                    <strong>Status:</strong> <?php echo $status_badge; ?><br>
                    <strong>Date Paid:</strong> <?php echo $formatted_date_paid; ?><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
                </div>
            </div>
        </div>
    </div>
</div>

