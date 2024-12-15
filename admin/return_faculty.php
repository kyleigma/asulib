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
                        <h1 class="h3 mb-0 text-gray-800">Faculty & Staff Return</h1>
                        <nav style="--bs-breadcrumb-divider: '>';font-size:85%;" aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class=""><a href="home.php">Dashboard</a></li>&nbsp;&nbsp;&nbsp;
                                <li class=""><i class="fas fa-angle-right"></i></li>&nbsp;&nbsp;&nbsp;
                                <li class=""><a href="return.php">Return</a></li>&nbsp;&nbsp;&nbsp;
                                <li class=""><i class="fas fa-angle-right"></i></li>&nbsp;&nbsp;&nbsp;
                                <li class="active" aria-current="page">Faculty & Staff Return</li>
                            </ol>
                        </nav>
                    </div>

                    <?php
                        if(isset($_SESSION['error'])){
                        echo "
                            <div class='alert alert-danger alert-dismissible'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                            <h5><i class='icon fa fa-exclamation-triangle'></i> Error!</h5>
                            ".$_SESSION['error']."
                            </div>
                        ";
                        unset($_SESSION['error']);
                        }
                        if(isset($_SESSION['success'])){
                        echo "
                            <div class='alert alert-success alert-dismissible'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                            <h5><i class='icon fa fa-check'></i> Success!</h5>
                            ".$_SESSION['success']."
                            </div>
                        ";
                        unset($_SESSION['success']);
                        }
                    ?>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <a href="#addnew2" data-toggle="modal" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> New</a>
                        </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="100">Date</th>
                                            <th width="150">Faculty/Staff ID</th>
                                            <th width="150">Name</th>
                                            <th width="150">Accession No.</th>
                                            <th>Title</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    $qry = mysqli_query($conn, "SELECT *, faculty.faculty_id AS fac, books.accession AS accession FROM returns LEFT JOIN faculty ON faculty.id=returns.faculty_id LEFT JOIN books ON books.id=returns.book_id WHERE student_id IS NULL OR student_id = '' ORDER BY date_return DESC");

                                    while ($qry2 = mysqli_fetch_array($qry)) {
                                    ?>
                                        <tr class="text-center">
                                            <td><?php echo date('M d, Y', strtotime($qry2['date_return'])); ?></td>
                                            <td><?php echo $qry2['fac']; ?></td>
                                            <td><?php echo $qry2['lastname'] . ', ' . $qry2['firstname'] . ' ' . $qry2['middlename']; ?></td>
                                            <td><?php echo $qry2['accession']; ?></td>
                                            <td style="text-align: justify; text-justify: inter-word;"><?php echo $qry2['title']; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>


                                 </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                

                    <!-- Content Row -->

                    
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
    <?php include 'includes/return_modal.php'; ?>
    <?php include 'includes/logout_modal.php'; ?>
    <?php include 'includes/scripts.php'; ?>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script>
    $('#dataTable').DataTable({
        "responsive": true,
        "order": [[0, 'desc']],  // Sort by date in descending order by default
        "columnDefs": [
            {
                "targets": 0, // Target the first column (Date)
                "type": "date" // Tell DataTables this column contains dates
            }
        ]
    });
    </script>
</body>

</html>