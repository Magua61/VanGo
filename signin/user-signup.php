<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $accountType = $_POST["account_type"];
    if ($accountType === "customer") {
        header("Location: ../registration/customer-registration.php");
        exit();
    } elseif ($accountType === "owner") {
        header("Location: ../registration/owner-registration.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Account</title>
    <link rel="stylesheet" type="text/css" href="user-signup.css">
</head>
<body>
    <h2>Create Account</h2>
    <form action="user-signup.php" method="POST">
        <label for="account_type">Account Type:</label><br>
        <input type="radio" id="customer" name="account_type" value="customer">
        <label for="customer">Customer</label><br>
        <input type="radio" id="owner" name="account_type" value="owner">
        <label for="owner">Owner</label><br><br>
        
        <input type="submit" value="Create Account">
    </form>
</body>
</html>
