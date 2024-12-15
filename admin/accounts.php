<?php include 'includes/session.php'; ?>
<?php include 'includes/conn.php'; ?>
<?php include 'includes/header.php'; ?>

<?php
// Check the user's role
$qry = mysqli_query($conn, "SELECT role FROM admin WHERE id = '".$_SESSION['admin']."'");
$qry2 = mysqli_fetch_array($qry);

if ($qry2['role'] == 1) {
    // User is not an admin, redirect to a different page
    header('Location: home.php');
    exit;
}
?>

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
                        <h1 class="h3 mb-0 text-gray-800">Accounts</h1>
                        <nav style="--bs-breadcrumb-divider: '>';font-size:85%;" aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class=""><a href="home.php">Dashboard</a></li>&nbsp;&nbsp;&nbsp;
                                <li class=""><i class="fas fa-angle-right"></i></li>&nbsp;&nbsp;&nbsp;
                                <li class="active" aria-current="page">Accounts</li>
                            </ol>
                        </nav>
                    </div>
                    

                    <?php
                        if(isset($_SESSION['error'])){
                        echo "
                            <div class='alert alert-danger alert-dismissible'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                            <h5><i class='icon fa fa-warning'></i> Error!</h5>
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
                        if(isset($_SESSION['exist'])){
                            echo "
                                <div class='alert alert-warning alert-dismissible'>
                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                <h5><i class='icon fa fa-exclamation-triangle'></i> Warning!</h5>
                                ".$_SESSION['exist']."
                                </div>
                            ";
                            unset($_SESSION['exist']);
                        }
                    ?>
                    
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> New</a>
                        </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="20">ID</th>
                                            <th width="50">Name</th>
                                            <th width="50">Username</th>
                                            <th width="25">Date Created</th>
                                            <th width="50">Role</th>
                                            <th width="50">Status</th>
                                            <th width="100">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                            $qry = mysqli_query($conn, "SELECT * FROM admin");
                                            while ($qry2 = mysqli_fetch_array($qry)) {
                                                // Check role and output span accordingly
                                                if ($qry2['role'] == 0) {
                                                    $role = '<span class="badge badge-primary">Admin</span>';
                                                } else {
                                                    $role = '<span class="badge badge-secondary">Staff</span>';
                                                }
                                    
                                                // Define status logic (example based on the previous code for status)
                                                if ($qry2['status'] == 0) {
                                                    $status = '<span class="badge badge-success">Active</span>';
                                                } else {
                                                    $status = '<span class="badge badge-danger">Inactive</span>';
                                                }
                                        ?>
                                                <tr class="text-center">
                                                    <td><?php echo $qry2['id']?></td>
                                                    <td><?php echo $qry2['firstname'] . ' ' . $qry2['lastname']; ?></td>
                                                    <td><?php echo $qry2['username']; ?></td>
                                                    <td class="text-center"><span class="badge badge-light"><?php echo $qry2['created_on']; ?></span></td>
                                                    <td class="text-center"><?php echo $role; ?></td>
                                                    <td class="text-center"><?php echo $status; ?></td>
                                                    <td class="text-center"><a href="#edit<?php echo $qry2['id']; ?>" data-toggle="modal" class="btn btn-sm btn-warning text-center"><span class="fas fa-edit"></span></a>
                                                    <a href="#delete<?php echo $qry2['id']; ?>" data-toggle="modal" class="btn btn-sm btn-danger text-center"><span class="fas fa-trash"></span></a></td>
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
    <?php include 'includes/account_modal.php'; ?>
    <?php include 'includes/logout_modal.php'; ?>
    <?php include 'includes/scripts.php'; ?>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
</body>

</html>