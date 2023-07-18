<?php

require_once '../db_connect.php';

// Retrieve the form data
$rentalId = $_POST['rentalId'];
$reviewRating = $_POST['reviewRating'];
$reviewComment = $_POST['reviewComment'];

// Handle the file input
$files = $_FILES['fileInput'];
$fileNames = array();

// Specify the directory to store the uploaded files
$targetDir = "../registration/uploads/reviews/";

// Check if any file is uploaded
if (!empty($files['name'][0])) {
    // Loop through each file
    foreach ($files['tmp_name'] as $key => $tmpName) {
        // Generate a unique filename for each file
        $fileName = uniqid() . '_' . $files['name'][$key];
        $targetFilePath = $targetDir . $fileName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($tmpName, $targetFilePath)) {
            $fileNames[] = $fileName;
        }
    }
}

// Prepare the SQL statement to insert the review details
$query = "INSERT INTO review (Review_Rating, Review_Comment, Review_Datetime, Rental_ID) VALUES (?, ?, NOW(), ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("isi", $reviewRating, $reviewComment, $rentalId);

// Execute the statement to insert the review details
if ($stmt->execute()) {
    // Get the auto-generated review ID
    $reviewId = $stmt->insert_id;

    // Insert the file names into the review_photo table if files exist
    if (!empty($fileNames)) {
        foreach ($fileNames as $fileName) {
            $insertQuery = "INSERT INTO review_photo (Review_ID, Review_Photo) VALUES (?, ?)";
            $stmtFile = $conn->prepare($insertQuery);
            $stmtFile->bind_param("is", $reviewId, $fileName);
            $stmtFile->execute();
        }
    }

    // Insertion successful
    $response = array('success' => true);
    echo json_encode($response);
    exit; // Stop execution here to prevent the "Insertion failed" response
} 

// Insertion failed
$response = array('success' => false);
echo json_encode($response);

$conn->close();
?>
