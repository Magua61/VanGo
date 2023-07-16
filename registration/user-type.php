<?php

require_once '../db_connect.php';
session_start();

$fName = $_SESSION['fName'];
$mName = $_SESSION['mName'];
$lName = $_SESSION['lName'];
$gender = $_SESSION['gender'];
$address = $_SESSION['address'];
$birthdate = $_SESSION['birthdate'];
$phoneNo = $_SESSION['phoneNo'];
$email = $_SESSION['email'];
$salt = $_SESSION['salt'];
$hashedPassword = $_SESSION['hashedPassword'];


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accountType = $_POST["account_type"];

    // Insert the data into the user table
    $query = "INSERT INTO user (User_Email, User_Type) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $accountType);
    $stmt->execute();

    // Insert the password data into the password table
    $query = "INSERT INTO password (User_Email, Hash_Password, Salt_Password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $email, $hashedPassword, $salt);
    $stmt->execute();

    if ($accountType === "customer") {
        // Insert the information into the customer table
        $query = "INSERT INTO customer (C_FName, C_MName, C_LName, C_Gender, C_Address, C_Birthdate, C_Email, C_PhoneNo) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssss", $fName, $mName, $lName, $gender, $address, $birthdate, $email, $phoneNo);
        $stmt->execute();

        // TEMPORARY
        header("Location: ../signin/user-signin.php");

        // Destroy the session
        session_unset();
        session_destroy();
        
        exit();

    } elseif ($accountType === "owner") {
        // Insert the  information into the owner table
        $query = "INSERT INTO owner (O_FName, O_MName, O_LName, O_Gender, O_Address, O_Birthdate, O_Email, O_PhoneNo)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssss", $fName, $mName, $lName, $gender, $address, $birthdate, $email, $phoneNo);
        $stmt->execute();

        header("Location: owner-signup.php");
        exit();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Account</title>
    <link rel="stylesheet" type="text/css" href="user-type.css">
</head>
<body>
    <h2>Create Account</h2>
    <form action="user-type.php" method="POST">
        <label for="account_type">Account Type:</label><br>
        <input type="radio" id="customer" name="account_type" value="customer">
        <label for="customer">Customer</label><br>
        <input type="radio" id="owner" name="account_type" value="owner">
        <label for="owner">Owner</label><br><br>
        
        <input type="submit" value="Create Account">
    </form>
</body>
</html>
