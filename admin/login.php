<?php
	session_start();
	include 'includes/conn.php';

	if(isset($_POST['login'])){
		$username = $_POST['username'];
		$password = $_POST['password'];

		// Prepare and bind
		$stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();

		if($result->num_rows < 1){
			$_SESSION['error'] = 'Cannot find account with the username';
		}
		else{
			$row = $result->fetch_assoc();

			// Check if the account is active or inactive
			if($row['status'] == 1){
				$_SESSION['error'] = 'Your account is inactive. Please contact the administrator.';
			}
			else {
				// Account is active, proceed to verify the password
				if(password_verify($password, $row['password'])){ // Check hashed password
					$_SESSION['admin'] = $row['id'];
				}
				elseif($password == $row['password']){ // Check unhashed password (optional case, consider removing this for security)
					$_SESSION['admin'] = $row['id'];
				}
				else{
					$_SESSION['error'] = 'Incorrect password';
				}
			}
		}
	}
	else{
		$_SESSION['error'] = 'Input admin credentials first';
	}

	header('location: ../loginpage.php');
?>
