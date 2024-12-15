<?php
// Check the user's role
$qry = mysqli_query($conn, "SELECT role FROM admin WHERE id = '".$_SESSION['admin']."'");
$qry2 = mysqli_fetch_array($qry);
$role = $qry2['role'];
?>

<!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion scrollbar" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex flex-column align-items-center justify-content-center my-4" href="home.php">
                <div class="sidebar-brand-icon">
                    <img src="../images/logo.png" class="img-profile rounded-circle" style="width: 6rem; height: 6rem;" alt="logo">
                </div>
            </a>



            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link dashboard" href="home.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Manage
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTrans"
                    aria-expanded="true" aria-controls="collapseTrans">
                    <i class="fas fa-fw fa-exchange-alt"></i>
                    <span>Transactions</span>
                </a>
                <div id="collapseTrans" class="collapse" aria-labelledby="headingTrans" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item borrow" href="borrow.php">Borrow</a>
                        <a class="collapse-item return" href="return.php">Return</a>
                        <a class="collapse-item return" href="adminreq.php">Borrowing Requests</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInvent"
                    aria-expanded="true" aria-controls="collapseInvent">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Inventory</span>
                </a>
                <div id="collapseInvent" class="collapse" aria-labelledby="headingInvent" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item booklist" href="book.php">Book List</a>
                        <?php if ($role != 1): ?>
                        <a class="collapse-item categ" href="category.php">Categories</a>
                        <?php endif; ?>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStudent"
                    aria-expanded="true" aria-controls="collapseStudent">
                    <i class="fas fa-fw fa-graduation-cap"></i>
                    <span>Students</span>
                </a>
                <div id="collapseStudent" class="collapse" aria-labelledby="headingStudent" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item student" href="student.php">Student List</a>
                    <?php if ($role != 1): ?>
                    <a class="collapse-item program" href="program.php">Programs</a>
                    <?php endif; ?>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFaculty"
                    aria-expanded="true" aria-controls="collapseFaculty">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Faculty & Staff</span>
                </a>
                <div id="collapseFaculty" class="collapse" aria-labelledby="headingFaculty" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item student" href="faculty.php">Faculty & Staff List</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Record
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
             <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReport"
                    aria-expanded="true" aria-controls="collapseReport">
                    <i class="fas fa-fw fa-chart-bar"></i>
                    <span>Reports</span>
                </a>
                <div id="collapseReport" class="collapse" aria-labelledby="headingReport" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item fines" href="fines.php">Fines & Dues</a>
                        <a class="collapse-item stat" href="statistics.php">Statistics</a>
                    </div>
                </div>
            </li>

            <?php if ($role != 1):?>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Users
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAccounts"
                    aria-expanded="true" aria-controls="collapseAccounts">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Accounts</span>
                </a>
                <div id="collapseAccounts" class="collapse" aria-labelledby="headingAccounts" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item manage" href="accounts.php">Manage</a>
                    </div>
                </div>
            </li>
            <?php endif; ?>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Sidebar Toggler (Sidebar) -->
             <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div> 

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item d-flex align-items-center mx-1">
                            <span class="navbar-brand text-gray-600 mb-0" style="font-size: 20px;"><strong>ASU KALIBO</strong> LIBRARY AND INFORMATION SERVICES</span>
                        </li>
                    </ul>

                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php echo "<img src='" . (!empty($user['photo']) ? '../images/' . $user['photo'] : '../images/default.jpg') . "' class='img-profile rounded-circle' alt='Admin Image'>";?>
                                <span class="ml-2 d-none d-lg-inline text-gray-600" style="font-size: 15px;">
                                    <?php echo $user['firstname'].' '.$user['lastname']; ?>
                                </span>
                                <i class="fas fa-caret-down ml-2"></i> 
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#profile" data-toggle="modal" id="admin_profile">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>

                </nav>

                
                <!-- End of Topbar -->

<style>
#accordionSidebar {
  position: fixed;
  top: 0;
  left: 0;
  width: 249px; /* adjust the width to match your sidebar width */
  height: 100vh;
  overflow-y: auto; /* changed from hidden to auto */
  z-index: 1000;
  transition: width 0.5s;
}

#accordionSidebar.collapsed {
  overflow-y: visible; /* added overflow-y: visible */
  padding: 0; /* adjust padding to 0 */
  width: 104px; /* adjust width to match your collapsed sidebar width */
}

#content-wrapper {
  padding-left: 224px; /* adjust the padding to match your sidebar width */
  transition: padding 0.5s;
  overflow-y: auto; /* added overflow-y: auto */
  height: 100vh; /* added height: 100vh */
  padding-top: 0rem; /* ensure content does not overlap with sticky topbar */
}

#content-wrapper.collapsed {
  padding-left: 104px; /* updated padding for collapsed sidebar */
  margin-left: 0;
  width: calc(100% - 104px); /* updated width calculation */
}

.topbar {
   position: sticky;
   top: 0;
   z-index: 1000;
}

.scrollbar {
  overflow-y: auto;
  overflow-x: hidden;
}
</style>

<script>
    $('#sidebarToggle').on('click', function() {
  $('#accordionSidebar').toggleClass('collapsed');
  $('#content-wrapper').toggleClass('collapsed');
});
</script>

<?php include 'includes/profile_modal.php'; ?>
