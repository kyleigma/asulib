<?php include 'includes/session.php'; ?>
<?php include 'includes/conn.php'; ?>
<?php include 'includes/header.php'; ?>

<?php
// Redirect if no user is logged in
if (!isset($_SESSION['student']) && !isset($_SESSION['faculty'])) {
    header('location: index.php');
    exit();
}

// Initialize variables
$user_id = null;
$is_student = false;

// Check if the user is a student
if (isset($_SESSION['student'])) {
    $user_id = $_SESSION['student']; // Get the student ID from session
    $is_student = true;
} elseif (isset($_SESSION['faculty'])) {
    $faculty_id = $_SESSION['faculty']; // Get the faculty ID from session
    $faculty_query = "SELECT id FROM faculty WHERE faculty_id = '$faculty_id'";
    $faculty_result = $conn->query($faculty_query);
    if ($faculty_result->num_rows > 0) {
        $faculty_row = $faculty_result->fetch_assoc();
        $user_id = $faculty_row['id'];
    }
    $is_student = false;
}

// Fetch fines to check if they exist for this user
$fine_query = "
    SELECT 
        COALESCE(fines.overdue_days, '') AS overdue_days, 
        COALESCE(fines.fine_amount, '') AS fine_amount, 
        COALESCE(fines.status, '') AS fine_status, 
        COALESCE(fines.date_paid, '') AS date_paid, 
        borrow.date_borrow, 
        borrow.due_date, 
        books.accession, 
        books.title, 
        books.author 
    FROM fines 
    INNER JOIN borrow ON borrow.id = fines.borrow_id 
    INNER JOIN books ON books.id = borrow.book_id
    INNER JOIN " . ($is_student ? "students" : "faculty") . " 
    ON " . ($is_student ? "students.id" : "faculty.id") . " = fines." . ($is_student ? "student_id" : "faculty_id") . "
    WHERE fines." . ($is_student ? "student_id" : "faculty_id") . " = '$user_id'
";
$fines_result = $conn->query($fine_query);
$has_fines = $fines_result->num_rows > 0;

// Fetch transactions based on the filter
$selected_filter = isset($_POST['filter']) ? $_POST['filter'] : 'borrow';
$query = ($selected_filter === 'return')
    ? "SELECT 
         COALESCE(returns.date_return, '') AS date, 
         COALESCE(books.accession, '') AS accession, 
         COALESCE(books.title, '') AS title, 
         COALESCE(books.author, '') AS author 
       FROM returns 
       INNER JOIN books ON books.id = returns.book_id 
       INNER JOIN " . ($is_student ? "students" : "faculty") . " 
       ON " . ($is_student ? "students.id" : "faculty.id") . " = returns." . ($is_student ? "student_id" : "faculty_id") . "
       WHERE returns." . ($is_student ? "student_id" : "faculty_id") . " = '$user_id'"
    : "SELECT 
         COALESCE(borrow.date_borrow, '') AS date, 
         COALESCE(borrow.due_date, '') AS due_date, 
         COALESCE(borrow.status, '') AS status, 
         COALESCE(books.accession, '') AS accession, 
         COALESCE(books.title, '') AS title, 
         COALESCE(books.author, '') AS author 
       FROM borrow 
       INNER JOIN books ON books.id = borrow.book_id 
       INNER JOIN " . ($is_student ? "students" : "faculty") . " 
       ON " . ($is_student ? "students.id" : "faculty.id") . " = borrow." . ($is_student ? "student_id" : "faculty_id") . "
       WHERE borrow." . ($is_student ? "student_id" : "faculty_id") . " = '$user_id'";

$result_query = $conn->query($query);
?>

<body id="page-top">
    <div id="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <div style="padding-top: 105px;"></div>

        <div class="container-fluid">
            <?php
            if (isset($_SESSION['error'])) {
                echo "<div class='alert alert-danger alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h5><i class='icon fa fa-warning'></i> Error!</h5>
                        " . $_SESSION['error'] . "
                    </div>";
                unset($_SESSION['error']);
            }
            ?>

            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10 col-sm-12">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                <h4 class="card-title mb-0">Transactions</h4>
                                <form method="post" class="ml-3">
                                    <div class="form-group mb-0 d-flex align-items-center">
                                        <label for="filter" class="mr-2 mb-0">Filter:</label>
                                        <select name="filter" id="filter" class="form-control" onchange="this.form.submit()">
                                            <option value="borrow" <?php if ($selected_filter == 'borrow') echo 'selected'; ?>>Borrow</option>
                                            <option value="return" <?php if ($selected_filter == 'return') echo 'selected'; ?>>Return</option>
                                        </select>
                                    </div>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Date</th>
                                            <?php if ($selected_filter === 'borrow'): ?>
                                                <th>Due Date</th>
                                                <th>Accession Number</th>
                                                <th>Title</th>
                                                <th>Author</th>
                                                <th>Status</th>
                                                <?php if ($has_fines): ?>
                                                    <th>Overdue Days</th>
                                                    <th>Fine Amount</th>
                                                    <th>Fine Status</th>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <th>Accession Number</th>
                                                <th>Title</th>
                                                <th>Author</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <?php
                                        if ($selected_filter === 'borrow') {
                                            while ($row = $result_query->fetch_assoc()) {
                                                echo "<tr class='text-center'>
                                                    <td>" . (!empty($row['date']) ? date('M d, Y', strtotime($row['date'])) : "") . "</td>
                                                    <td>" . (!empty($row['due_date']) ? date('M d, Y', strtotime($row['due_date'])) : "") . "</td>
                                                    <td>" . (!empty($row['accession']) ? $row['accession'] : "") . "</td>
                                                    <td style='text-align: justify; text-justify: inter-word;'>" . (!empty($row['title']) ? $row['title'] : "") . "</td>
                                                    <td>" . (!empty($row['author']) ? $row['author'] : "") . "</td>
                                                    <td>" . ($row['status'] == 1 ? 
                                                        "<span class='badge badge-danger'>Borrowed</span>" : 
                                                        "<span class='badge badge-success'>Returned</span>") . "</td>";

                                                if ($has_fines) {
                                                    $fine_row = $fines_result->fetch_assoc();
                                                    echo "<td>" . (!empty($fine_row['overdue_days']) ? $fine_row['overdue_days'] : "") . "</td>
                                                        <td>" . (!empty($fine_row['fine_amount']) ? "Php " . number_format($fine_row['fine_amount'], 2) : "") . "</td>
                                                        <td>" . (!empty($fine_row['fine_status']) ? ($fine_row['fine_status'] === 'paid' ? 
                                                            "<span class='badge badge-success'>Paid</span>" : 
                                                            "<span class='badge badge-danger'>Unpaid</span>") : "") . "</td>";
                                                }

                                                echo "</tr>";
                                            }
                                        } else {
                                            while ($row = $result_query->fetch_assoc()) {
                                                echo "<tr class='text-center'>
                                                    <td>" . (!empty($row['date']) ? date('M d, Y', strtotime($row['date'])) : "") . "</td>
                                                    <td>" . (!empty($row['accession']) ? $row['accession'] : "") . "</td>
                                                    <td style='text-align: justify; text-justify: inter-word;'>" . (!empty($row['title']) ? $row['title'] : "") . "</td>
                                                    <td>" . (!empty($row['author']) ? $row['author'] : "") . "</td>
                                                </tr>";
                                            }
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
        <?php include 'includes/scripts.php'; ?>
    </div>
</body>
</html>
