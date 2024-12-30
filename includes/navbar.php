<?php include 'includes/conn.php'; ?>
<?php include 'includes/header.php'; ?>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-primary bg-primary topbar mb-4 fixed-top shadow">
            <!-- Brand Name -->
            <div class="navbar-brand-small ml-3" style="align-items: center;">
                <a href="index.php" class="text-white" style="text-decoration:none;">
                    <img src="images/logo.png" class="img-profile rounded-circle mx-2" style="width: 2.1rem; height:2.1rem;" alt="logo">
                    <b>ASU KALIBO </b> LIBRARY AND INFORMATION SERVICES
                </a>
            </div>

            <ul class="navbar-nav ml-auto">
                <?php
                if (isset($_SESSION['student']) || isset($_SESSION['faculty'])) {
                    if (isset($_SESSION['student'])) {
                        echo "
                        <li class='nav-item'>
                            <a class='nav-link text-white nav-hoverable navbar-text-small' href='catalog.php'>HOME</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link text-white nav-hoverable navbar-text-small' href='transaction.php'>TRANSACTIONS</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link text-white nav-hoverable navbar-text-small' href='userreq.php'>REQUESTS</a>
                        </li>
                        <div class='topbar-divider d-none d-sm-block'></div>
                        <li class='user user-menu nav-item'>
                            <span class='nav-link text-white nav-hoverable navbar-text-small' style='cursor: default;'>";
                            $sql = "SELECT * FROM students WHERE id = '" . $_SESSION['student'] . "'";
                            $query = $conn->query($sql);
                            if ($query && $query->num_rows > 0) {
                                $student = $query->fetch_assoc();
                                echo "<img src='" . (!empty($student['photo']) ? 'images/' . $student['photo'] : 'images/default.jpg') . "' class='img-profile rounded-circle mx-2' alt='Student Image'>";
                                echo "<span class='hidden-xs text-white'>" . htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) . "</span>";
                            } 
                            echo "</span>
                        </li>";
                    } elseif (isset($_SESSION['faculty'])) {
                        echo "
                        <li class='nav-item'>
                            <a class='nav-link text-white nav-hoverable navbar-text-small' href='catalog.php'>HOME</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link text-white nav-hoverable navbar-text-small' href='transaction.php'>TRANSACTIONS</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link text-white nav-hoverable navbar-text-small' href='userreq.php'>REQUESTS</a>
                        </li>
                        <div class='topbar-divider d-none d-sm-block'></div>
                        <li class='user user-menu nav-item'>
                            <span class='nav-link text-white nav-hoverable navbar-text-small' style='cursor: default;'>";
                            $sql = "SELECT * FROM faculty WHERE faculty_id = '" . $_SESSION['faculty'] . "'";
                            $query = $conn->query($sql);
                            if ($query && $query->num_rows > 0) {
                                $faculty = $query->fetch_assoc();
                                echo "<img src='" . (!empty($faculty['photo']) ? 'images/' . $faculty['photo'] : 'images/default.jpg') . "' class='img-profile rounded-circle mx-2' alt='Faculty Image'>";
                                echo "<span class='hidden-xs text-white'>" . htmlspecialchars($faculty['firstname'] . ' ' . $faculty['lastname']) . "</span>";
                            } 
                            echo "</span>
                        </li>";
                    }

                    echo "
                    <li class='nav-item mr-3'>
                        <a href='#logout' data-toggle='modal' class='nav-link py-3 text-white nav-hoverable navbar-text-small d-flex align-items-center'>
                            <i class='fa fa-sign-out' style='font-size:20px'></i>
                            <span class='ml-2'>LOGOUT</span>
                        </a>
                    </li>";                
                } else {
                    echo "
                    <li class='nav-item mr-3'>
                        <a href='about_us.php' class='nav-link py-3 text-white nav-hoverable navbar-text-small d-flex align-items-center'>
                            <i class='fa fa-info-circle' style='font-size:20px'></i>
                            <span class='ml-2'>ABOUT US</span>
                        </a>
                    </li>
                    <li class='nav-item mr-3'>
                        <a href='loginpage.php' class='nav-link py-3 text-white nav-hoverable navbar-text-small d-flex align-items-center'>
                            <i class='fa fa-sign-in' style='font-size:20px'></i>
                            <span class='ml-2'>LOGIN</span>
                        </a>
                    </li>";
                }
                ?>
            </ul>


        </nav>

<<<<<<< HEAD
       
=======
        <!-- Offset to push content below the fixed navbar -->
        <div style="padding-top: 105px;"></div> <!-- Adjust padding as needed -->
>>>>>>> a7198e3f67bf1677812dab854b4fc18858f1709c

        <?php include 'includes/login_modal.php'; ?>
        <?php include 'includes/logout_modal.php'; ?>

        <style>
        .nav-hoverable {
            transition: background-color 0.3s; /* Smooth transition for the background color */
        }

        .nav-hoverable:hover {
            background-color: rgba(0, 0, 0, 0.2); /* Darkens the background on hover */
            color: #ffffff; /* Keep text color white on hover */
        }

        .navbar-text-small {
            font-size: 0.85rem; /* Adjust this value as needed */
        }
        </style>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">