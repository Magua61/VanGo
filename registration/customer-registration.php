<?php
require_once '../db_connect.php';

// Define the error variable
$errors = [];
$errorFields = [];

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $fname = $conn->real_escape_string($_POST["fname"]);
    $mname = $conn->real_escape_string($_POST["mname"]);
    $lname = $conn->real_escape_string($_POST["lname"]);
    $address = $conn->real_escape_string($_POST["address"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $phone = $conn->real_escape_string($_POST["phone"]);
    $password = $conn->real_escape_string($_POST["password"]);
    $profilePic = $_FILES['profilepic']['name'];
    $profilePicTmp = $_FILES['profilepic']['tmp_name'];

    // Validate password
    if (!validateAlphanumericLength($password, 8, 32)) {
        $errors[] = "Password must be between 8 and 32 alphanumeric characters.";
        $errorFields[] = "password";
    }

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

    // Check file size
    $maxFileSize = 2 * 1024 * 1024; // 2MB 
    if ($_FILES['profilepic']['size'] > $maxFileSize) {
        $errors[] = "File size exceeds the maximum limit of 2MB.";
        $errorFields[] = "profilepic";
    }

    // Check file type
    $allowedFileTypes = ['image/jpeg', 'image/png'];
    $fileType = mime_content_type($profilePicTmp);
    if (!in_array($fileType, $allowedFileTypes)) {
        $errors[] = "Only JPEG and PNG file types are allowed.";
        $errorFields[] = "profilepic";
    }

    // Generate a unique filename
    $profilePicFilename = uniqid() . '_' . $profilePic;

    // Move the uploaded file to a desired location
    $destination = 'uploads/profiles/' . $profilePicFilename;
    if (!move_uploaded_file($profilePicTmp, $destination)) {
        $errors[] = "Error occurred while uploading the file. Please try again later.";
        $errorFields[] = "profilepic";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Generate a salt
        $salt = bin2hex(random_bytes(16));

        // Hash the password with the salt
        $hashedPassword = hash('sha256', $password . $salt);

        // Insert the customer data into the user table
        $query = "INSERT INTO user (User_Email, User_Type) VALUES (?, 'customer')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // Insert the customer information into the customer table
        $query = "INSERT INTO customer (C_FName, C_MName, C_LName, C_Address, C_Email, C_PhoneNo, C_ProfilePic) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssss", $fname, $mname, $lname, $address, $email, $phone, $destination);
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
function validateAlphanumericLength($value, $minLength, $maxLength)
{
    return preg_match('/^[a-zA-Z0-9!@#$%^&*()\-_]+$/', $value) && strlen($value) >= $minLength && strlen($value) <= $maxLength;
}


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
?>

<!DOCTYPE html>
<html>

<head>
    <title>Registration Form</title>
    <link rel="stylesheet" type="text/css" href="customer-registration-style.css">
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
        <form method="post" action="customer-registration.php" name="registrationForm" enctype="multipart/form-data">

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
            <input type="password" name="password" required><br>

            <input type="submit" value="Register">
        </form>
    </div>
</body>

</html>