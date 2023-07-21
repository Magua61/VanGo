<?php

require_once '../db_connect.php';
session_start();

// Define the error variable
$errors = [];
$errorFields = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form values and escape them
    $fName = $conn->real_escape_string($_POST['fName']);
    $mName = $conn->real_escape_string($_POST['mName']);
    $lName = $conn->real_escape_string($_POST['lName']);
    $gender = $conn->real_escape_string($_POST['inlineRadioOptions']);
    $address = $conn->real_escape_string($_POST['address']);
    $birthdate = $conn->real_escape_string($_POST['birthdate']);
    $phoneNo = $conn->real_escape_string($_POST['phoneNo']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirmPassword = $conn->real_escape_string($_POST['confirmPassword']);

      // Validate password
    if (!validateAlphanumericLength($password, 8, 32)) {
        $errors[] = "Password must be between 8 and 32 alphanumeric characters.";
        $errorFields[] = "password";
    }

    // Validate phone number
    if (!validatePhoneNumber($phoneNo)) {
        $errors[] = "Phone number must start with '09' and have a total of 11 digits.";
        $errorFields[] = "phone";
    }

    // Check if the email already exists in the database
    if (checkEmailExists($email)) {
        $errors[] = "Email already exists. Please choose a different email.";
        $errorFields[] = "email";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Password and Confirm Password must match.";
        $errorFields[] = "confirmPassword";
    }

    if (empty($errors)) {

      $salt = bin2hex(random_bytes(16));

        // Hash the password with the salt
      $hashedPassword = hash('sha256', $password . $salt);
        
      $_SESSION['fName'] = $fName;
      $_SESSION['mName'] = $mName;
      $_SESSION['lName'] = $lName;
      $_SESSION['gender'] = $gender;
      $_SESSION['address'] = $address;
      $_SESSION['birthdate'] = $birthdate;
      $_SESSION['phoneNo'] = $phoneNo;
      $_SESSION['email'] = $email;
      $_SESSION['salt'] = $salt;
      $_SESSION['hashedPassword'] = $hashedPassword;

      header("Location: user-type.php");
      exit();
    }

}

$conn->close();

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
						<a class="nav-link" href="../index.php#">Home</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="../index.php#about">About</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="../index.php#services">Services</a>
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
                        <h3 class="fw-normal mb-5" style="color: #4835d4;">Sign up</h3>
                        <?php if (!empty($errors)) : ?>
                            <ul class="error" style="color: red;" >
                                <?php foreach ($errors as $error) : ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <form action="user-signup.php" method="POST">
                          <div class="row">
                            <div class="col-md-6 mb-4 pb-2">
                              <div class="form-outline">
                                <input type="text" name="fName" class="form-control form-control-lg" placeholder="Juan" value="<?php echo isset($_POST['fName']) ? $_POST['fName'] : ''; ?>" required />
                                <label class="form-label" for="fName">First name</label>
                              </div>
                            </div>
                            <div class="col-md-6 mb-4 pb-2">
                              <div class="form-outline">
                                <input type="text" name="mName" class="form-control form-control-lg" placeholder="Dela" value="<?php echo isset($_POST['mName']) ? $_POST['mName'] : ''; ?>" required/>
                                <label class="form-label" for="mName">Middle name</label>
                              </div>
                            </div>
                            <div class="col-md-12 mb-4 pb-2">
                              <div class="form-outline">
                                <input type="text" name="lName" class="form-control form-control-lg" placeholder="Cruz" value="<?php echo isset($_POST['lName']) ? $_POST['lName'] : ''; ?>" required />
                                <label class="form-label" for="lName">Last name</label>
                              </div>
                            </div>
                          </div>

                          <div class="d-md-flex justify-content-start align-items-center mb-4 pb-2">
                          <h6 class="mb-0 me-4">Gender: </h6>
                          <div class="form-check form-check-inline mb-0 me-4">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="femaleGender" value="Female" <?php echo isset($_POST['inlineRadioOptions']) && $_POST['inlineRadioOptions'] === 'option1' ? 'checked' : ''; ?> required/>
                            <label class="form-check-label" for="femaleGender">Female</label>
                          </div>
                          <div class="form-check form-check-inline mb-0 me-4">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="maleGender" value="Male" <?php echo isset($_POST['inlineRadioOptions']) && $_POST['inlineRadioOptions'] === 'option2' ? 'checked' : ''; ?> />
                            <label class="form-check-label" for="maleGender">Male</label>
                          </div>
                          <div class="form-check form-check-inline mb-0">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="otherGender" value="Other" <?php echo isset($_POST['inlineRadioOptions']) && $_POST['inlineRadioOptions'] === 'option3' ? 'checked' : ''; ?> />
                            <label class="form-check-label" for="otherGender">Other</label>
                          </div>
                          </div>

                          <div class="mb-4 pb-2">
                            <div class="form-outline form-white">
                              <input type="text" name="address" class="form-control form-control-lg" placeholder="12 Mango St. San Juan.." value="<?php echo isset($_POST['address']) ? $_POST['address'] : ''; ?>" required/>
                              <label class="form-label" for="address">Address</label>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-5 mb-4 pb-2">
                              <div class="form-outline form-white">
                                <input type="date" name="birthdate" class="form-control form-control-lg" value="<?php echo isset($_POST['birthdate']) ? $_POST['birthdate'] : ''; ?>" required />
                                <label class="form-label" for="birthdate">Birthday</label>
                              </div>
                            </div>
                            <div class="col-md-7 mb-4 pb-2">
                              <div class="form-outline form-white">
                                <input type="text" name="phoneNo" class="form-control form-control-lg" placeholder="09123456789" value="<?php echo isset($_POST['phoneNo']) ? $_POST['phoneNo'] : ''; ?>" required />
                                <label class="form-label" for="phoneNo">Phone</label>
                              </div>
                            </div>
                          </div>


                         <!-- Account Details -->
                          </div>
                        </div>
                        <div class="col-lg-6 bg-indigo text-white">
                          <div class="p-5">
                            <h3 class="fw-normal mb-5 text-white">Account</h3>

                            <div class="mb-4 pb-2">
                              <div class="form-outline form-white">
                                <input type="text" name="email" class="form-control form-control-lg" placeholder="juan@example.com" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required/>
                                <label class="form-label" for="email">Your Email</label>
                              </div>
                            </div>

                            <div class="mb-4">
                              <div class="form-outline form-white">
                                <input type="password" name="password" class="form-control form-control-lg" required/>
                                <label class="form-label" for="password">Password</label>
                              </div>
                            </div>

                            <div class="mb-4">
                              <div class="form-outline form-white">
                                <input type="password" name="confirmPassword" class="form-control form-control-lg" required/>
                                <label class="form-label" for="confirmPassword">Confirm Password</label>
                              </div>
                            </div>
          
                            <div class="form-check d-flex justify-content-start mb-4 pb-3">
                              <input class="form-check-input me-3" type="checkbox" value="" id="form2Example3c" required/>
                              <label class="form-check-label text-white" for="form2Example3">
                                I accept the <a href="#!" class="text-white"><u>Terms and Conditions.</u></a></label>
                            </div>
          
                            <button type="submit" class="btn btn-light btn-lg"
                              data-mdb-ripple-color="dark">Register</button>
          
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