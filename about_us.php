<?php include 'includes/session.php'; ?>
<?php include 'includes/conn.php'; ?>
<?php include 'includes/header.php'; ?>

<body id="page-top" style="margin: 0; padding: 0;"> 

    <!-- Page Wrapper -->
    <div id="wrapper" style="margin: 0; padding: 0;">

        <!-- Directly include the navbar without affecting other files -->
        <?php include 'includes/navbar.php'; ?>

        <!-- Hero Section with background applied to the div -->
        <div style="position: relative; height: 100vh; background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('images/bg.jpg'); background-size: cover; background-position: center center; background-attachment: fixed;">
            <!-- Hero Section with background applied to the div -->
            <div style="position: relative; height: 100vh; background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('images/bg.jpg'); background-size: cover; background-position: center center; background-attachment: fixed;">
                <!-- Centered Content with decreased top margin -->
                <div style="position: absolute; top: 40%; left: 50%; transform: translate(-50%, -25%); text-align: center; color: white;">
                    <img src="admin/img/logo.png" alt="University Logo" style="width: 120px; height: 120px; margin-bottom: 20px;">
                    <h1 class="display-4" style="font-weight: 700;">About Us</h1>
                    <p class="lead" style="max-width: 600px; font-size: 1.2rem; margin: 0 auto;">
                        Welcome to the Aklan State University - Kalibo Campus Library. Our mission is to support academic excellence by providing
                        accessible and comprehensive library resources to our students, faculty, and staff.
                    </p>
                    <p style="font-size: 1rem; margin-top: 20px; font-weight: normal;">
                        A Capstone Project by <br>
                        <b>Marenel B. Cometa, Kyle Igma, Joyce Anne C. Malilay, & Johanne Kristal M. Villanueva</b> <br>
                        Bachelor of Science in Information Technology - Major in Instructional Systems Technology
                    </p>
                </div>
            </div>
        </div>

        <?php include 'includes/footer.php'; ?>
    </div>
    <!-- End of Page Wrapper -->

    <?php include 'includes/logout_modal.php'; ?>
    <?php include 'includes/scripts.php'; ?>

</body>

</html>
