<?php

require_once '../db_connect.php';

// Define the error variable
$errors = [];
$errorFields = [];

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['loginBtn'])) {
        // Retrieve form data for password table
        $email = $conn->real_escape_string($_POST["email"]);
        $password = $conn->real_escape_string($_POST["password"]);
    }
    
    // Validate email
    if (empty($_POST["email"])) {
        $errors[] = "Email address is required";
        $errorFields[] = "email";
    }

    // Validate password
    if (empty($_POST["password"])) {
        $errors[] = "Password is required";
        $errorFields[] = "password";
    }
    

    // If there are no validation errors
    if (empty($errors)) {
        // Query the user table to check if the provided email matches a user record
        $query = "SELECT * FROM user WHERE User_Email=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // If a user record is found
        if ($result->num_rows === 1) {
            // Fetch the user record
            $row = $result->fetch_assoc();

            // Query the password table to retrieve the password data
            $query = "SELECT * FROM password WHERE User_Email=?";
            $stmt = $conn->prepare($query);
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
                        //header('Location: ../customer-side/customer-dashboard.php');
                        header('Location: ../customer-side/user-index.php');
                        exit();
                    } elseif ($row['User_Type'] === 'owner') {
                        header('Location: ../owner-side/owner-dashboard.php');
                        exit();
                    }
                } else {
                    $errors[] = "Incorrect password";
                }
            } else {
                $errors[] = "Incorrect email or password";
            }
        } else {
            $errors[] = "Incorrect email or password";
        }

        // Close the prepared statement
        $stmt->close();
    }
    
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../user-signup-style.css" />
    <title>Web Design Mastery | Pathway</title>
  </head>
  <body>
    <nav>
      <div class="nav__logo">VanGo</div>
      <ul class="nav__links">
        <li class="link"><a href="index.html">Home</a></li>
        <li class="link"><a href="#">About</a></li>
        <li class="link"><a href="#">Rent-A-Van</a></li>
        <li class="link"><a href="#">Be-A-Driver</a></li>
      </ul>
      <button class="btn"><a href="user-signup.html">Sign in</a></button>
    </nav>
    <header>
      <div class="section__container header__container">
        <div class="header__content">
          <div class="header__details">
            <h1>Van on the go! 
                Anytime, anywhere, wherever.</h1>
            <p class="section__subtitle">
              Make your trip in just a click. We are the best van rental service
              here in the Philippines. We provide cheap, fast, and reliable vans
              as we support local van owners by giving them a platform to rent their vans! 
            </p>
          </div>
        </div>
        
          <div class="form">
            <h2>Sign in</h2>
            <?php if (!empty($errors)) : ?>
                <ul class="error">
                    <?php foreach ($errors as $error) : ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <form action="user-signin.php" method="POST">
              <input type="email" name="email" placeholder="Enter Email Here" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
              <input type="password" name="password" placeholder="Enter Password Here">
              <button type="submit" name="loginBtn" class="btn form__btn">Login</button>
            </form>

            <p class="link">Don't have an account?<br>
            <a href="user-signup.php">Sign up</a>here</p>
            <p class="liw">Log in with</p>

            <div class="icons">
                <a href="#"><ion-icon name="logo-facebook"></ion-icon></a>
                <a href="#"><ion-icon name="logo-instagram"></ion-icon></a>
                <a href="#"><ion-icon name="logo-twitter"></ion-icon></a>
                <a href="#"><ion-icon name="logo-google"></ion-icon></a>
                <a href="#"><ion-icon name="logo-skype"></ion-icon></a>
            </div>
        </div>
<script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
        
      </div>
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
  </body>
</html>