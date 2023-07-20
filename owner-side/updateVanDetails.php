<?php

require_once '../db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $vanPhoto = $_FILES['vanPhoto'];
    $vanId = $conn->real_escape_string($_POST['vanId']);
    $vanMake = $conn->real_escape_string($_POST['vanMake']);
    $vanModel = $conn->real_escape_string($_POST['vanModel']);
    $vanYear = $conn->real_escape_string($_POST['vanYear']);
    $vanCapacity = $conn->real_escape_string($_POST['vanCapacity']);
    $plateNumber = $conn->real_escape_string($_POST['plateNumber']);
    $vanRate = $conn->real_escape_string($_POST['vanRate']);
    $vanCR = $_FILES['vanCR'];
    $vanOR = $_FILES['vanOR'];

    // Process the form data as needed, for example, save the files and other data to a database
    $targetDir = "uploads/";
    $vanPhotoFileName = basename($vanPhoto['name']);
    $vanCRFileName = basename($vanCR['name']);
    $vanORFileName = basename($vanOR['name']);

    $vanPhotoPath = $targetDir . 'van_photos/' . $vanPhotoFileName;
    $vanCRPath = $targetDir . 'certificates/' . $vanCRFileName;
    $vanORPath = $targetDir . 'receipts/' . $vanORFileName;

    // Move uploaded files to the target directories
    move_uploaded_file($vanPhoto['tmp_name'], $vanPhotoPath);
    move_uploaded_file($vanCR['tmp_name'], $vanCRPath);
    move_uploaded_file($vanOR['tmp_name'], $vanORPath);

    $query = "UPDATE van SET 
            V_PlateNo = ?, 
            V_Make = ?, 
            V_Model = ?, 
            V_Year = ?, 
            V_Capacity = ? 
        WHERE Van_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssii", $plateNumber, $vanMake, $vanModel, $vanYear, $vanCapacity, $vanId);
    $stmt->execute();
                
    $query = "UPDATE van_rate SET V_Rate = ? WHERE Van_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("di", $vanRate, $vanId);
    $stmt->execute();

    if(!empty($vanPhoto)){
        $query = "UPDATE van_photo SET V_Photo = ? WHERE Van_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $vanPhotoPath, $vanId);
        $stmt->execute();
    }
    if(!empty($vanCR)){
        $query = "UPDATE van_document SET V_CR = ? WHERE Van_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $vanCRPath, $vanId);
        $stmt->execute();
    }
    if(!empty($vanOR)){
        $query = "UPDATE van_document SET V_OR = ? WHERE Van_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $vanORPath, $vanId);
        $stmt->execute();
    }

    $response = array('success' => true);
    echo json_encode($response);
    exit; // Stop execution here to prevent the "Insertion failed" response
} 

$response = array('success' => false);
echo json_encode($response);

$conn->close();

?>
