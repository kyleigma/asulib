<?php include 'includes/session.php'; ?>
<?php include 'includes/conn.php'; ?>
<?php include 'includes/header.php'; ?>

<?php
// Redirect if no user is logged in
if (!isset($_SESSION['student']) && !isset($_SESSION['faculty'])) {
    header('location: index.php');
    exit();
}

$where = '';
if (isset($_GET['category'])) {
    $catid = $_GET['category'];
    if ($catid > 0) {
        $where = 'WHERE category_id = ' . $catid; // Only filter if category is not "ALL"
    }
}
?>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <div style="padding-top: 105px;"></div>
        <div>
            <div>
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-8 col-md-10 col-sm-12">
                                <div class="card shadow mb-4">
                                    <div class="card-body">
                                        <h4 class="card-title">Browse</h4>

                                        <div class="form-group d-flex align-items-center" style="max-width: 320px;">
                                            <span class="mr-2 mb-0" style="line-height: 2.5;">Category:</span>
                                            <select class="form-control rounded" id="catlist" onchange="filterCategory()">
                                                <option value="0">ALL</option>
                                                <?php
                                                $sql = "SELECT * FROM category";
                                                $query = $conn->query($sql);
                                                while ($catrow = $query->fetch_assoc()) {
                                                    $selected = ($catid == $catrow['id']) ? " selected" : "";
                                                    echo "
                                                        <option value='" . $catrow['id'] . "' " . $selected . ">" . htmlspecialchars($catrow['name']) . "</option>
                                                    ";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <!-- Column-specific Searchbars above the table with Flexbox layout -->
                                        <div class="d-flex mb-3">
                                            <div class="flex-fill pr-2">
                                                <input type="text" id="searchAccNo" class="form-control" placeholder="Search Accession No.">
                                            </div>
                                            <div class="flex-fill pr-2">
                                                <input type="text" id="searchTitle" class="form-control" placeholder="Search Title">
                                            </div>
                                            <div class="flex-fill">
                                                <input type="text" id="searchAuthor" class="form-control" placeholder="Search Author">
                                            </div>
                                        </div>

                                        <!-- DataTable -->
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="dataTableBooks" width="100%" cellspacing="0">
                                                <thead class="text-center">
                                                    <tr>
                                                        <th>Acc. No.</th>
                                                        <th>Title</th>
                                                        <th>Author</th>
                                                        <th>Status</th>
                                                        <th>Reserve</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-center">
                                                    <?php
                                                        // Add your query and book fetching logic here
                                                        $qry = mysqli_query($conn, "SELECT * FROM books $where ORDER BY books.category_no ASC");
                                                        while ($qry2 = mysqli_fetch_array($qry)) {
                                                            $status = ($qry2['status'] == 1) ? '<span class="badge badge-danger">borrowed</span>' : 
                                                                      ($qry2['status'] == 0 ? '<span class="badge badge-success">available</span>' : 
                                                                      ($qry2['status'] == 2 ? '<span class="badge badge-warning">reserved</span>' : 
                                                                      '<span class="badge badge-dark">unavailable</span>'));
                                                            $button = ($qry2['status'] == 1 || $qry2['status'] == 2) ? 
                                                                      '<button class="btn btn-secondary btn-sm" disabled>Requested</button>' : 
                                                                      '<button class="btn btn-primary btn-sm">Request</button>';
                                                    ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($qry2['accession']); ?></td>
                                                        <td><?php echo htmlspecialchars($qry2['title']); ?></td>
                                                        <td><?php echo htmlspecialchars($qry2['author']); ?></td>
                                                        <td><?php echo $status; ?></td>
                                                        <td><?php echo $button; ?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- End of Main Content -->
            </div>
        </div>

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <?php include 'includes/footer.php'; ?>
        <?php include 'includes/request_modal.php'; ?>
        <?php include 'includes/logout_modal.php'; ?>
        <?php include 'includes/scripts.php'; ?>
        <script src="vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

        <script>
            $(document).ready(function() {
                var table = $('#dataTableBooks').DataTable({
                    dom: 'lBfrtip',
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                    searching: true,
                    ordering: true,
                    paging: true,
                    info: true,
                });

                // Column-specific search functionality
                $('#searchAccNo').on('keyup change', function() {
                    var searchTerm = this.value;
                    table.column(0).search(searchTerm).draw();
                });

                $('#searchTitle').on('keyup change', function() {
                    var searchTerm = this.value;
                    table.column(1).search(searchTerm).draw();
                });

                $('#searchAuthor').on('keyup change', function() {
                    var searchTerm = this.value;
                    table.column(2).search(searchTerm).draw();
                });

                // "Request" button click event
                $('#dataTableBooks').on('click', '.btn-primary', function(e) {
                    e.preventDefault(); // Prevent the default action

                    var row = $(this).closest('tr');
                    var accession = row.find('td').eq(0).text(); // Get the Accession No.

                    // Set the Accession No. in the modal
                    $('#requestAccession').text(accession);

                    // Open the confirmation modal
                    $('#requestModal').modal('show');

                    // On confirmation, redirect to process_request.php with the accession as a query parameter
                    $('#confirmRequest').attr('href', 'process_request.php?accession=' + accession);
                });
            });
        </script>

        <style>
            #dataTableBooks_filter {
                display: none;
            }
        </style>
</body>

</html>
