<?php
	if (session_status() == PHP_SESSION_NONE) {
	session_start();
	}
	
	include 'includes/conn.php';
	

	if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
		header('location: ../loginpage.php');
	}

	$sql = "SELECT * FROM admin WHERE id = '".$_SESSION['admin']."'";
	$query = $conn->query($sql);
	$user = $query->fetch_assoc();

	// Check if the welcome message has already been displayed
	if (!isset($_SESSION['logged_in'])) {
		$_SESSION['logged_in'] = true; // Set session variable to indicate the user has logged in
		// Prepare the welcome message
		$_SESSION['welcome_message'] = "Welcome, " . $user['firstname'] . ' ' . $user['lastname']."!";
	}

	
	
?>