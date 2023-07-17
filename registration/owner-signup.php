<?php

require_once '../db_connect.php';
session_start();

// Define the error variable
$errors = [];
$errorFields = [];

$query = "SELECT * FROM owner WHERE O_Email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$result = $stmt->get_result();

$owner = $result->fetch_assoc();

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $validID = $_FILES['validID']['name'];
    $validIDTmp = $_FILES['validID']['tmp_name'];
    $ltoCertificate = $_FILES['ltoCertificate']['name'];
    $ltoCertificateTmp = $_FILES['ltoCertificate']['tmp_name'];
    $officialReceipt = $_FILES['officialReceipt']['name'];
    $officialReceiptTmp = $_FILES['officialReceipt']['tmp_name'];
    $vehiclePicture = $_FILES['vehiclePicture']['name'];
    $vehiclePictureTmp = $_FILES['vehiclePicture']['tmp_name'];

    // Retrieve form data for van table
    $plateNumber = $conn->real_escape_string($_POST["plateNumber"]);
    $vehicleMake = $conn->real_escape_string($_POST["vehicleMake"]);
    $vehicleModel = $conn->real_escape_string($_POST["vehicleModel"]);
    $vehicleYear = $conn->real_escape_string($_POST["vehicleYear"]);
    $vehicleCapacity = $conn->real_escape_string($_POST["vehicleCapacity"]);

    // Validate plate number
    if (!validatePlateNumber($plateNumber)) {
        $errors[] = "Plate number must be 7 characters long.";
        $errorFields[] = "plateNumber";
    }

    // Validate year
    if (!validateYear($vehicleYear)) {
        $errors[] = "Year must be a 4-digit number.";
        $errorFields[] = "vehicleYear";
    }

    $destinationFolder = 'uploads/validids/';
    $validIDUpload = uploadFile('validID', $destinationFolder);
    $validIDPath = $destinationFolder . $validIDUpload['fileName'];

    $destinationFolder = 'uploads/certificates/';
    $crUpload = uploadFile('ltoCertificate', $destinationFolder);
    $crPath = $destinationFolder . $crUpload['fileName'];

    $destinationFolder = 'uploads/receipts/';
    $orUpload = uploadFile('officialReceipt', $destinationFolder);
    $orPath = $destinationFolder . $orUpload['fileName'];

    $destinationFolder = 'uploads/van_photos/';
    $vanPhotoUpload = uploadFile('vehiclePicture', $destinationFolder);
    $vanPhotoPath = $destinationFolder . $vanPhotoUpload['fileName'];

    
    // If no errors, proceed with registration
    if (empty($errors)) {

        $query = "INSERT INTO owner_valid_id (Owner_ID, O_ValidID) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $owner['Owner_ID'], $validIDPath);
        $stmt->execute();

        // Insert the van data into the van table
        $query = "INSERT INTO van (V_PlateNo, V_Make, V_Model, V_Year, V_Capacity, Owner_ID) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssiii", $plateNumber, $vehicleMake, $vehicleModel, $vehicleYear, $vehicleCapacity, $owner['Owner_ID']);
        $stmt->execute();

        $vanID = $stmt->insert_id;

        $query = "INSERT INTO van_document (Van_ID, V_OR, V_CR) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $vanID, $orPath, $crPath);
        $stmt->execute();

        $query = "INSERT INTO van_photo (Van_ID, V_Photo) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $vanID, $vanPhotoPath);
        $stmt->execute();

        // Redirect to success page
        header("Location: ../signin/user-signin.php");
    
        session_unset();
        session_destroy();

        exit();
    }
}

// Close database connection
$conn->close();

function validatePlateNumber($value)
{
    return preg_match('/^[a-zA-Z0-9\s!@#$%^&*()]+$/u', $value) && strlen($value) === 7;
}

function validateYear($value)
{
    return preg_match('/^\d{4}$/', $value);
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

    // Check file type
    $allowedFileTypes = ['image/jpeg', 'image/png'];
    $fileType = mime_content_type($_FILES[$fileField]['tmp_name']);
    if (!in_array($fileType, $allowedFileTypes)) {
        $errors[] = "Only " . implode(", ", $allowedFileTypes) . " file types are allowed.";
        $errorFields[] = $fileField;
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

?>




<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css"
      rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="user-signup-style.css" />
    <title>Web Design Mastery | Pathway</title>
  </head>
  <body>
    <!-- NAVIGATION BAR -->
	<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
		<div class="container">
			<a class="navbar-brand" href="#"><span class="text-info">Van</span>Go</a> <button aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-bs-target="#navbarSupportedContent" data-bs-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav ms-auto mb-2 mb-lg-0">
					<li class="nav-item">
						<a class="nav-link" href="#">Home</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#about">About</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#services">Services</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
    <!-- SIGN UP FORM -->
    <header>
        <section class="my-100 h-custom gradient-custom-2">
            <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12">
                <div class="card card-registration card-registration-2" style="border-radius: 15px;">
                    <div class="card-body p-0">
                    
                        <div class="row g-0">
                        <div class="col-lg-6">
                            <div class="p-5">

                            <h3 class="fw-normal mb-5" style="color: #4835d4;">Owner Information</h3>

                            <?php if (!empty($errors)) : ?>
                                <ul class="error" style="color: red;" >
                                    <?php foreach ($errors as $error) : ?>
                                        <li><?php echo $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        <form action="owner-signup.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-4">
                                <div class="form-outline form-white">
                                <input class="form-control form-control-lg" type="file" name="validID" <?php if (isset($_FILES['validID']) && $_FILES['validID']['error'] !== UPLOAD_ERR_NO_FILE) {
                                                                                                            echo ' value="selected"';
                                                                                                        } ?> required/>
                                <div class="small text-muted mt-2">Upload your Valid ID. Max file size 50 MB</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4 pb-2">
                                <div class="form-outline form-white">
                                    <input type="text" class="form-control form-control-lg" name="plateNumber" value="<?php echo isset($_POST['plateNumber']) ? $_POST['plateNumber'] : ''; ?>" required/>
                                    <label class="form-label" for="plateNumber">Plate Number</label>
                                </div>
                                </div>
                                <div class="col-md-6 mb-4 pb-2">
                                <div class="form-outline form-white">
                                    <input type="text" class="form-control form-control-lg" name="vehicleCapacity" value="<?php echo isset($_POST['vehicleCapacity']) ? $_POST['vehicleCapacity'] : ''; ?>" required/>
                                    <label class="form-label" for="vehicleCapacity">Vehicle Capacity</label>
                                </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4 pb-2">
                                <div class="form-outline form-white">
                                    <input type="text" class="form-control form-control-lg" name="vehicleYear" value="<?php echo isset($_POST['vehicleYear']) ? $_POST['vehicleYear'] : ''; ?>" required/>
                                    <label class="form-label" for="vehicleYear">Vehicle Year</label>
                                </div>
                                </div>
                                <div class="col-md-6 mb-4 pb-2">
                                <div class="form-outline form-white">
                                    <input type="text" class="form-control form-control-lg" name="vehicleMake" value="<?php echo isset($_POST['vehicleMake']) ? $_POST['vehicleMake'] : ''; ?>" required/>
                                    <label class="form-label" for="vehicleMake">Vehicle Make</label>
                                </div>
                                </div>
                            </div>

                            <div class="mb-4 pb-2">
                                <div class="form-outline form-white">
                                <input type="text" class="form-control form-control-lg" name="vehicleModel" value="<?php echo isset($_POST['vehicleModel']) ? $_POST['vehicleModel'] : ''; ?>" required/>
                                <label class="form-label" for="vehicleModel">Vehicle Model</label>
                                </div>
                            </div>
                            </div>
                        </div>

                        <div class="col-lg-6 bg-indigo text-white">
                            <div class="p-5">
                            <div class="mb-4">
                                <div class="form-outline form-white">
                                <label class="form-label" for="ltoCertificate">LTO Certificate of Registration</label>
                                <input class="form-control form-control-lg" type="file" name="ltoCertificate" <?php if (isset($_FILES['ltoCertificate']) && $_FILES['ltoCertificate']['error'] !== UPLOAD_ERR_NO_FILE) {
                                                                                                                    echo ' value="selected"';
                                                                                                                } ?> required/>
                                <div class="small text-white">(Max file size 50 MB)</div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="form-outline form-white">
                                <label class="form-label" for="officialReceipt">Official Receipt of Certificate of Registration</label>
                                <input class="form-control form-control-lg" id="formFileLg" type="file" name="officialReceipt" <?php if (isset($_FILES['officialReceipt']) && $_FILES['officialReceipt']['error'] !== UPLOAD_ERR_NO_FILE) {
                                                                                                                                    echo ' value="selected"';
                                                                                                                                } ?> required/>
                                <div class="small text-white">(Max file size 50 MB)</div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-outline form-white">
                                <label class="form-label" for="vehiclePicture">Vehicle Picture</label>
                                <input class="form-control form-control-lg" id="formFileLg" type="file" name="vehiclePicture" <?php if (isset($_FILES['vehiclePicture']) && $_FILES['vehiclePicture']['error'] !== UPLOAD_ERR_NO_FILE) {
                                                                                                                                    echo ' value="selected"';
                                                                                                                                } ?> required/>
                                <div class="small text-white">(Max file size 50 MB)</div>
                                </div>
                            </div>

                            <div class="form-check d-flex justify-content-start mb-4 pb-3">
                                <input class="form-check-input me-3" type="checkbox" value="" id="form2Example3c" required/>
                                <label class="form-check-label text-white" for="form2Example3">
                                I accept the <a href="#!" class="text-white"><u>Terms and Conditions.</u></a>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-light btn-lg" data-mdb-ripple-color="dark">Register</button>
                            </div>
                        </div>
                        </form>
                        </div>
                    
                    </div>
                </div>
                </div>
            </div>
            </div>
        </section>
    </header>

    
    <footer class="footer">
      <div class="section__container footer__container">
        <div class="footer__col">
          <h3>VanGo</h3>
          <p>
            Van on the Go! Rent a van anytime, anywhere, wherever. 
          </p>
        </div>
        <div class="footer__col">
          <h4>Support</h4>
          <p>FAQs</p>
          <p>Terms & Conditions</p>
          <p>Privacy Policy</p>
          <p>Contact Us</p>
        </div>
        <div class="footer__col">
          <h4>Address</h4>
          <p>
            <span>Address:</span> 456 Sta. Mesa, Manila 1000, Philippines
          </p>
          <p><span>Email:</span> info@vango.com</p>
          <p><span>Phone:</span> +68 9876543210</p>
        </div>
      </div>
      <div class="footer__bar">
        Copyright © 2023 VanGo. All rights reserved.
      </div>
    </footer>
    <!-- JAVASCRIPT -->
	<script src="https://kit.fontawesome.com/c08dde9054.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>