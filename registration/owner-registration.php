<?php
require_once '../db_connect.php';

// Define the error variable
$errors = [];
$errorFields = [];

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data for owner table
    $fname = $conn->real_escape_string($_POST["fname"]);
    $mname = $conn->real_escape_string($_POST["mname"]);
    $lname = $conn->real_escape_string($_POST["lname"]);
    $address = $conn->real_escape_string($_POST["address"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $phone = $conn->real_escape_string($_POST["phone"]);
    $password = $conn->real_escape_string($_POST["password"]);
    $profilePic = $_FILES['profilepic']['name'];
    $profilePicTmp = $_FILES['profilepic']['tmp_name'];
    $validID = $_FILES['validid']['name'];
    $validIDTmp = $_FILES['validid']['tmp_name'];

    // Retrieve form data for van table
    $plateNo = $conn->real_escape_string($_POST["plateNo"]);
    $make = $conn->real_escape_string($_POST["make"]);
    $model = $conn->real_escape_string($_POST["model"]);
    $year = $conn->real_escape_string($_POST["year"]);
    $capacity = $conn->real_escape_string($_POST["capacity"]);
    $cr = $_FILES['cr']['name'];
    $crTmp = $_FILES['cr']['tmp_name'];
    $vanPhoto = $_FILES['vanphoto']['name'];
    $vanPhotoTmp = $_FILES['vanphoto']['tmp_name'];

    // Validate phone number
    if (!validatePhoneNumber($phone)) {
        $errors[] = "Phone number must start with '09' and have a total of 11 digits.";
        $errorFields[] = "phone";
    }

    // Check if the email already exists in the database
    if (checkEmailExists($email)) {
        $errors[] = "Email already exists. Please choose a different email.";
        $errorFields[] = "email";
    }

    // Validate plate number
    if (!validatePlateNumber($plateNo)) {
        $errors[] = "Plate number must be 7 characters long.";
        $errorFields[] = "plateNo";
    }

    // Validate year
    if (!validateYear($year)) {
        $errors[] = "Year must be a 4-digit number.";
        $errorFields[] = "year";
    }

    $destinationFolder = 'uploads/profiles/';
    $profilePicUpload = uploadFile('profilepic', $destinationFolder);
    $profilePicPath = $destinationFolder . $profilePicUpload['fileName'];

    $destinationFolder = 'uploads/validids/';
    $validIDUpload = uploadFile('validid', $destinationFolder);
    $validIDPath = $destinationFolder . $validIDUpload['fileName'];

    $destinationFolder = 'uploads/certificates/';
    $crUpload = uploadFile('cr', $destinationFolder);
    $crPath = $destinationFolder . $crUpload['fileName'];

    $destinationFolder = 'uploads/van_photos/';
    $vanPhotoUpload = uploadFile('vanphoto', $destinationFolder);
    $vanPhotoPath = $destinationFolder . $vanPhotoUpload['fileName'];



    // If no errors, proceed with registration
    if (empty($errors)) {
        // Generate a salt
        $salt = bin2hex(random_bytes(16));

        // Hash the password with the salt
        $hashedPassword = hash('sha256', $password . $salt);

        // Insert the owner data into the user table
        $query = "INSERT INTO user (User_Email, User_Type) VALUES (?, 'owner')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // Insert the owner data into the owner table
        $query = "INSERT INTO owner (O_FName, O_MName, O_LName, O_Address, O_Email, O_PhoneNo, O_ProfilePic, O_ValidID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssss", $fname, $mname, $lname, $address, $email, $phone, $profilePicPath, $validIDPath);
        $stmt->execute();

        // Get the last inserted owner ID
        $ownerId = $stmt->insert_id;

        // Insert the van data into the van table
        $query = "INSERT INTO van (V_PlateNo, V_Make, V_Model, V_Year, V_Capacity, V_CR, V_Photo, Owner_ID) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssiissi", $plateNo, $make, $model, $year, $capacity, $crPath, $vanPhotoPath, $ownerId);
        $stmt->execute();

        // Insert the password data into the password table
        $query = "INSERT INTO password (User_Email, Hash_Password, Salt_Password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $email, $hashedPassword, $salt);
        $stmt->execute();

        // Redirect to success page
        header("Location: ../signin/user-signin.php");
        
        exit();
    }
}

// Close database connection
$conn->close();

// Validation functions
function validatePhoneNumber($value)
{
    return preg_match('/^09\d{9}$/', $value);
}

function checkEmailExists($email)
{
    global $conn;

    $query = "SELECT * FROM user WHERE User_Email=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->num_rows > 0;
}

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
    $maxFileSize = 2 * 1024 * 1024; // 2MB
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
<html>

<head>
    <title>Registration Form</title>
    <link rel="stylesheet" type="text/css" href="owner-registration-style.css">
    <style>
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Registration Form</h2>
        <?php if (!empty($errors)) : ?>
            <ul class="error">
                <?php foreach ($errors as $error) : ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <form method="post" action="owner-registration.php" name="registrationForm" enctype="multipart/form-data">

            <label for="profilepic">Profile Picture:</label>
            <input type="file" name="profilepic" <?php if (isset($_FILES['profilepic']) && $_FILES['profilepic']['error'] !== UPLOAD_ERR_NO_FILE) {
                                                        echo ' value="selected"';
                                                    } ?> required><br>

            <label for="fname">First Name:</label>
            <input type="text" name="fname" value="<?php echo isset($_POST['fname']) ? $_POST['fname'] : ''; ?>" required><br>

            <label for="mname">Middle Name:</label>
            <input type="text" name="mname" value="<?php echo isset($_POST['mname']) ? $_POST['mname'] : ''; ?>" required><br>

            <label for="lname">Last Name:</label>
            <input type="text" name="lname" value="<?php echo isset($_POST['lname']) ? $_POST['lname'] : ''; ?>" required><br>

            <label for="address">Address:</label>
            <input type="text" name="address" placeholder="[House/Building Number], [Street Name], [Subdivision/Village], [Barangay/District], [City/Municipality], [Province]" value="<?php echo isset($_POST['address']) ? $_POST['address'] : ''; ?>" required><br>

            <label for="email">Email Address:</label>
            <input type="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required><br>

            <label for="phone">Phone Number:</label>
            <input type="tel" name="phone" placeholder="09*********" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>" required><br>

            <label for="password">Password:</label>
            <input type="password" placeholder="8 - 32 alphanumeric and special characters" name="password" required><br>

            <label for="validid">Valid ID:</label>
            <input type="file" name="validid" <?php if (isset($_FILES['validid']) && $_FILES['validid']['error'] !== UPLOAD_ERR_NO_FILE) {
                                                    echo ' value="selected"';
                                                } ?> required><br>

            <label for="plateNo">Plate Number:</label>
            <input type="text" name="plateNo" value="<?php echo isset($_POST['plateNo']) ? $_POST['plateNo'] : ''; ?>" required><br>

            <label for="make">Vehicle Make:</label>
            <input type="text" name="make" placeholder="Toyota" value="<?php echo isset($_POST['make']) ? $_POST['make'] : ''; ?>" required><br>

            <label for="model">Vehicle Model:</label>
            <input type="text" name="model" placeholder="Hiace" value="<?php echo isset($_POST['model']) ? $_POST['model'] : ''; ?>" required><br>

            <label for="year">Vehicle Year:</label>
            <input type="text" name="year" placeholder="2019" value="<?php echo isset($_POST['year']) ? $_POST['year'] : ''; ?>" required><br>

            <label for="capacity">Vehicle Capacity:</label>
            <input type="text" name="capacity" placeholder="2019" value="<?php echo isset($_POST['capacity']) ? $_POST['capacity'] : ''; ?>" required><br>

            <label for="cr">LTO Certificate of Registration:</label>
            <input type="file" name="cr" <?php if (isset($_FILES['cr']) && $_FILES['cr']['error'] !== UPLOAD_ERR_NO_FILE) {
                                                echo ' value="selected"';
                                            } ?> required><br>

            <label for="vanphoto">Vehicle Photo:</label>
            <input type="file" name="vanphoto" <?php if (isset($_FILES['vanphoto']) && $_FILES['vanphoto']['error'] !== UPLOAD_ERR_NO_FILE) {
                                                    echo ' value="selected"';
                                                } ?> required><br>

            <input type="submit" value="Submit">
        </form>
    </div>
</body>

</html>