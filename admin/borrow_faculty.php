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
                        <h1 class="h3 mb-0 text-gray-800">Faculty & Staff Borrow</h1>
                        <nav style="--bs-breadcrumb-divider: '>';font-size:85%;" aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class=""><a href="home.php">Dashboard</a></li>&nbsp;&nbsp;&nbsp;
                                <li class=""><i class="fas fa-angle-right"></i></li>&nbsp;&nbsp;&nbsp;
                                <li class=""><a href="borrow.php">Borrow</a></li>&nbsp;&nbsp;&nbsp;
                                <li class=""><i class="fas fa-angle-right"></i></li>&nbsp;&nbsp;&nbsp;
                                <li class="active" aria-current="page">Faculty & Staff Borrow</li>
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
                                            <th width="50">Date</th>
                                            <th width="50">Faculty/Staff ID</th>
                                            <th width="100">Name</th>
                                            <th width="100">Accession No.</th>
                                            <th>Title</th>
                                            <th>Due Date</th>
                                            <th width="20">Status</th>
                                            <th width="100">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                        $qry = mysqli_query($conn, "SELECT *, borrow.id as id, faculty.faculty_id AS fac, borrow.status AS barstat FROM borrow LEFT JOIN faculty ON faculty.id = borrow.faculty_id LEFT JOIN books ON books.id = borrow.book_id WHERE student_id IS NULL OR student_id = '' ORDER BY date_borrow DESC;");
                                        while ($qry2 = mysqli_fetch_array($qry)) {
                                            // If status is 1, it means the book is borrowed, not returned yet
                                            if($qry2['barstat'] == 1){
                                                $status = '<span class="badge badge-danger">borrowed</span>';
                                            }
                                            // If status is 0, it means the book has been returned
                                            else{
                                                $status = '<span class="badge badge-success">returned</span>';
                                            }
                                    ?>

                                    <tr class="text-center">
                                        <td><?php echo date('M d, Y', strtotime($qry2['date_borrow'])); ?></td>
                                        <td><?php echo $qry2['faculty_id']; ?></td>
                                        <td><?php echo $qry2['lastname'] . ', ' . $qry2['firstname'] . ' ' . $qry2['middlename']; ?></td>
                                        <td><?php echo $qry2['accession']; ?></td>
                                        <td style="text-align: justify; text-justify: inter-word;"><?php echo $qry2['title']; ?></td>
                                        <td><?php echo date('M j, Y', strtotime($qry2['due_date']));?></td>
                                        <td class="text-center"><?php echo $status; ?></td>
                                        <td style="text-align: center;">
                                            <a href="receipt.php?borrow_id=<?php echo $qry2['id']; ?>" class="btn btn-sm btn-secondary" onclick="window.open(this.href, '_blank', 'width=800, height=600'); return false;">
                                                <span class="fas fa-print"></span> Print
                                            </a>
                                        </td>
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
    <?php include 'includes/borrow_modal.php'; ?>
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