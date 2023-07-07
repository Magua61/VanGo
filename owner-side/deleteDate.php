<?php

require_once '../db_connect.php';
session_start();

if (isset($_POST['id']) && isset($_POST['submit'])){
	$id = $_POST['id'];

	$query = "DELETE FROM van_unavailable_date WHERE XDate_ID=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header('Location: calendar.php');

	
?>
