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
                        <h1 class="h3 mb-0 text-gray-800">Faculty & Staff List</h1>
                        <nav style="--bs-breadcrumb-divider: '>';font-size:85%;" aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class=""><a href="home.php">Dashboard</a></li>&nbsp;&nbsp;&nbsp;
                                <li class=""><i class="fas fa-angle-right"></i></li>&nbsp;&nbsp;&nbsp;
                                <li class="active" aria-current="page">Faculty & Staff List</li>
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
                                <?php if ($role != 1): ?>
                                    <div class="d-flex">
                                        <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-flat mr-2"><i class="fa fa-plus"></i> New</a>
                                        <a href="#importModal" data-toggle="modal" class="btn btn-primary btn-flat"><i class="fa fa-sign-in-alt"></i> Import</a>
                                    </div>
                                <?php endif; ?>

                                <!-- Dropdown Filter for Position -->
                                <form method="GET" action="" class="d-flex align-items-center">
                                    <label for="position_filter" class="mr-2 mb-0" style="line-height: 2.5;">Filter:</label>  
                                        <select class="form-control" name="position_filter" onchange="this.form.submit()">
                                            <option value="" <?php echo (!isset($_GET['position_filter']) || $_GET['position_filter'] == '') ? 'selected' : ''; ?>>All</option>
                                            <option value="0" <?php echo (isset($_GET['position_filter']) && $_GET['position_filter'] == '0') ? 'selected' : ''; ?>>Faculty</option>
                                            <option value="1" <?php echo (isset($_GET['position_filter']) && $_GET['position_filter'] == '1') ? 'selected' : ''; ?>>COS Faculty</option>
                                            <option value="2" <?php echo (isset($_GET['position_filter']) && $_GET['position_filter'] == '2') ? 'selected' : ''; ?>>Faculty in External Campus</option>
                                            <option value="3" <?php echo (isset($_GET['position_filter']) && $_GET['position_filter'] == '3') ? 'selected' : ''; ?>>Non-Teaching Staff</option>
                                        </select>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="dataTableFac" width="100%" cellspacing="0">
                                    <thead>
                                        <?php 
                                        // Initialize position_filter
                                        $position_filter = isset($_GET['position_filter']) ? $_GET['position_filter'] : '';

                                        // Determine whether to show the Program column based on the filter
                                        $show_program = ($position_filter === '0' || $position_filter === '1'); // Show if filter is set to Faculty or COS Faculty
                                        ?>
                                        <tr class="text-center">
                                            <th width="100">Faculty/Staff ID</th>
                                            <th width="100">Position</th>
                                            <?php if ($show_program): // Show Program column if filter is set to Faculty or COS Faculty ?>
                                                <th width="100">Program</th>
                                            <?php endif; ?>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Middle Initial</th>
                                            <?php if ($role != 1): ?>
                                                <th width="100">Actions</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Modify query based on the filter selection
                                        if ($position_filter === '0') {
                                            $qry = mysqli_query($conn, "SELECT faculty.*, program.code FROM faculty LEFT JOIN program ON faculty.program_id = program.id WHERE faculty.position = 0;");
                                        } elseif ($position_filter === '1') {
                                            $qry = mysqli_query($conn, "SELECT faculty.*, program.code FROM faculty LEFT JOIN program ON faculty.program_id = program.id WHERE faculty.position = 1;");
                                        } elseif ($position_filter === '2') {
                                            $qry = mysqli_query($conn, "SELECT * FROM faculty WHERE position = 2;");
                                        } elseif ($position_filter === '3') {
                                            $qry = mysqli_query($conn, "SELECT * FROM faculty WHERE position = 3;");
                                        } else {
                                            $qry = mysqli_query($conn, "SELECT faculty.*, program.code FROM faculty LEFT JOIN program ON faculty.program_id = program.id ORDER BY faculty_id ASC;");
                                        }

                                        // Loop to display filtered data
                                        while ($qry2 = mysqli_fetch_array($qry)) {
                                        ?>
                                            <tr class="text-center">
                                                <td><?php echo $qry2['faculty_id']; ?></td>
                                                <td>
                                                    <?php 
                                                    // Badge display based on position value
                                                    if ($qry2['position'] == 0): ?>
                                                        <span class="badge badge-success">Faculty</span>
                                                    <?php elseif ($qry2['position'] == 1): ?>
                                                        <span class="badge badge-info">COS Faculty</span>
                                                    <?php elseif ($qry2['position'] == 2): ?>
                                                        <span class="badge badge-primary">Faculty in External Campus</span>
                                                    <?php elseif ($qry2['position'] == 3): ?>
                                                        <span class="badge badge-secondary">Non-Teaching Staff</span>
                                                    <?php endif; ?>
                                                </td>
                                                <?php if ($show_program): // Show program column if filter is set to Faculty or COS Faculty ?>
                                                    <td><?php echo $qry2['code'] ?? 'N/A'; ?></td>
                                                <?php endif; ?>
                                                <td><?php echo $qry2['lastname']; ?></td>
                                                <td><?php echo $qry2['firstname']; ?></td>
                                                <td><?php echo $qry2['middlename']; ?></td>
                                                <?php if ($role != 1): ?>
                                                    <td class="text-center">
                                                        <a href="#view<?php echo $qry2['id']; ?>" data-toggle="modal" class="btn btn-sm btn-info"><span class="fas fa-eye"></span></a>
                                                        <a href="#edit<?php echo $qry2['id']; ?>" data-toggle="modal" class="btn btn-sm btn-warning"><span class="fas fa-edit"></span></a>
                                                        <a href="#delete<?php echo $qry2['id']; ?>" data-toggle="modal" class="btn btn-sm btn-danger"><span class="fas fa-trash"></span></a>
                                                    </td>
                                                <?php endif; ?>
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
    <?php include 'includes/faculty_modal.php'; ?>
    <?php include 'includes/logout_modal.php'; ?>
    <?php include 'includes/scripts.php'; ?>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    
</body>

</html>