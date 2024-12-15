<?php
	include 'includes/session.php';
	include 'includes/conn.php';

	if(isset($_POST['add'])){
		$name = $_POST['name'];
		
		$check_sql = mysqli_query($conn, "SELECT * FROM category WHERE name = '$name'");
	
		if(mysqli_num_rows($check_sql) > 0){
			$_SESSION['exist'] = 'Category name already exists.';
		} else {
			$sql = mysqli_query($conn, "INSERT INTO category (name) VALUES ('$name')");
	
			if($sql){
				$_SESSION['success'] = 'Category added successfully.';
			}
			else{
				$_SESSION['error'] = $conn->error;
			}
		}
	}
	else{
		$_SESSION['error'] = 'Fill up add form first';
	}
	
	header('location: category.php');
?>