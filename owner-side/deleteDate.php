<?php

require_once '../db_connect.php';
session_start();

if (isset($_POST['id']) && isset($_POST['submit'])){
	$inXDate_ID = $_POST['id'];

	// $query = "CALL deleteDate(?)";
    // $stmt = $conn->prepare($query);
    // $stmt->bind_param("i", $id);
    // $stmt->execute();

    $updateSQL = "UPDATE rental 
        SET Rental_Status = 'Cancelled' 
        WHERE Van_ID = (SELECT Van_ID FROM van_unavailable_date WHERE XDate_ID = ?)
        AND Pickup_Date = (SELECT Start_Date FROM van_unavailable_date WHERE XDate_ID = ?)
        AND Return_Date = (SELECT End_Date FROM van_unavailable_date WHERE XDate_ID = ?)
        AND Rental_Status = 'Pending'
        LIMIT 1";
        
    $stmt = $conn->prepare($updateSQL);
    $stmt->bind_param("iii", $inXDate_ID, $inXDate_ID, $inXDate_ID);
    $stmt->execute();

    $deleteSQL = "DELETE FROM van_unavailable_date WHERE XDate_ID = ?";
    $stmt = $conn->prepare($deleteSQL);
    $stmt->bind_param("i", $inXDate_ID);
    $stmt->execute();

    
}
header('Location: owner-dashboard.php');

	
?>
