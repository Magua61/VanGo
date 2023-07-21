<?php

require_once '../db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    
    $vanPhoto = $_FILES['vanPhoto']['name'];
    $vanPhotoTmp = $_FILES['vanPhoto']['tmp_name'];
    $vanId = $conn->real_escape_string($_POST['vanId']);
    $vanMake = $conn->real_escape_string($_POST['vanMake']);
    $vanModel = $conn->real_escape_string($_POST['vanModel']);
    $vanYear = $conn->real_escape_string($_POST['vanYear']);
    $vanCapacity = $conn->real_escape_string($_POST['vanCapacity']);
    $plateNumber = $conn->real_escape_string($_POST['plateNumber']);
    $vanRate = $conn->real_escape_string($_POST['vanRate']);
    $vanCR = $_FILES['vanCR']['name'];
    $vanCRTmp = $_FILES['vanCR']['tmp_name'];
    $vanOR = $_FILES['vanOR']['name'];
    $vanORTmp = $_FILES['vanOR']['tmp_name'];

    $destinationFolder = '../registration/uploads/van_photos/';
    $vanPhotoUpload = uploadFile('vanPhoto', $destinationFolder);
    $vanPhotoPath = $destinationFolder . $vanPhotoUpload['fileName'];

    $destinationFolder = '../registration/uploads/certificates/';
    $vanCRUpload = uploadFile('vanCR', $destinationFolder);
    $vanCRPath = $destinationFolder . $vanCRUpload['fileName'];

    $destinationFolder = '../registration/uploads/receipts/';
    $vanORUpload = uploadFile('vanOR', $destinationFolder);
    $vanORPath = $destinationFolder . $vanORUpload['fileName'];
    

    // // Process the form data as needed, for example, save the files and other data to a database
    // $targetDir = "uploads/";
    // $vanPhotoFileName = basename($vanPhoto['name']);
    // $vanCRFileName = basename($vanCR['name']);
    // $vanORFileName = basename($vanOR['name']);

    // $vanPhotoPath = $targetDir . 'van_photos/' . $vanPhotoFileName;
    // $vanCRPath = $targetDir . 'certificates/' . $vanCRFileName;
    // $vanORPath = $targetDir . 'receipts/' . $vanORFileName;

    //   // Move uploaded files to the target directories
    // if ($vanPhoto['error'] === 0) {
    //     move_uploaded_file($vanPhoto['tmp_name'], $vanPhotoPath);
    // }
    // if ($vanCR['error'] === 0) {
    //     move_uploaded_file($vanCR['tmp_name'], $vanCRPath);
    // }
    // if ($vanOR['error'] === 0) {
    //     move_uploaded_file($vanOR['tmp_name'], $vanORPath);
    // }

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

    if ($vanPhoto['error'] === 0) {
        $query = "UPDATE van_photo SET V_Photo = ? WHERE Van_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $vanPhotoPath, $vanId);
        $stmt->execute();
    }
    if ($vanCR['error'] === 0) {
        $query = "UPDATE van_document SET V_CR = ? WHERE Van_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $vanCRPath, $vanId);
        $stmt->execute();
    }
    if ($vanOR['error'] === 0) {
        $query = "UPDATE van_document SET V_OR = ? WHERE Van_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $vanORPath, $vanId);
        $stmt->execute();
    }

    $response = array('success' => true);
    echo json_encode($response);
    exit; // Stop execution here to prevent the "Insertion failed" response
}

function uploadFile($fileField, $destinationFolder)
{
    $errors = [];
    $errorFields = [];

    // Check file size
    $maxFileSize = 50 * 1024 * 1024; // 50mb
    if ($_FILES[$fileField]['size'] > $maxFileSize) {
        $errors[] = "File size exceeds the maximum limit of " . ($maxFileSize / (1024 * 1024)) . "MB.";
        $errorFields[] = $fileField;
    }

    if (!empty($fileField) && isset($_FILES[$fileField]['tmp_name']) && $_FILES[$fileField]['tmp_name'] !== '') {
      $allowedFileTypes = ['image/jpeg', 'image/png'];
      $fileType = mime_content_type($_FILES[$fileField]['tmp_name']);
      if (!in_array($fileType, $allowedFileTypes)) {
          $errors[] = "Only " . implode(", ", $allowedFileTypes) . " file types are allowed.";
          $errorFields[] = $fileField;
      }
   }
  

    // Generate a unique filename
    $fileName = uniqid() . '_' . $_FILES[$fileField]['name'];

    // Move the uploaded file to the desired location
    $destination = $destinationFolder . $fileName;
    if (!move_uploaded_file($_FILES[$fileField]['tmp_name'], $destination)) {
        $errors[] = "Error occurred while uploading the file. Please try again later.";
        $errorFields[] = $fileField;
    }

    return [
        'errors' => $errors,
        'errorFields' => $errorFields,
        'fileName' => $fileName
    ];
}

$response = array('success' => false);
echo json_encode($response);

$conn->close();

?>
