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
      rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="signin-style.css" />
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
  <!-- HEADER SECTION-->
    <header>
      <section class="h-100 gradient-form" style="background-color: #eee;">
        <div class="container py-5 h-100">
          <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-xl-10">
              <div class="card rounded-3 text-black">
                <div class="row g-0">
                  <div class="col-lg-6">
                    <div class="card-body p-md-5 mx-md-4">
      
                      <div class="text-center">
                        <h4 class="mt-1 mb-5 pb-1">VanGo</h4>
                      </div>
                    <?php if (!empty($errors)) : ?>
                        <ul class="error" style="color: red;" >
                            <?php foreach ($errors as $error) : ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <p>Please login to your account</p>
                    <form action="user-signin.php" method="POST">
                      <div class="form-outline mb-4">
                        <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>"/>
                      </div>
                      <div class="form-outline mb-4">
                        <input type="password" name="password" class="form-control" placeholder="Password"/>
                      </div>
                      <div class="text-center pt-1 mb-4">
                        <button class="btn fa-lg mb-3 action_btn" name="loginBtn" type="submit">Log in</button><br>
                      </div>
                    </form>

                        <div class="text-center pt-1 mb-4">
                            <a class="text-muted" href="#">Forgot password?</a>
                        </div>
                        <div class="d-flex align-items-center justify-content-center pb-4">
                            <p class="mb-0 me-2">Don't have an account?</p>
                            <button type="button" class="btn btn-outline-info" onclick="redirectToSignup()">Create new</button>
                    </div>
                    </div>
                  </div>
                  <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                    <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                      <h4 class="mb-4">Van on the go! Anytime, anywhere, wherever.</h4>
                      <p class="small mb-0">Make your trip in just a click. We are the best van rental service
                        here in the Philippines. We provide cheap, fast, and reliable vans
                        as we support local van owners by giving them a platform to rent their vans! </p>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </header>
    
    <!-- FOOTER -->
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
  <script>
      function redirectToSignup() {
        window.location.href = "../registration/user-signup.php";
      }
  </script>

  </body>
</html>