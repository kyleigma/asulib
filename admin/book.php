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
                        <h1 class="h3 mb-0 text-gray-800">Book List</h1>
                        <nav style="--bs-breadcrumb-divider: '>';font-size:85%;" aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class=""><a href="home.php">Dashboard</a></li>&nbsp;&nbsp;&nbsp;
                                <li class=""><i class="fas fa-angle-right"></i></li>&nbsp;&nbsp;&nbsp;
                                <li class="active" aria-current="page">Book List</li>
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
                            <?php if ($role != 1):?>
                                <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> New</a>
                            <?php endif; ?>

                            <!-- Dropdown Filter for Category -->
                            <form method="GET" action="">
                                <div class="form-group mb-3 d-flex align-items-center">
                                    <label for="category_filter" class="mr-2 mb-0" style="line-height: 2.5;">Filter:</label>
                                    <select class="form-control" id="category_filter" name="category_filter" onchange="this.form.submit()">
                                        <option value="" <?php echo (!isset($_GET['category_filter']) || $_GET['category_filter'] == '') ? 'selected' : ''; ?>>All Categories</option>
                                        <?php
                                            // Fetch categories from the database
                                            $category_qry = mysqli_query($conn, "SELECT * FROM category");
                                            while ($category = mysqli_fetch_array($category_qry)) {
                                                $selected = (isset($_GET['category_filter']) && $_GET['category_filter'] == $category['id']) ? 'selected' : '';
                                                echo "<option value='" . $category['id'] . "' $selected>" . $category['name'] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="50">Cat. No.</th>
                                            <th width="50">Category</th>
                                            <th width="">Acc. No.</th>
                                            <th width="">Volume</th>
                                            <th width="200">Title</th>
                                            <th width="200">Author</th>
                                            <th width="150">Publisher</th>
                                            <th width="100">Publish Date</th>
                                            <th width="">Status</th>
                                            <?php if ($role != 1): ?>
                                                <th width="200">Actions</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    <?php
                                        // Apply filter if selected
                                        $filter = isset($_GET['category_filter']) && $_GET['category_filter'] != '' ? "WHERE b.category_id = ".$_GET['category_filter'] : '';

                                        // Query to fetch filtered or all books with categories
                                        $qry = mysqli_query($conn, "SELECT b.id, b.category_no, b.accession, b.volume, b.title, b.author, b.publisher, b.publish_date, b.status, c.name FROM books b LEFT JOIN category c ON c.id = b.category_id $filter");

                                        while ($qry2 = mysqli_fetch_array($qry)) {
                                            // Determine the status
                                            $status = ($qry2['status'] == 0) ? 
                                            '<span class="badge badge-success">available</span>' : 
                                            '<span class="badge badge-danger">unavailable</span>';
                                    ?>
                                            <tr class="text-center">
                                                <td><?php echo $qry2['category_no']?></td>
                                                <td><?php echo $qry2['name']?></td>
                                                <td><?php echo $qry2['accession']; ?></td>
                                                <td><?php echo $qry2['volume']; ?></td>
                                                <td style="text-align: justify; text-justify: inter-word;"><?php echo $qry2['title']; ?></td>
                                                <td><?php echo $qry2['author']; ?></td>
                                                <td><?php echo $qry2['publisher']; ?></td>
                                                <td class="text-center"><?php echo $qry2['publish_date']; ?></td>
                                                <td class="text-center"><?php echo $status; ?></td>
                                                <?php if ($role != 1): ?>
                                                    <td class="text-center"><a href="#edit<?php echo $qry2['id']; ?>" data-toggle="modal" class="btn btn-sm btn-warning mb-2"><span class="fas fa-edit"></span> </a>
                                                    <a href="#delete<?php echo $qry2['id']; ?>" data-toggle="modal" class="btn btn-sm btn-danger mb-2"><span class="fas fa-trash"></span> </a></td>
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
    <?php include 'includes/book_modal.php'; ?>
    <?php include 'includes/logout_modal.php'; ?>
    <?php include 'includes/scripts.php'; ?>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
</body>

</html>