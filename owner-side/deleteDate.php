<?php

require_once '../db_connect.php';
session_start();

if (isset($_POST['id']) && isset($_POST['submit'])){
	$id = $_POST['id'];

	$query = "CALL deleteDate(?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header('Location: owner-dashboard.php');

	
?>
