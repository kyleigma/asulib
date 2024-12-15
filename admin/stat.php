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
                        <h1 class="h3 mb-0 text-gray-800">Circulation Statistics</h1>
                    </div>

                    <!-- Container for Cards -->
                    <div class="container mt-5">
                        <div class="row justify-content-center">
                            <!-- First Card -->
                            <div class="col-12 col-md-6 col-lg-5 mb-5">
                                <a href="stat_borrow.php" class="text-decoration-none text-inherit">
                                    <div class="card border-left-primary shadow d-flex flex-column justify-content-center align-items-center h-100 p-4">
                                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                            <i class="fas fa-share fa-4x text-primary-300 mb-3"></i>
                                            <div class="text-center">
                                                <div class="font-weight-bold text-primary text-uppercase">
                                                    Borrowing
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- Second Card -->
                            <div class="col-12 col-md-6 col-lg-5 mb-5">
                                <a href="stat_return.php" class="text-decoration-none text-inherit">
                                    <div class="card border-left-primary shadow d-flex flex-column justify-content-center align-items-center h-100 p-4">
                                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                            <i class="fas fa-reply fa-4x text-primary-300 mb-3"></i>
                                            <div class="text-center">
                                                <div class="font-weight-bold text-primary text-uppercase">
                                                    Returning
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div style="height: 155px;"></div>

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
</body>


</html>