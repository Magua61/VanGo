<?php

require_once '../db_connect.php';

// Retrieve the form data
$rentalId = $_POST['rentalId'];

$updateSQL = "UPDATE rental SET Rental_Status = 'Cancelled' WHERE Rental_ID = ?";
$stmt = $conn->prepare($updateSQL);
$stmt->bind_param("i", $rentalId);
$stmt->execute();

$deleteSQL = "DELETE FROM van_unavailable_date 
        WHERE 
        Van_ID = (SELECT Van_ID FROM rental WHERE Rental_ID = ?) 
        AND Start_Date = (SELECT Pickup_Date FROM rental WHERE Rental_ID = ?)
        AND End_Date = (SELECT Return_Date FROM rental WHERE Rental_ID = ?)";
$stmt = $conn->prepare($deleteSQL);
$stmt->bind_param("iii", $rentalId, $rentalId, $rentalId);

// Execute the statement to cancel the rental
if ($stmt->execute()) {

    // Cancellation successful
    $response = array('success' => true);
    echo json_encode($response);
    exit; // Stop execution here to prevent the "Cancellation failed" response
} 

// Cancellation failed
$response = array('success' => false);
echo json_encode($response);

$conn->close();
?>