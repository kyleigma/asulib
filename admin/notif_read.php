<?php
session_start();
include 'includes/conn.php';

$mark_read_query = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
$stmt = $conn->prepare($mark_read_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
?>
