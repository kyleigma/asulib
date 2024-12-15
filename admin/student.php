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
                        <h1 class="h3 mb-0 text-gray-800">Student List</h1>
                        <nav style="--bs-breadcrumb-divider: '>';font-size:85%;" aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class=""><a href="home.php">Dashboard</a></li>&nbsp;&nbsp;&nbsp;
                                <li class=""><i class="fas fa-angle-right"></i></li>&nbsp;&nbsp;&nbsp;
                                <li class="active" aria-current="page">Student List</li>
                            </ol>
                        </nav>
                    </div>

                    <?php
                        if (isset($_SESSION['error'])) {
                            echo "
                                <div class='alert alert-danger alert-dismissible'>
                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                <h5><i class='icon fa fa-exclamation-triangle'></i> Error!</h5>
                                " . $_SESSION['error'] . "
                                </div>
                            ";
                            unset($_SESSION['error']);
                        }

                        if (isset($_SESSION['success'])) {
                            echo "
                                <div class='alert alert-success alert-dismissible'>
                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                <h5><i class='icon fa fa-check'></i> Success!</h5>
                                " . $_SESSION['success'] . "
                                </div>
                            ";
                            unset($_SESSION['success']);
                        }

                        if (isset($_SESSION['exist'])) {
                            echo "
                                <div class='alert alert-warning alert-dismissible'>
                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                <h5><i class='icon fa fa-exclamation-triangle'></i> Warning!</h5>
                                " . $_SESSION['exist'] . "
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
                                    <!-- Buttons on the left with Flexbox -->
                                    <div class="d-flex">
                                        <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-flat mr-2"><i class="fa fa-plus"></i> New</a>
                                        <a href="#importModal" data-toggle="modal" class="btn btn-primary btn-flat"><i class="fa fa-sign-in-alt"></i> Import</a>
                                    </div>
                                <?php endif; ?>

                                <!-- Filter on the right -->
                                <form method="GET" action="" class="d-flex align-items-center">
                                    <label for="program_filter" class="mr-2 mb-0" style="line-height: 2.5;">Filter:</label>
                                    <select class="form-control" id="program_filter" name="program_filter" onchange="this.form.submit()">
                                        <option value="" <?php echo (!isset($_GET['program_filter']) || $_GET['program_filter'] == '') ? 'selected' : ''; ?>>All Programs</option>
                                        <?php
                                            // Fetch programs from the database
                                            $program_qry = mysqli_query($conn, "SELECT * FROM program");
                                            while ($program = mysqli_fetch_array($program_qry)) {
                                                $selected = (isset($_GET['program_filter']) && $_GET['program_filter'] == $program['id']) ? 'selected' : '';
                                                echo "<option value='" . $program['id'] . "' $selected>" . $program['code'] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="80">Student ID</th>
                                            <th width="50">Program</th>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Middle Name</th>
                                            <?php if ($role != 1): ?>
                                                <th width="95">Actions</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        // Get the selected program filter from the dropdown
                                        $program_filter = isset($_GET['program_filter']) ? $_GET['program_filter'] : '';

                                        // Modify the query based on the selected program
                                        if ($program_filter != '') {
                                            $qry = mysqli_query($conn, "SELECT students.id, students.student_id, students.firstname, students.lastname, students.middlename, program.code 
                                                                        FROM students 
                                                                        LEFT JOIN program ON program.id = students.program_id 
                                                                        WHERE students.program_id = '$program_filter' ORDER BY `lastname` ASC");
                                        } else {
                                            // Default query to display all students if no program is selected
                                            $qry = mysqli_query($conn, "SELECT students.id, students.student_id, students.firstname, students.lastname, students.middlename, program.code 
                                                                        FROM students 
                                                                        LEFT JOIN program ON program.id = students.program_id
                                                                        ORDER BY students.program_id ASC, students.lastname ASC");
                                        }

                                        // Loop through the query result and display each student
                                        while ($qry2 = mysqli_fetch_array($qry)) {
                                        ?>
                                            <tr class="text-center">
                                                <td><?php echo $qry2['student_id']; ?></td>
                                                <td><?php echo $qry2['code']; ?></td>
                                                <td><?php echo ucwords($qry2['lastname']); ?></td>
                                                <td><?php echo ucwords($qry2['firstname']); ?></td>
                                                <td><?php echo ucwords($qry2['middlename'] ?? ''); ?></td>

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
    <?php include 'includes/student_modal.php'; ?>
    <?php include 'includes/logout_modal.php'; ?>
    <?php include 'includes/scripts.php'; ?>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script>
        $('#dataTable').DataTable({
            "responsive": true,
            "order": [
                [1, 'asc'],
                [2, 'asc']
            ]
        });
    </script>

    
</body>

</html>