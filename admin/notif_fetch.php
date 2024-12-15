<?php
session_start();
include 'db_connection.php'; // Include your database connection

$user_id = $_SESSION['user_id']; // Adjust according to your session handling

// Fetch unread notifications count
$query = "SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = ? AND status = 'unread'";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$unread_count = $row['unread_count'];

// Fetch unread notifications
$query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);
?>
