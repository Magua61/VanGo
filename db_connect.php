<?php
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "if0_34662655_vango";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

$currentDate = date('Y-m-d');
$currentTime = date('H:i:s');

$sql = "SELECT Rental_ID, Van_ID, Pickup_Date, Return_Date  
        FROM rental WHERE Return_Date <= ? AND Rental_Status = 'Pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $currentDate);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update rental status to 'completed' for each matching rental
    while ($row = $result->fetch_assoc()) {
        $rentalID = $row['Rental_ID'];
        $vanID = $row['Van_ID'];
        $pickupDate = $row['Pickup_Date'];
        $returnDate = $row['Return_Date'];

        // SQL query to update the rental status to 'completed'
        $updateSql = "UPDATE rental SET Rental_Status = 'Completed' WHERE Rental_ID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("i", $rentalID);
        $updateStmt->execute();

        $updateSql = "UPDATE van_unavailable_date
                    SET Status = 'Completed'
                    WHERE Van_ID = ?
                      AND Start_Date = ?
                      AND End_Date = ?
                      AND Status = 'Booked';
                    ";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("iss", $vanID, $pickupDate, $returnDate);
        $updateStmt->execute();

    }
}