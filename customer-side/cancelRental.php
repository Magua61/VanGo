<?php

require_once '../db_connect.php';

// Retrieve the form data
$rentalId = $_POST['rentalId'];

$query = "CALL cancelRental(?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $rentalId);

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