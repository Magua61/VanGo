<?php

require_once '../db_connect.php';
session_start();

$errors = [];
$errorFields = [];

$query = "SELECT C.Customer_ID, C_FName, C_MName, C_LName, C_Gender, C_Address, C_Birthdate, C_Email, C_PhoneNo, C_ProfilePic, Hash_Password, Salt_Password, User_RegiDatetime
          FROM customer C 
          LEFT JOIN customer_profile CP
            ON C.Customer_ID = CP.Customer_ID
          JOIN user U
            ON C.C_Email = U.User_Email
          JOIN password P
            ON U.User_Email = P.User_Email
          WHERE C.Customer_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['customerid']);
$stmt->execute();
$result = $stmt->get_result();

$customer = $result->fetch_assoc();

$query = "SELECT R.Rental_ID, V.Van_ID, R.Customer_ID, V_Photo, concat_ws(' ', V_Make, V_Model, V_Year) as 'V_Name', V_Capacity, 
            concat_ws(' ', O_FName, O_LName) as 'O_FullName', O_Address, O_PhoneNo, V_PlateNo, Pickup_Date, Pickup_Time, Return_Date, Return_Time, Payment_Amount, Rental_Status
          FROM owner O JOIN van V ON
            O.Owner_ID = V.Owner_ID
          LEFT JOIN van_rate VR ON
            V.Van_ID = VR.Van_ID
          LEFT JOIN van_photo VP ON
            V.Van_ID = VP.Van_ID
          JOIN rental R ON
            V.Van_ID = R.Van_ID
          JOIN payment P ON
            R.Rental_ID = P.Rental_ID
          WHERE R.Customer_ID = ?
          ORDER BY Pickup_Date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['customerid']);
$stmt->execute();
$result = $stmt->get_result();

$vanRentals = [];
while ($row = $result->fetch_assoc()) {
    $vanRentals[] = $row;
}

$joinedDate = date('d M Y', strtotime($customer['User_RegiDatetime']));
$fullName = $customer['C_FName'].' '.$customer['C_LName'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

  $profilePhoto = $_FILES['profilePhoto']['name'];
  $profilePhotoTmp = $_FILES['profilePhoto']['tmp_name'];
  $fName = $conn->real_escape_string($_POST['fName']);
  $mName = $conn->real_escape_string($_POST['mName']);
  $lName = $conn->real_escape_string($_POST['lName']);
  $birthday = $conn->real_escape_string($_POST['birthday']);
  $email = $conn->real_escape_string($_POST['email']);
  $phone = $conn->real_escape_string($_POST['phone']);
  $address = $conn->real_escape_string($_POST['address']);
  $currentPassword = $conn->real_escape_string($_POST['currentPassword']);
  $newPassword = $conn->real_escape_string($_POST['newPassword']);
  $confirmPassword = $conn->real_escape_string($_POST['confirmPassword']);

  $hashPassword = $customer['Hash_Password'];
  $saltPassword = $customer['Salt_Password'];

  $currentPasswordCheck = hash('sha256', $currentPassword . $saltPassword);

  // Verify the entered password
  if ($currentPasswordCheck !== $hashPassword && ($currentPassword !== '')) {
    $errors[] = "Current password is incorrect.";
    $errorFields[] = "currentPassword";
  }
  // Validate password
  if ($currentPassword !== '' && $newPassword !== '' && !validateAlphanumericLength($newPassword, 8, 32)) {
    $errors[] = "Password must be between 8 and 32 alphanumeric characters.";
    $errorFields[] = "newPassword";
  }
  if(($currentPassword === '') && ($newPassword !== '' || $confirmPassword !== '')) {
    $errors[] = "Enter the current password first.";
    $errorFields[] = "confirmPassword";
   }
  if ($newPassword !== $confirmPassword && $currentPassword !== '') {
    $errors[] = "Password and Confirm Password must match.";
    $errorFields[] = "confirmPassword";
  }
  if (!validatePhoneNumber($phone)) {
    $errors[] = "Phone number must start with '09' and have a total of 11 digits.";
    $errorFields[] = "phone";
  }
  if ($customer['C_Email'] !== $email && checkEmailExists($email)) {
    $errors[] = "Email already exists. Please choose a different email.";
    $errorFields[] = "email";
  }

  $destinationFolder = '../registration/uploads/profiles/';
  $profilePhotoUpload = uploadFile('profilePhoto', $destinationFolder);
  $profilePhotoPath = $destinationFolder . $profilePhotoUpload['fileName'];

  if (empty($errors)) {
    if(is_null($customer['C_ProfilePic'])){
        $query = "INSERT INTO customer_profile(Customer_ID, C_ProfilePic) VALUES (?,?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $_SESSION['customerid'], $profilePhotoUpload['fileName']);
        $stmt->execute();

    }
    if(!empty($_FILES['profilePhoto']['name'])){
        $query = "UPDATE customer_profile SET C_ProfilePic=? WHERE Customer_ID=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $profilePhotoUpload['fileName'], $_SESSION['customerid']);
        $stmt->execute();
    }
    if(    
      $fName !== $customer['C_FName'] ||
      $mName !== $customer['C_MName'] ||
      $lName !== $customer['C_LName'] ||
      $address !== $customer['C_Address'] ||
      $birthday !== $customer['C_Birthdate'] ||
      $email !== $customer['C_Email'] ||
      $phone !== $customer['C_PhoneNo']
    ){
        $query = "UPDATE customer SET C_FName=?, C_MName=?, C_LName=?, C_Address=?, 
                  C_Birthdate=?, C_Email=?, C_PhoneNo=? WHERE Customer_ID=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssi", $fName, $mName, $lName, $address, $birthday, $email, $phone, $_SESSION['customerid']);
        $stmt->execute();

    }
    if($currentPassword !== '' && $newPassword !== '' && $confirmPassword !== ''){
        $saltPassword = $customer['Salt_Password'];
        $newHashPassword = hash('sha256', $newPassword . $saltPassword);

        $query = "UPDATE password SET Hash_Password=? WHERE User_Email=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $newHashPassword, $customer['C_Email']);
        $stmt->execute();
    }
    
    header("Location: user-profile.php");
    exit();

  }

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

    if (!empty($fileField) && isset($_FILES[$fileField]['tmp_name']) && $_FILES[$fileField]['tmp_name'] !== '') {
      $allowedFileTypes = ['image/jpeg', 'image/png'];
      $fileType = mime_content_type($_FILES[$fileField]['tmp_name']);
      if (!in_array($fileType, $allowedFileTypes)) {
          $errors[] = "Only " . implode(", ", $allowedFileTypes) . " file types are allowed.";
          $errorFields[] = $fileField;
      }
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
function validateAlphanumericLength($value, $minLength, $maxLength)
{
    return preg_match('/^[a-zA-Z0-9!@#$%^&*()\-_]+$/', $value) && strlen($value) >= $minLength && strlen($value) <= $maxLength;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta content="IE=edge" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<title>VanGo</title>
	<!-- STYLE SHEETS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
	<link href="user-index-style.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/c08dde9054.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
  <!--Main Navigation-->
<header>
  <!-- Jumbotron -->
  <nav
  id="main-navbar"
  class="navbar navbar-expand-lg navbar-light bg-white fixed-top"
  >
<!-- Container wrapper -->
<div class="container-fluid">
 <!-- Toggle button -->
 <button
         class="navbar-toggler"
         type="button"
         data-mdb-toggle="collapse"
         data-mdb-target="#sidebarMenu"
         aria-controls="sidebarMenu"
         aria-expanded="false"
         aria-label="Toggle navigation"
         >
   <i class="fas fa-bars"></i>
 </button>

 <!-- Brand -->
 <a class="navbar-brand fw-bold" href="#"><span class="text-info">Van</span>Go</a> 
 
 <!-- Right links -->
 <div class="d-flex align-items-center">

   <!-- Notifications -->
   <div class="navbar-nav d-flex flex-row">
    <div class="nav-item me-3 me-lg-0">
    <a href="user-index.php" class="btn btn-light" title="Home">
      <i class="fa-solid fa-house"></i>
    </a>
    </div>
     
     <!-- Icon dropdown -->
     <div class="nav-item me-3 me-lg-0 dropdown-center">
       <button
         class="btn btn-light dropdown-toggle"
         data-bs-toggle="dropdown"
         aria-expanded="true"
       >
         <i class="fas fa-user"></i>
     </button>
       <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
         <li>
           <a class="dropdown-item" href="user-profile.php">Profile</a>
         </li>
         <li>
           <a class="dropdown-item" href="#">Settings</a>
         </li>
         <li><hr class="dropdown-divider" /></li>
         <li>
           <a class="dropdown-item" href="../signin/user-signin.php">Logout </a>
         </li>
       </ul>
     </div>
   </div>
 </div>
</div>
<!-- Container wrapper -->
</nav>
  <!-- Jumbotron -->

  <!-- Heading -->
  <div class="bg-primary mb-4">
    <div class="container py-5">
    </div>
  </div>
  <!-- Heading -->
</header>

<!-- sidebar + content -->
<section class="">
  <div class="container">
    <div class="row flex-lg-nowrap">
      <div class="col-12 col-lg-auto mb-3" style="width: 200px;">
        <div class="card p-3">
          <div class="e-navlist e-navlist--active-bg">
            <ul class="nav">
              <li class="nav-item active"><a class="nav-link px-2 active" href="#"><i class="fa fa-fw fa-bar-chart mr-1"></i><span>Profile</span></a></li>
              <li class="nav-item"><a class="nav-link px-2"><i class="fa fa-fw fa-cog mr-1"></i><span>Settings</span></a></li>
              <li class="nav-item"><a class="nav-link px-2" href="../signin/user-signin.php"><i class="fa fa-fw fa-sign-out mr-1"></i><span>Logout</span></a></li>
            </ul>
          </div>
        </div>
      </div>
    
      <div class="col vh-100">
        <div class="row">
          <div class="col mb-3">
            <div class="card">
              <div class="card-body">
                <div class="e-profile">
                  <div class="row">
                    <div class="col-12 col-sm-auto mb-3">
                      <div class="mx-auto" style="width: 140px;">
                        <div class="d-flex justify-content-center align-items-center rounded" style="height: 140px; background-color: rgb(233, 236, 239);">
                          <img src="<?php echo '../registration/uploads/profiles/'.$customer['C_ProfilePic']; ?>" id="profilePhotoContainer" alt="Customer Photo" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                      </div>
                    </div>
                    <div class="col d-flex flex-column flex-sm-row justify-content-between mb-3">
                      <div class=" text-sm-left mb-2 mb-sm-0">
                        <h4 class="pt-sm-2 pb-1 mb-0 text-nowrap"><?php echo $fullName; ?></h4>
                        <p class="mb-0 text-muted"><?php echo $customer['C_Email']; ?></p>
                          <div class="mt-2">
                          <form action="user-profile.php" method="POST" enctype="multipart/form-data">
                            <label for="profilePhoto" class="btn btn-primary">
                              <i class="fa fa-fw fa-camera"></i>
                              <span>Change Photo</span>
                            </label>
                            <input name="profilePhoto" id="profilePhoto" type="file" style="display: none;">
                          </div>
                      </div>
                      <div class="text-center text-sm-right">
                        <div class="text-muted"><small>Joined <?php echo $joinedDate; ?></small></div>
                      </div>
                    </div>
                  </div>
                  <ul class="nav nav-tabs">
                    <li class="nav-item"><a href="#profile" class="active nav-link" data-bs-toggle="tab" role="tab" aria-controls="profile" aria-selected="true">Personal Details</a></li>
                    <li class="nav-item"><a href="#history" class="nav-link" data-bs-toggle="tab" role="tab" aria-controls="history" aria-selected="false">History</a></li>
                  </ul>
                  <div class="tab-content pt-3">
                    <div class="tab-pane active" id="profile">
                    <?php if (!empty($errors)) : ?>
                                <ul class="error" style="color: red;" >
                                    <?php foreach ($errors as $error) : ?>
                                        <li><?php echo $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                      <div class="row">
                        <div class="col">
                          <div class="row">
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="fName">First Name</label>
                                <input class="form-control" type="text" name="fName" value="<?php echo $customer['C_FName']; ?>" required>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="mName">Middle Name</label>
                                <input class="form-control" type="text" name="mName" value="<?php echo $customer['C_MName']; ?>" required>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="lName">Last Name</label>
                                <input class="form-control" type="text" name="lName" value="<?php echo $customer['C_LName']; ?>" required>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="birthday">Birthday</label>
                                <input class="form-control" type="date" name="birthday" value="<?php echo $customer['C_Birthdate']; ?>" required>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control" type="email" name="email" value="<?php echo $customer['C_Email']; ?>" required>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="phone">Phone</label>
                                <input class="form-control" type="text" name="phone" value="<?php echo $customer['C_PhoneNo']; ?>" required>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col mb-3">
                              <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control" rows="3" name="address" required><?php echo $customer['C_Address']; ?> </textarea>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12 col-sm-6 mb-3">
                          <div class="mb-2"><b>Change Password</b></div>
                          <div class="row">
                            <div class="col">
                              <div class="form-group">
                                <label for="currentPassword">Current Password</label>
                                <input class="form-control" type="password" name="currentPassword" >
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col">
                              <div class="form-group">
                                <label for="newPassword">New Password</label>
                                <input class="form-control" type="password" name="newPassword" >
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col">
                              <div class="form-group">
                                <label for="confirmPassword">Confirm Password</label>
                                <input class="form-control" type="password" name="confirmPassword">
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-5 offset-sm-1 mb-3">
                          <div class="mb-2"><b>Keeping in Touch</b></div>
                          <div class="row">
                            <div class="col">
                              <label>Email Notifications</label>
                              <div class="custom-controls-stacked px-2">
                                <div class="custom-control custom-checkbox">
                                  <input type="checkbox" class="custom-control-input" id="notifications-blog" checked="">
                                  <label class="custom-control-label" for="notifications-blog">Blog posts</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                  <input type="checkbox" class="custom-control-input" id="notifications-news" checked="">
                                  <label class="custom-control-label" for="notifications-news">Newsletter</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                  <input type="checkbox" class="custom-control-input" id="notifications-offers" checked="">
                                  <label class="custom-control-label" for="notifications-offers">Personal Offers</label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col d-flex justify-content-end">
                          <button class="btn btn-primary" name="submit" type="submit" >Save Changes</button>
                        </div>
                      </div>
                    </form>
                    </div>

                    <div class="tab-pane" id="history">

                      <div id="cardContainer"></div>
                      
                        <!-- MODAL FOR DETAILS-->
                        <div class="modal fade" id="vandetailsModal" tabindex="-1" role="dialog" aria-labelledby="vandetailsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="vandetailsModalLabel">Details</h5>
                                  <button type="button" class="close btn btn-light" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                    <div class="col-lg-5 border-end">
                                        <img class="w-100 h-auto img-thumbnail mb-1 shadow" id="modalImage" >
                                    </div>
                                    <div class="col-lg-5 ">
                                        <h4 class="border-bottom"><span id="vanName"></span></h4>
                                        <p>Capacity: <span id="vanCapacity"></span></p>
                                        <p>Plate Number: <span id="plateNumber"></span></p>
                                        <p>Owner's Full Name: <span id="ownerFullName"></span></p>
                                        <p>Owner's Address: <span id="ownerAddress"></span></p>
                                        <p>Owner's Phone Number: <span id="ownerPhoneNo"></span></p>
                                        <p>Start Date: <span id="startDate"></span></p>
                                        <p>End Date: <span id="endDate"></span></p>
                                        <p>Total: ₱<span id="total"></span></p>
                                    </div>
                                    <div class="col-lg-2 align-items-center justify-content-center border-start">
                                        <div class="row w-100">
                                        <button class="btn btn-danger mb-2 mx-2 shadow" id="cancelRentalButton" data-bs-toggle="modal" data-bs-target="#confirmationModal" >Cancel</button>
                                        </div>
                                        <div class="row w-100">
                                        <button class="btn btn-light mb-2 mx-2 shadow" id="rateNowButton" data-bs-toggle="modal" data-bs-target="#ratevanModal"><i class="fas fa-star text-warning"></i> Rate now</button>
                                        </div>
                                    </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary shadow" data-bs-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                            <!-- MODAL FOR RATINGS-->
                          <div class="modal fade" id="ratevanModal" tabindex="-1" role="dialog" aria-labelledby="ratevanModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="ratevanModalLabel">Rate Van</h5>
                                  <button type="button" class="close btn btn-light" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div id="errorMessage" class="text-danger mt-2"></div>
                                        <h5>Quality of Service</h5>
                                        <div class="star-rating">
                                          <i class="far fa-star" data-value="1"></i>
                                          <i class="far fa-star" data-value="2"></i>
                                          <i class="far fa-star" data-value="3"></i>
                                          <i class="far fa-star" data-value="4"></i>
                                          <i class="far fa-star" data-value="5"></i>
                                        </div>
                                        <div class="form-group pb-2">
                                            <textarea class="form-control" rows="4" id="reviewComment" name="reviewComment" placeholder="Write something here..."></textarea>
                                        </div>
                                        <div class=" form-outline d-flex">
                                            <i class="fa-solid fa-camera fa-lg pt-3 px-1"></i>
                                            <input type="file" id="fileInput" name="fileInput" class="form-control custom-file-input " >
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button>
                                  <button type="button" class="btn btn-primary" id="ratingSubmit" name="ratingSubmit">Confirm</button>
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- MODAL CONFIRM -->
                          <div class="modal fade" id="confirmationModal" tabindex="-1">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">Confirmation</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <p><span id="confirmContent">Are you sure you want to cancel this rental? This action cannot be undone.</span></p>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button>
                                  <button type="button" class="btn btn-danger" id="confirmSubmit" name="confirmSubmit" data-bs-dismiss="modal">Confirm</button>
                                </div>
                              </div>
                            </div>
                          </div>
                                                              
                          <!-- MODAL REFUND -->
                          <div class="modal fade" id="refundModal" tabindex="-1">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">Refund</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <p><span id="refundContent">Please note that a refund will be processed for the cancelled rental. Our team will reach out to you shortly regarding the refund.</span></p>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>  
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- MODAL ALERT -->
                          <div class="modal fade" id="alertModal" tabindex="-1">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">Alert</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <p><span id="alertContent"></span></p>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
    
          <div class="col-12 col-md-3 mb-3">
            <div class="card">
              <div class="card-body">
                <h6 class="card-title font-weight-bold">Support</h6>
                <p class="card-text">Get fast, free help from our friendly assistants.</p>
                <button type="button" class="btn btn-primary">Contact Us</button>
              </div>
            </div>
          </div>
        </div>
    
      </div>
    </div>
    </div>            
</section>
<!-- sidebar + content -->

	<!-- FOOTER -->
	<footer class="footer bg-dark mt-auto">
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
	<!-- footer ends -->
	
	<!-- JAVASCRIPT -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<script>
      const stars = document.querySelectorAll(".star-rating .fa-star");
      var vanRentals = <?php echo json_encode($vanRentals); ?>;
      const ratingSubmitBtn = document.getElementById("ratingSubmit");
      const confirmSubmitBtn = document.getElementById("confirmSubmit");
      var rentalId;
      var selectedStars;
      var container = document.querySelector('#cardContainer');
      const profilePhotoContainer = document.getElementById("profilePhotoContainer");
      const profilePhoto = document.getElementById("profilePhoto");

      stars.forEach((star, index) => {
        star.addEventListener("mouseover", () => {
          selectedStars = index + 1; // Add 1 to index to get the selected value
          console.log('Number of selected stars (mouseover):', selectedStars);
          addActiveStars(index);
        });
    
    
        star.addEventListener("click", () => {
          addPermanentStars(index);
          selectedStars = index + 1; // Add 1 to index to get the selected value
          console.log('Number of selected stars (click):', selectedStars);
        });
      });

      ratingSubmitBtn.addEventListener("click", function(event) {

        // Retrieve the form data
        const reviewComment = document.getElementById("reviewComment").value;
        const fileInput = document.getElementById("fileInput").files;

        const errors = [];

          // Perform validation
        if (reviewComment.trim() === "") {
          // Display an error message for the review comment field
          errors.push('Please enter a review.');
        }


        if (errors.length > 0) {
            const errorMessageList = document.createElement('ul');
            errors.forEach((error) => {
                const listItem = document.createElement('li');
                listItem.textContent = error;
                errorMessageList.appendChild(listItem);
            });

            errorMessage.innerHTML = ''; // Clear any existing error message
            errorMessage.appendChild(errorMessageList);
            return;
        }

        // Construct the form data object
        const formData = new FormData();
        formData.append("rentalId", rentalId);
        formData.append("reviewRating", selectedStars);
        formData.append("reviewComment", reviewComment);
        for (let i = 0; i < fileInput.length; i++) {
          formData.append("fileInput[]", fileInput[i]);
        }

          // Perform the form submission using fetch or any other AJAX method
        fetch("insertForReview.php", {
          method: "POST",
          body: formData
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {

              hideRateVanModalForm();
              openAlertModalForm("Review Submitted Successfully!", "black");
              document.getElementById("reviewComment").value = "";
              document.getElementById("fileInput").value = null;
              clearActiveStars();
            } else {
              hideRateVanModalForm();
              openAlertModalForm("An error occurred while submitting the review. Please try again.", "black");
            }
          
            

          })
          .catch(error => {
            
            const errorMessage = error.toString();
            if (errorMessage.includes("is not valid JSON")) {
              // Ignore the error if it contains "is not valid JSON"
              hideRateVanModalForm();
              openAlertModalForm("Review Submitted Successfully!", "black");
              document.getElementById("reviewComment").value = "";
              document.getElementById("fileInput").value = null;
              clearActiveStars();
              return;
            }else{
              // After performing the desired action, you can hide the modal using JavaScript:
              // const ratevanModal = new bootstrap.Modal(document.getElementById('ratevanModal'));
              // ratevanModal.hide();

            }

            console.error("Error:", error); 
            
        });

      });

      const tabs = document.querySelectorAll('.nav-link');

      tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
          const tabId = tab.getAttribute('href');
          const currentURL = new URL(window.location.href);
          currentURL.searchParams.set('activeTab', tabId);
          window.history.pushState({}, '', currentURL);
        });
      });


      window.addEventListener('DOMContentLoaded', function() {
        const activeTabId = getQueryParam('activeTab');
        if (activeTabId) {
          const previousTab = document.querySelector(`.nav-link[href="${activeTabId}"]`);
          if (previousTab) {
            previousTab.click();
          }
        }
      });


      confirmSubmitBtn.addEventListener("click", () => {

        // Construct the form data object
        const formData = new FormData();
        formData.append("rentalId", rentalId);

        // Perform the cancellation using fetch or any other AJAX method
        fetch("cancelRental.php", {
          method: "POST",
          body: formData
        })
          .then(response => response.json())
          .then(data => {
            // Handle the response data
            if (data.success) {
              openRefundModalForm();
              
            } else {
              // Display error message or perform any error handling
              openAlertModalForm("An error occurred while cancelling the rental. Please try again.", "black");
            }
          })
          .catch(error => {
            // Handle the error
            openAlertModalForm("An error occurred while cancelling the rental. Please try again.", "black");
          });
      });


      function addActiveStars(index) {
        clearActiveStars();
        for (let i = 0; i <= index; i++) {
          stars[i].classList.add("text-warning","fas");
          
        }
      }

      function addPermanentStars(index) {
          clearActiveStars();
        for (let i = 0; i <= index; i++) {
          stars[i].classList.remove("far");
          stars[i].classList.add("text-warning","fas");
          
        }
      }
    
      function clearActiveStars() {
        stars.forEach((star) => {

          star.classList.add("far");
          star.classList.remove("text-warning","fas");

        });
      }

      function handleImagePreview(inputElement, imageElement) {
        inputElement.addEventListener("change", function() {
          const selectedImage = imageElement;

          // Check if a file is selected
          if (inputElement.files && inputElement.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
              selectedImage.src = e.target.result;
              selectedImage.style.display = "block"; // Show the image once it's loaded
            };

            reader.readAsDataURL(inputElement.files[0]);
          }
        });
      }

      function openAlertModalForm(textContent, color) {
        const modal = document.getElementById('alertModal');
        const alertContent = document.getElementById('alertContent');
        const modalTitle = modal.querySelector('.modal-title');

        // Set the content of the alert
        alertContent.textContent = textContent;
        alertContent.style.color = color;
        modalTitle.style.color = color;

        // Open the modal
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
      }
      function openRefundModalForm() {
        const refundModal = document.getElementById("refundModal");
        const bootstrapModal = new bootstrap.Modal(refundModal);
        bootstrapModal.show();
      }

      function hideRateVanModalForm() {
        const ratevanModal = document.getElementById("ratevanModal");
        const bootstrapModal = bootstrap.Modal.getInstance(ratevanModal);
        if (bootstrapModal) {
          bootstrapModal.hide();
        }
      }

      function regenerateCards() {
        
        container.innerHTML = "";

        // Loop through the 'vanRentals' array and generate the HTML structure for each card
        vanRentals.forEach((rental) => {
          // Create the card element
          const card = document.createElement('div');
          card.classList.add('card', 'row', 'shadow', 'mb-2');
          card.style.cursor = 'pointer';
          card.setAttribute('data-bs-toggle', 'modal');
          card.setAttribute('data-bs-target', '#vandetailsModal');

          // Create the card body
          const cardBody = document.createElement('div');
          cardBody.classList.add('card-body');

          // Create the flex row
          const flexRow = document.createElement('div');
          flexRow.classList.add('row', 'flex-row', 'd-flex');

          // Create the image column
          const imageCol = document.createElement('div');
          imageCol.classList.add('col-lg-2');

          // Create the image element
          const image = document.createElement('img');
          image.src = '../registration/uploads/van_photos/' + rental.V_Photo;
          image.classList.add('w-100', 'h-100', 'img-thumbnail');
          image.dataset.imageSrc = image.src;

          // Append the image element to the image column
          imageCol.appendChild(image);

          // Create the details column
          const detailsCol = document.createElement('div');
          detailsCol.classList.add('col-lg-8'); // Adjust the width based on your desired layout

          // Create the title row
          const titleRow = document.createElement('div');
          titleRow.classList.add('row');
          titleRow.innerHTML = `<a href="#">${rental.V_Name}</a>`;

          // Create the date row
          const dateRow = document.createElement('div');
          dateRow.classList.add('row');

          // Format the pickup date
          const pickupDate = new Date(rental.Pickup_Date);
          const formattedPickupDate = pickupDate.toLocaleDateString('en-US', {
            month: '2-digit',
            day: '2-digit',
            year: 'numeric'
          }).replace(/\//g, '-').replace(/^(.*?)\-(\d{1})\-([0-9]{2})$/, "$1-0$2-$3");

          // Format the return date
          const returnDate = new Date(rental.Return_Date);
          const formattedReturnDate = returnDate.toLocaleDateString('en-US', {
            month: '2-digit',
            day: '2-digit',
            year: 'numeric'
          }).replace(/\//g, '-').replace(/^(.*?)\-(\d{1})\-([0-9]{2})$/, "$1-0$2-$3");

          // Set the formatted dates in the date row
          dateRow.innerHTML = `<span class="text-muted">From: ${formattedPickupDate} To: ${formattedReturnDate}</span>`;

          // Append the title row and date row to the details column
          detailsCol.appendChild(titleRow);
          detailsCol.appendChild(dateRow);

          // Create the status column
          const statusCol = document.createElement('div');
          statusCol.classList.add('col-lg-2', 'text-end'); // Adjust the width and alignment based on your desired layout

          // Create the status element
          const status = document.createElement('span');
          status.classList.add('fw-bold');

          // Set the text content and color based on the rental status
          if (rental.Rental_Status === 'Cancelled') {
            status.textContent = rental.Rental_Status;
            status.classList.add('text-danger'); // Set color to red
          } else if (rental.Rental_Status === 'Pending') {
            status.textContent = rental.Rental_Status;
            status.classList.add('text-warning'); // Set color to orange
          } else if (rental.Rental_Status === 'Completed') {
            status.textContent = rental.Rental_Status;
            status.classList.add('text-success'); // Set color to black
          } 

          // Append the status element to the status column
          statusCol.appendChild(status);

          // Append the image column, details column, and status column to the flex row
          flexRow.appendChild(imageCol);
          flexRow.appendChild(detailsCol);
          flexRow.appendChild(statusCol);

          // Append the flex row to the card body
          cardBody.appendChild(flexRow);

          // Append the card body to the card
          card.appendChild(cardBody);
          card.dataset.rentalId = rental.Rental_ID;

          // Append the card to the container
          container.appendChild(card);

          card.addEventListener('click', () => {
            rentalId = card.dataset.rentalId;
            const imageSrc = image.dataset.imageSrc;
            const rentalStatus = rental.Rental_Status;

            console.log(rentalId);

            // Get the modal elements
            const modalImage = document.querySelector('#modalImage');
            const modalVanName = document.querySelector('#vanName');
            const modalVanCapacity = document.querySelector('#vanCapacity');
            const modalPlateNumber = document.querySelector('#plateNumber');
            const modalOwnerFullName = document.querySelector('#ownerFullName');
            const modalOwnerAddress = document.querySelector('#ownerAddress');
            const modalOwnerPhoneNo = document.querySelector('#ownerPhoneNo');
            const modalStartDate = document.querySelector('#startDate');
            const modalEndDate = document.querySelector('#endDate');
            const modalTotal = document.querySelector('#total');
            const cancelButton = document.querySelector('#cancelRentalButton');
            const rateNowButton = document.querySelector('#rateNowButton');

            // Set the rental data as attributes of the modal elements
            modalImage.src = imageSrc;
            modalVanName.textContent = rental.V_Name;
            modalVanCapacity.textContent = rental.V_Capacity;
            modalPlateNumber.textContent = rental.V_PlateNo;
            modalOwnerFullName.textContent = rental.O_FullName;
            modalOwnerAddress.textContent = rental.O_Address; 
            modalOwnerPhoneNo.textContent = rental.O_PhoneNo;
            modalStartDate.textContent = formattedPickupDate + ' ' + rental.Pickup_Time;
            modalEndDate.textContent = formattedReturnDate + ' ' + rental.Return_Time;
            modalTotal.textContent = rental.Payment_Amount;


            if (rentalStatus === 'Cancelled') {
              cancelButton.disabled = true;
              rateNowButton.disabled = true;
            } 
            if (rentalStatus === 'Completed') {
              cancelButton.disabled = true;
              rateNowButton.disabled = false;
            }
            if (rentalStatus === 'Pending') {
              cancelButton.disabled = false;
              rateNowButton.disabled = true;
            }

          });

        });
      }

      handleImagePreview(profilePhoto, profilePhotoContainer);
      regenerateCards();

      function getQueryParam(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
      }

      $('#refundModal').on('hide.bs.modal', function (e) {
        location.reload();
      })

  </script>
</body>
</html>