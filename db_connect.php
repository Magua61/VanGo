<?php
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "db_vango";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

$currentDate = date('Y-m-d');
$currentTime = date('H:i:s');

$sql = "SELECT Rental_ID FROM rental WHERE Return_Date <= ? AND Return_Time <= ? AND Rental_Status = 'Pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $currentDate, $currentTime);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update rental status to 'completed' for each matching rental
    while ($row = $result->fetch_assoc()) {
        $rentalID = $row['Rental_ID'];

        // SQL query to update the rental status to 'completed'
        $updateSql = "UPDATE rental SET Rental_Status = 'Completed' WHERE Rental_ID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("i", $rentalID);
        $updateStmt->execute();
    }
}