<?php include 'includes/session.php'; ?>
<?php include 'includes/conn.php'; ?>
<?php include 'includes/header.php'; ?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include 'includes/navbar.php'; ?>
<<<<<<< HEAD
        <div style="padding-top: 105px;"></div>
=======
>>>>>>> a7198e3f67bf1677812dab854b4fc18858f1709c
        <div>

            <div>
                <!-- Begin Page Content -->
                <div class="container-fluid px-4 py-4">
                    <div class="row align-items-center">
                        <!-- Text and Buttons Column -->
                        <div class="col-lg-6 text-center text-lg-start d-flex flex-column justify-content-center">
                            <div class="lc-block">
                                <div class="lc-block d-block mx-auto mb-2">
                                    <img src="admin/img/logo.png" style="width: 100px; height: 100px;">
                                </div>

                                <div editable="rich">
                                    <h2 class="display-6" style="font-weight: 400;">Welcome to</h2>
                                    <h2 class="display-5" style="font-weight: 600;">Aklan State University - Kalibo Campus</h2>
                                    <h2 class="display-5" style="font-weight: 600;">Library Borrowing Management System</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Image Column -->
                        <div class="col-lg-6 text-center">
                            <img src="images/book.png" class="img-fluid" alt="Book Icon" style="max-height: 400px; object-fit: contain;">
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
            <?php include 'includes/logout_modal.php'; ?>
            <?php include 'includes/scripts.php'; ?>
            <script src="vendor/datatables/jquery.dataTables.min.js"></script>
            <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
            <script>
                // JavaScript function to filter the table based on selected category
                function filterCategory() {
                    var categoryId = document.getElementById("catlist").value;
                    var url = new URL(window.location.href);
                    url.searchParams.set('category', categoryId);
                    window.location.href = url.toString();
                }
            </script>
</body>

</html>
