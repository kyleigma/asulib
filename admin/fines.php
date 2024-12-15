<?php
include 'includes/session.php';
include 'includes/conn.php';
include 'includes/header.php';

// Function to update fines and dues
function updateFines($conn) {
    $today = date('Y-m-d');

    // Fetch records from borrow table where status is '1' (indicating the book is still borrowed)
    $borrow_sql = "SELECT * FROM borrow WHERE status = '1'";
    $borrow_result = mysqli_query($conn, $borrow_sql);

    while ($borrow_row = mysqli_fetch_array($borrow_result)) {
        $borrow_id = $borrow_row['id'];
        $student_id = $borrow_row['student_id'];
        $faculty_id = $borrow_row['faculty_id'];
        $book_id = $borrow_row['book_id'];
        $date_borrow = $borrow_row['date_borrow'];

        $isFaculty = !empty($faculty_id);

        if ($isFaculty) {
            $faculty_position = getFacultyPosition($conn, $faculty_id);
            switch ($faculty_position) {
                case 0: $max_days_circulating = 60; $fine_rate = 10; break;
                case 1: $max_days_circulating = 30; $fine_rate = 10; break;
                case 2: $max_days_circulating = 7;  $fine_rate = 10; break;
                case 3: $max_days_circulating = 3;  $fine_rate = 10; break;
                default: $max_days_circulating = 3; $fine_rate = 10; break;
            }
        } else {
            $max_days_circulating = 3;
            $fine_rate = 5;
        }

        $days_borrowed = (strtotime($today) - strtotime($date_borrow)) / (60 * 60 * 24);
        $due_date = date('Y-m-d', strtotime($date_borrow . " + $max_days_circulating days"));

        if ($days_borrowed > $max_days_circulating) {
            $overdue_days = $days_borrowed - $max_days_circulating;
            $fine_amount = $overdue_days * $fine_rate;

            // Check if an unpaid fine record already exists
            $check_fine_sql = "SELECT * FROM fines 
                WHERE borrow_id = '$borrow_id' 
                AND status = 'unpaid'";
            $check_fine_result = mysqli_query($conn, $check_fine_sql);

            if (mysqli_num_rows($check_fine_result) > 0) {
                $existing_fine = mysqli_fetch_assoc($check_fine_result);
                $last_updated = $existing_fine['last_updated'];

                // Update only if it hasn't been updated today
                if ($last_updated != $today) {
                    $update_fine_sql = "UPDATE fines 
                        SET overdue_days = $overdue_days, fine_amount = $fine_amount, last_updated = '$today' 
                        WHERE id = " . $existing_fine['id'];
                    mysqli_query($conn, $update_fine_sql);
                }
            } else {
                // Insert a new fine record if none exists for this borrower and book
                $insert_fine_sql = "INSERT INTO fines (borrow_id, student_id, faculty_id, book_id, date_borrow, due_date, overdue_days, fine_amount, status, last_updated)
                    VALUES ('$borrow_id', " . ($student_id ? "'$student_id'" : 'NULL') . ", " . ($faculty_id ? "'$faculty_id'" : 'NULL') . ", '$book_id', '$date_borrow', '$due_date', $overdue_days, $fine_amount, 'unpaid', '$today')";
                mysqli_query($conn, $insert_fine_sql);
            }
        }
    }
}

// Helper function to get the faculty position based on the faculty_id
function getFacultyPosition($conn, $faculty_id) {
    $sql = "SELECT position FROM faculty WHERE id = '$faculty_id'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['position']; // Return the faculty position
    }
    return null; // Return null if no matching faculty found
}

// Run the update fines function
updateFines($conn);

// Handle the filter logic
$filter = isset($_GET['position_filter']) ? $_GET['position_filter'] : 'all'; // default is 'all'

?>

<body id="page-top">
    <div id="wrapper">
    <?php include 'includes/frame.php'; ?>

        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Fines & Dues</h1>
                <nav style="--bs-breadcrumb-divider: '>';font-size:85%;" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class=""><a href="home.php">Dashboard</a></li>&nbsp;&nbsp;&nbsp;
                        <li class=""><i class="fas fa-angle-right"></i></li>&nbsp;&nbsp;&nbsp;
                        <li class="active" aria-current="page">Fines & Dues</li>
                    </ol>
                </nav>
            </div>

            <?php
            if (isset($_SESSION['error'])) {
                echo "<div class='alert alert-danger alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h5><i class='icon fa fa-exclamation-triangle'></i> Error!</h5>" . $_SESSION['error'] . "</div>";
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo "<div class='alert alert-success alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h5><i class='icon fa fa-check'></i> Success!</h5>" . $_SESSION['success'] . "</div>";
                unset($_SESSION['success']);
            }
            ?>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <!-- Dropdown filter form -->
                    <form method="GET" action="">
                        <div class="form-group mb-0 d-flex align-items-center justify-content-end">
                            <label for="position_filter" class="mr-2 mb-0" style="line-height: 2.5;">Filter:</label>
                            <select class="form-control" name="position_filter" onchange="this.form.submit()" style="width: auto;">
                                <option value="all" <?php echo ($filter == 'all') ? 'selected' : ''; ?>>All</option>
                                <option value="student" <?php echo ($filter == 'student') ? 'selected' : ''; ?>>Student</option>
                                <option value="faculty" <?php echo ($filter == 'faculty') ? 'selected' : ''; ?>>Faculty/Staff</option>
                            </select>
                        </div>
                    </form>
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="10"><?php echo ($filter == 'all') ? 'ID' : ucfirst($filter) . ' ID'; ?></th>
                                    <th>Full Name</th>
                                    <th class="text-wrap" style="max-width: 70px;">
                                        <?php 
                                            echo ($filter == 'all') 
                                                ? 'Position/Program' 
                                                : (ucfirst($filter) == 'Student' ? 'Program' : 'Position'); 
                                        ?>
                                    </th>
                                    <th width="20">Acc. No.</th>
                                    <th>Title</th>
                                    <th width="20">Date Borrowed</th>
                                    <th>Due Date</th> <!-- New column for Due Date -->
                                    <th width="20">Overdue</th>
                                    <th>Total Fine</th>
                                    <th>Status</th>
                                    <th>Date Paid</th>
                                    <?php if ($role != 1): ?>
                                        <th>Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            // Adjust the query based on the filter
                            if ($filter == 'faculty') {
                                $sql = "SELECT fd.id AS fine_id, f.firstname, f.middlename, f.lastname, f.faculty_id AS id, 
                                            f.position AS position_program, b.accession, b.title, fd.date_borrow, fd.due_date, fd.overdue_days, 
                                            fd.fine_amount, fd.status AS fine_status, fd.date_paid, 'faculty' AS borrower_type
                                        FROM fines fd
                                        JOIN faculty f ON fd.faculty_id = f.id
                                        JOIN books b ON fd.book_id = b.id
                                        WHERE fd.faculty_id IS NOT NULL";
                            } elseif ($filter == 'student') {
                                $sql = "SELECT fd.id AS fine_id, s.firstname, s.middlename, s.lastname, s.student_id AS id, 
                                            p.code AS position_program, b.accession, b.title, fd.date_borrow, fd.due_date, fd.overdue_days, 
                                            fd.fine_amount, fd.status AS fine_status, fd.date_paid, 'student' AS borrower_type
                                        FROM fines fd
                                        JOIN students s ON fd.student_id = s.id
                                        JOIN program p ON s.program_id = p.id
                                        JOIN books b ON fd.book_id = b.id
                                        WHERE fd.student_id IS NOT NULL";
                            } else {
                                // Union query for both faculty and students (when filter is 'all')
                                $sql = "(SELECT fd.id AS fine_id, f.firstname, f.middlename, f.lastname, f.faculty_id AS id, 
                                                f.position AS position_program, b.accession, b.title, fd.date_borrow, fd.due_date, fd.overdue_days, 
                                                fd.fine_amount, fd.status AS fine_status, fd.date_paid, 'faculty' AS borrower_type
                                        FROM fines fd
                                        JOIN faculty f ON fd.faculty_id = f.id
                                        JOIN books b ON fd.book_id = b.id
                                        WHERE fd.faculty_id IS NOT NULL)
                                        UNION
                                        (SELECT fd.id AS fine_id, s.firstname, s.middlename, s.lastname, s.student_id AS id, 
                                                p.code AS position_program, b.accession, b.title, fd.date_borrow, fd.due_date, fd.overdue_days, 
                                                fd.fine_amount, fd.status AS fine_status, fd.date_paid, 'student' AS borrower_type
                                        FROM fines fd
                                        JOIN students s ON fd.student_id = s.id
                                        JOIN program p ON s.program_id = p.id
                                        JOIN books b ON fd.book_id = b.id
                                        WHERE fd.student_id IS NOT NULL)";
                            }

                            $result = mysqli_query($conn, $sql);

                            while ($row = mysqli_fetch_array($result)) {
                                $formatted_date_borrow = date('M j, Y', strtotime($row['date_borrow']));
                                $formatted_due_date = (!is_null($row['due_date'])) ? date('M j, Y', strtotime($row['due_date'])) : 'No Due Date';
                                $formatted_date_paid = ($row['date_paid'] != null) ? date('M j, Y', strtotime($row['date_paid'])) : 'Not Paid';

                                $statusBadge = ($row['fine_status'] == 'unpaid') 
                                    ? '<span class="badge badge-danger">Unpaid</span>' 
                                    : '<span class="badge badge-success">Paid</span>';

                                $disableButton = ($row['fine_status'] == 'paid') ? 'disabled' : '';

                                $idValue = $row['id'];

                                // Display position for faculty or program for students
                                $positionDisplay = ($row['borrower_type'] == 'faculty') 
                                                    ? ($row['position_program'] == 0 ? 'Teaching' : 'Non-teaching') 
                                                    : $row['position_program'];

                                $modalTrigger = ($row['fine_status'] == 'unpaid') 
                                ? "<form method='post' action='mark_as_paid.php' style='display:inline;'>
                                        <input type='hidden' name='fine_id' value='{$row['fine_id']}'>
                                        <input type='hidden' name='{$row['borrower_type']}_id' value='{$idValue}'>
                                        <input type='hidden' name='fine_amount' value='{$row['fine_amount']}'>
                                        <button type='button' class='btn btn-sm btn-success' data-toggle='modal' data-target='#confirmPaymentModal'
                                            onclick=\"setPaymentData('{$row['fine_id']}', '{$idValue}', '{$row['fine_amount']}')\">
                                            <span class='fas fa-check'></span>
                                        </button>
                                    </form>" 
                                : ''; 
                            
                                $viewButton = "<button type='button' class='btn btn-sm btn-info' 
                                data-toggle='modal' data-target='#view' 
                                onclick=\"fetchFineDetails(" . htmlspecialchars($row['fine_id']) . ")\">
                                <span class='fas fa-eye'></span>
                                </button>";              

                                echo "<tr class='text-center'>
                                        <td>{$idValue}</td>
                                        <td>{$row['firstname']} {$row['middlename']} {$row['lastname']}</td>
                                        <td>{$positionDisplay}</td>
                                        <td>{$row['accession']}</td>
                                        <td style='text-align: justify; text-justify: inter-word;'>{$row['title']}</td>
                                        <td>{$formatted_date_borrow}</td>
                                        <td>{$formatted_due_date}</td>
                                        <td>{$row['overdue_days']} Day(s)</td>
                                        <td>₱{$row['fine_amount']}</td>
                                        <td class='text-center'>{$statusBadge}</td>
                                        <td>{$formatted_date_paid}</td>";
                                        
                                // Conditional rendering of the last <td>
                                if ($role != 1) {
                                    echo "<td class='text-center' style='display: flex; justify-content: center; align-items: center;'>{$modalTrigger}&nbsp;{$viewButton}</td>";
                                }
                                        
                                echo "</tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/confirm_payment_modal.php'; ?>
    <?php include 'includes/fine_invoice_modal.php'; ?>
    <?php include 'includes/logout_modal.php'; ?>
    <?php include 'includes/scripts.php'; ?>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script>
    // Function to fetch fine details and populate the modal
    function fetchFineDetails(fine_id) {
        $.ajax({
            url: 'fetch_fine_details.php',
            type: 'GET',
            data: { fine_id: fine_id },
            success: function(response) {
            var data = JSON.parse(response);
            if (data.error) {
                alert(data.error);
            } else {
                // Populate modal fields
                $('#borrower_name').text(data.borrower_name);
                $('#borrower_id').text(data.borrower_id);
                $('#borrower_id_label').text(data.borrower_type === 'Faculty' ? 'Faculty ID:' : 'Student ID:');
                $('#borrower_type').text(data.borrower_type);
                $('#borrower_program').text(data.borrower_program);
                $('#borrower_program_label').text(data.borrower_type === 'Faculty' ? 'Position:' : 'Program:');
                $('#accession').text(data.accession);
                $('#title').text(data.title);
                $('#date_borrowed').text(data.date_borrowed);
                $('#due_date').text(data.due_date);
                $('#fineamount').text(data.fineamount);
                $('#overdue_days').text(data.overdue_days);
                $('#status_badge').html(data.status_badge);
                $('#date_paid').text(data.date_paid);

                // Show or hide Mark as Paid button
                if (data.status === 'unpaid') {
                    $('#markAsPaidButton').show();
                } else {
                    $('#markAsPaidButton').hide();
                }

                // Open the corresponding modal
                $('#view' + fine_id).modal('show');
            }
        },
            error: function(xhr, status, error) {
                console.error('An error occurred while fetching fine details:', error);
            }
        });
    }

    // Event listener to trigger the modal
    $(document).on('click', '.viewFineDetails', function() {
        var fine_id = $(this).data('fine-id');
        fetchFineDetails(fine_id);
    });
</script>

<script>
    $('#dataTable').DataTable({
        "responsive": true,
        "order": [
            [6, 'desc'],
            [9, 'asc']
        ],
        "columnDefs": [
            {
                "targets": 6, // Define specific settings for column 11
                "type": "date"  // Treat column 11 as a date
            }
        ]
    });
</script>


    
</body>
</html>