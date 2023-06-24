<?php

require_once 'db_connect.php';

$email = $password = '';
$emailErr = $passwordErr = '';
$notice = '';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = $mysqli->real_escape_string($_POST["email"]);
    }

    // Validate password
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = $mysqli->real_escape_string($_POST["password"]);
    }

    // If there are no validation errors
    if (empty($emailErr) && empty($passwordErr)) {
        // Query the user table to check if the provided email matches a user record
        $query = "SELECT * FROM user WHERE User_Email=?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // If a user record is found
        if ($result->num_rows === 1) {
            // Fetch the user record
            $row = $result->fetch_assoc();

            // Query the password table to retrieve the password data
            $query = "SELECT * FROM password WHERE User_Email=?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            // If a password record is found
            if ($result->num_rows === 1) {
                // Fetch the password record
                $passwordRow = $result->fetch_assoc();

                // Verify the password
                $hashPassword = $passwordRow['Hash_Password'];
                $saltPassword = $passwordRow['Salt_Password'];
                $enteredHash = hash('sha256', $password . $saltPassword);

                // If the password matches
                if ($hashPassword === $enteredHash) {
                    // Start the session and set session variables
                    session_start();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['email'] = $email;

                    // Redirect based on the user type
                    if ($row['User_Type'] === 'customer') {
                        header('Location: ../customer-dashboard.php');
                        exit();
                    } elseif ($row['User_Type'] === 'owner') {
                        header('Location: ../owner-dashboard.php');
                        exit();
                    }
                } else {
                    // Invalid password
                    $notice = "Incorrect password";
                }
            } else {
                // Invalid email or password
                $notice = "Incorrect email or password";
            }
        } else {
            // Invalid email or password
            $notice = "Incorrect email or password";
        }

        // Close the prepared statement
        $stmt->close();
    }
}

// Close the database connection
$mysqli->close();
