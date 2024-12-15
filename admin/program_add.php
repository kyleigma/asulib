<?php
	include 'includes/session.php';
	include 'includes/conn.php';

	if(isset($_POST['add'])){
		$code = $_POST['code'];
		$title = $_POST['title'];
		
		$check_sql = mysqli_query($conn, "SELECT * FROM program WHERE code = '$code'");
	
		if(mysqli_num_rows($check_sql) > 0){
			$_SESSION['exist'] = 'Program code already exists.';
		} else {
			$sql = mysqli_query($conn, "INSERT INTO program (code, title) VALUES ('$code', '$title')");
	
			if($sql){
				$_SESSION['success'] = 'Program added successfully.';
			}
			else{
				$_SESSION['error'] = $conn->error;
			}
		}
	}
	else{
		$_SESSION['error'] = 'Fill up add form first';
	}
	
	header('location: program.php');
?>