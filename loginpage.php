<?php
    session_start();
    if (isset($_SESSION['admin'])) {
        header('location:admin/home.php');
    }
    if (isset($_SESSION['faculty'])) {
        header('location:catalog.php');
    }
    if (isset($_SESSION['student'])) {
        header('location:catalog.php');
    }
?>

<?php include 'includes/conn.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="bg-gradient-primary d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"><img src="admin/img/img.png" class="img-fluid"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <?php
                                        if (isset($_SESSION['error'])) {
                                            echo "<div class='alert alert-danger alert-dismissible'>
                                                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                    <span class='small'>".$_SESSION['error']."</span>
                                                </div>
                                            ";
                                            unset($_SESSION['error']);
                                        }
                                    ?>
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-3">Login</h1>
                                    </div>
                                  
                                    <form class="user" action="" method="POST" id="loginForm">
                                        <div class="form-group d-flex align-items-center">
                                            <label for="login_as" class=" ml-1 mr-2 mt-2" style="white-space: nowrap; font-size: 14px;">Login as: </label>
                                            <select class="form-control" style="border-radius: 30px; font-size: 14px;" name="login_as" id="login_as" onchange="toggleFields(); toggleLoginButton(); changeFormAction();">
                                                <option value="" selected disabled>Select</option>
                                                <option value="borrower" <?php echo (isset($_POST['login_as']) && $_POST['login_as'] == 'borrower') ? 'selected' : ''; ?>>Borrower</option>
                                                <option value="admin" <?php echo (isset($_POST['login_as']) && $_POST['login_as'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                            </select>
                                        </div>

                                        <div id="borrowerFields" class="form-group" style="display:<?php echo (isset($_POST['login_as']) && $_POST['login_as'] == 'borrower') ? 'block' : 'none'; ?>;">
                                            <input type="text" name="id_number" class="form-control form-control-user" id="id_number" placeholder="Enter ID Number" value="<?php echo isset($_POST['id_number']) ? htmlspecialchars($_POST['id_number']) : ''; ?>">
                                        </div>

                                        <div id="adminFields" class="form-group" style="display:<?php echo (isset($_POST['login_as']) && $_POST['login_as'] == 'admin') ? 'block' : 'none'; ?>;">
                                            <input type="text" name="username" class="form-control form-control-user" id="username" placeholder="Enter Username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                                        </div>

                                        <div id="passwordField" class="form-group" style="display:<?php echo (isset($_POST['login_as']) && $_POST['login_as'] == 'admin') ? 'block' : 'none'; ?>;">
                                            <input type="password" name="password" class="form-control form-control-user" id="password" placeholder="Enter Password">
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-user btn-block" name="login" id="loginButton" disabled>
                                            Login
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <?php include 'includes/scripts.php'; ?>

    <script>
        function toggleFields() {
            var loginAs = document.getElementById('login_as').value;
            if (loginAs === 'borrower') {
                document.getElementById('borrowerFields').style.display = 'block';
                document.getElementById('adminFields').style.display = 'none';
                document.getElementById('passwordField').style.display = 'none';
            } else if (loginAs === 'admin') {
                document.getElementById('borrowerFields').style.display = 'none';
                document.getElementById('adminFields').style.display = 'block';
                document.getElementById('passwordField').style.display = 'block';
            }
        }

        function toggleLoginButton() {
            var loginAs = document.getElementById('login_as').value;
            var loginButton = document.getElementById('loginButton');
            // Enable the button only if a valid option is selected
            if (loginAs !== "") {
                loginButton.disabled = false;
            } else {
                loginButton.disabled = true;
            }
        }

        function changeFormAction() {
            var loginAs = document.getElementById('login_as').value;
            var form = document.getElementById('loginForm');

            // Set the form action based on selection
            if (loginAs === 'borrower') {
                form.action = 'login.php'; // Change action to login_borrower.php for borrower
            } else if (loginAs === 'admin') {
                form.action = 'admin/login.php'; // Change action to login_admin.php for admin
            } else {
                form.action = ''; // Clear action if no selection
            }
        }

        // Trigger toggleFields on page load if a previous selection exists
        window.onload = function() {
            var loginAs = document.getElementById('login_as').value;
            if (loginAs) {
                toggleFields(); // Show the correct input fields
                toggleLoginButton(); // Enable the login button if valid
            }
        };
    </script>

</body>

</html>
