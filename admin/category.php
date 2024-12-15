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
                        <h1 class="h3 mb-0 text-gray-800">Categories</h1>
                        <nav style="--bs-breadcrumb-divider: '>';font-size:85%;" aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class=""><a href="home.php">Dashboard</a></li>&nbsp;&nbsp;&nbsp;
                                <li class=""><i class="fas fa-angle-right"></i></li>&nbsp;&nbsp;&nbsp;
                                <li class="active" aria-current="page">Categories</li>
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
                        <div class="card-body" >
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> New</a>
                        </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th width="20%">Actions</th>
                                            
                                        </tr>
                                    </thead>
                        
                                    <tbody>
                                        <?php

                                            $qry = mysqli_query($conn, "SELECT * FROM `category` ORDER BY `category`.`id` DESC;");
                                            while ($qry2 = mysqli_fetch_array($qry)) {
                                        ?>


                                        <tr>
                                            <td><?php echo $qry2['name']; ?></td>
                                            <td class="text-center m-5">
                                            <a href="#edit<?php echo $qry2['id']; ?>" data-toggle="modal" class="btn btn-sm btn-warning"><span class="fas fa-edit"></span> Edit</a>
                                            <a href="#delete<?php echo $qry2['id']; ?>" data-toggle="modal" class="btn btn-sm btn-danger"><span class="fas fa-trash"></span> Delete</a>
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


    <?php include 'includes/category_modal.php'; ?>
    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/logout_modal.php'; ?>
    <?php include 'includes/scripts.php'; ?>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
</body>

</html>