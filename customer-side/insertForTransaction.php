<?php
require_once '../db_connect.php'; // Include the database connection file

// Retrieve the form data
$vanId = $conn->real_escape_string($_POST['vanId']);
$customerId = $conn->real_escape_string($_POST['customerId']);
$destination = $conn->real_escape_string($_POST['destination']);
$pickupAddress = $conn->real_escape_string($_POST['pickupAddress']);
$pickupDate = $conn->real_escape_string($_POST['pickupDate']);
$pickupTime = $conn->real_escape_string($_POST['pickupTime']);
$returnDate = $conn->real_escape_string($_POST['returnDate']);
$returnTime = $conn->real_escape_string($_POST['returnTime']);
$totalPrice = $conn->real_escape_string($_POST['totalPrice']);
$returnAddress = $conn->real_escape_string($_POST['returnAddress']);

// Insert rental record
$rentalStatus = 'Pending';
$query = "INSERT INTO rental (Van_ID, Customer_ID, Destination, Pickup_Address, Pickup_Date, Pickup_Time, Return_Date, Return_Time, Rental_Status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iisssssss", $vanId, $customerId, $destination, $pickupAddress, $pickupDate, $pickupTime, $returnDate, $returnTime, $rentalStatus);

$rentalInsertSuccess = $stmt->execute();

$rentalId = $stmt->insert_id; // Get the last inserted rental ID

// Insert rental without driver record if return address is provided
if (!empty($returnAddress)) {
    $query = "INSERT INTO rental_without_driver (Rental_ID, Return_Address)
                VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $rentalId, $returnAddress);

    $rentalWithoutDriverInsertSuccess = $stmt->execute();
} else {
    $rentalWithoutDriverInsertSuccess = true;
}

// Insert payment record
date_default_timezone_set('Asia/Manila');
$paymentDateTime = date('Y-m-d H:i:s'); // Current date and time
$query = "INSERT INTO payment (Rental_ID, Payment_Amount, Payment_Date_Time)
            VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ids", $rentalId, $totalPrice, $paymentDateTime);

$paymentInsertSuccess = $stmt->execute();

// Prepare the response
$response = array();
if ($rentalInsertSuccess && $rentalWithoutDriverInsertSuccess && $paymentInsertSuccess) {
    // All records inserted successfully
    $response['success'] = true;
    $response['message'] = "Records inserted successfully.";
} else {
    // Error inserting records
    $response['success'] = false;
    $response['message'] = "Error inserting records.";
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
