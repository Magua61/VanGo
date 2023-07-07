<?php

require_once '../db_connect.php';
session_start();

//echo $_POST['status'];
if (isset($_POST['start']) && isset($_POST['end'])){
	
	$vanid = $_SESSION['vanid'];
	$start = $_POST['start'];
	$end = $_POST['end'];

	$query = "INSERT INTO van_unavailable_date(Van_ID, Start_Date, End_Date, Status) 
			values (?, ?, ?, 'Unavailable')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $vanid, $start, $end);
    $stmt->execute();

}
header('Location: '.$_SERVER['HTTP_REFERER']);

	
?>
