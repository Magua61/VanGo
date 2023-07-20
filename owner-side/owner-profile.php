<?php

require_once '../db_connect.php';
session_start();

$errors = [];
$errorFields = [];

$query = "SELECT O.Owner_ID, O_FName, O_MName, O_LName, O_Gender, O_Address, O_Birthdate, O_Email, O_PhoneNo, O_ProfilePic, Hash_Password, Salt_Password, User_RegiDatetime
          FROM owner O
          LEFT JOIN owner_profile OP
            ON O.Owner_ID = OP.Owner_ID
          JOIN user U
            ON O.O_Email = U.User_Email
          JOIN password P
            ON U.User_Email = P.User_Email
          WHERE O.Owner_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['ownerid']);
$stmt->execute();
$result = $stmt->get_result();

$owner = $result->fetch_assoc();

$query = "SELECT V.Van_ID, concat_ws(' ', V_Make, V_Model, V_Year) AS 'V_Name', V_Photo, V_PlateNo, V_Make, V_Model, V_Year, V_Capacity, V_Rate, V_OR, V_CR, Owner_ID
          FROM van V RIGHT JOIN van_rate VR
            ON V.Van_ID = VR.Van_ID
          RIGHT JOIN van_document VD
            ON V.Van_ID = VD.Van_ID
          RIGHT JOIN van_photo VP
            ON V.Van_ID = VP.Van_ID
          WHERE Owner_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['ownerid']);
$stmt->execute();
$result = $stmt->get_result();

$vans = [];
while ($row = $result->fetch_assoc()) {
    $vans[] = $row;
}

$query = "SELECT Van_ID, RW.Review_ID, Review_Photo, concat_ws(' ', C_FName, C_LName) as 'C_FullName', Review_Rating, Review_Comment, DATE_FORMAT(Review_Datetime, '%Y-%m-%d %H:%i') as 'Review_Datetime'
          FROM customer C JOIN rental RL
            ON C.Customer_ID = RL.Customer_ID
          JOIN review RW
            ON RL.Rental_ID = RW.Rental_ID
          left JOIN review_photo RP
          ON RW.Review_ID = RP.Review_ID
          ORDER BY Review_Datetime DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$vanReviews = [];
while ($row = $result->fetch_assoc()) {
    $vanReviews[] = $row;
}



$joinedDate = date('d M Y', strtotime($owner['User_RegiDatetime']));
$fullName = $owner['O_FName'].' '.$owner['O_LName'];


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

  $hashPassword = $owner['Hash_Password'];
  $saltPassword = $owner['Salt_Password'];

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
  if ($owner['O_Email'] !== $email && checkEmailExists($email)) {
    $errors[] = "Email already exists. Please choose a different email.";
    $errorFields[] = "email";
  }

  $destinationFolder = '../registration/uploads/profiles/';
  $profilePhotoUpload = uploadFile('profilePhoto', $destinationFolder);
  $profilePhotoPath = $destinationFolder . $profilePhotoUpload['fileName'];

  if (empty($errors)) {
    if(is_null($owner['O_ProfilePic'])){
        $query = "INSERT INTO owner_profile(Owner_ID, O_ProfilePic) VALUES (?,?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $_SESSION['ownerid'], $profilePhotoPath);
        $stmt->execute();

    }
    if(!empty($_FILES['profilePhoto']['name'])){
        $query = "UPDATE owner_profile SET O_ProfilePic=? WHERE Owner_ID=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $profilePhotoPath, $_SESSION['ownerid']);
        $stmt->execute();
    }
    if(    
      $fName !== $owner['O_FName'] ||
      $mName !== $owner['O_MName'] ||
      $lName !== $owner['O_LName'] ||
      $address !== $owner['O_Address'] ||
      $birthday !== $owner['O_Birthdate'] ||
      $email !== $owner['O_Email'] ||
      $phone !== $owner['O_PhoneNo']
    ){
        $query = "UPDATE owner SET O_FName=?, O_MName=?, O_LName=?, O_Address=?, 
                  O_Birthdate=?, O_Email=?, O_PhoneNo=? WHERE Owner_ID=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssi", $fName, $mName, $lName, $address, $birthday, $email, $phone, $_SESSION['ownerid']);
        $stmt->execute();

    }
    if($currentPassword !== '' && $newPassword !== '' && $confirmPassword !== ''){
        $saltPassword = $owner['Salt_Password'];
        $newHashPassword = hash('sha256', $newPassword . $saltPassword);

        $query = "UPDATE password SET Hash_Password=? WHERE User_Email=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $newHashPassword, $owner['O_Email']);
        $stmt->execute();
    }
    
    header("Location: owner-profile.php");
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
	<link href="../customer-side/user-index-style.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/c08dde9054.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    <a href="owner-dashboard.php" class="btn btn-light" title="Home">
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
           <a class="dropdown-item" href="owner-profile.php">Profile</a>
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
              <li class="nav-item active"><a class="nav-link px-2 active" href="owner-profile.php"><i class="fa fa-fw fa-bar-chart mr-1"></i><span>Profile</span></a></li>
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
                          <img src="<?php echo $owner['O_ProfilePic']; ?>" id="profilePhotoContainer" alt="Owner Photo" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                      </div>
                    </div>
                    <div class="col d-flex flex-column flex-sm-row justify-content-between mb-3">
                      <div class=" text-sm-left mb-2 mb-sm-0">
                        <h4 class="pt-sm-2 pb-1 mb-0 text-nowrap"><?php echo $fullName; ?></h4>
                        <p class="mb-0 text-muted"><?php echo $owner['O_Email']; ?></p>
                          <div class="mt-2">
                          <form action="owner-profile.php" method="POST" enctype="multipart/form-data">
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
                    <li class="nav-item"><a href="#vehicles" class="nav-link" data-bs-toggle="tab" role="tab" aria-controls="vehicles" aria-selected="false">Vehicles</a></li>
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
                                <input class="form-control" type="text" name="fName" value="<?php echo $owner['O_FName']; ?>" required>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="mName">Middle Name</label>
                                <input class="form-control" type="text" name="mName" value="<?php echo $owner['O_MName']; ?>" required>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="lName">Last Name</label>
                                <input class="form-control" type="text" name="lName" value="<?php echo $owner['O_LName']; ?>" required>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="birthday">Birthday</label>
                                <input class="form-control" type="date" name="birthday" value="<?php echo $owner['O_Birthdate']; ?>" required>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control" type="email" name="email" value="<?php echo $owner['O_Email']; ?>" required>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="phone">Phone</label>
                                <input class="form-control" type="text" name="phone" value="<?php echo $owner['O_PhoneNo']; ?>" required>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col mb-3">
                              <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control" rows="3" name="address" required><?php echo $owner['O_Address']; ?> </textarea>
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
                          <button class="btn btn-primary" name="submit" type="submit">Save Changes</button>
                        </div>
                      </div>
                    </form>
                    </div>

                    <div class="tab-pane" id="vehicles">

                      <div id="cardContainer"></div>

                        <!-- MODAL FOR DETAILS-->
                        <div class="modal fade" id="vehicleModal" tabindex="-1" role="dialog" aria-labelledby="vehicleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="vehicleModalLabel">Details</h5>
                                  <button type="button" class="close btn btn-light" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                    <div class="col-lg-5 border-end">
                                        <img id="vanPhoto" class="w-100 h-auto img-thumbnail mb-1 shadow">
                                    </div>
                                    <div class="col-lg-5 ">
                                        <h4 class="border-bottom"><span id="vanName"></span></h4>
                                        <p>Make: <span id="vanMake"></span></p>
                                        <p>Model: <span id="vanModel"></span></p>    
                                        <p>Year: <span id="vanYear"></span></p>                  
                                        <p>Capacity: <span id="vanCapacity"></span></p>
                                        <p>Plate Number: <span id="plateNumber"></span></p>
                                        <p>Rate: ₱<span id="vanRate"></span></p>
                                        <div class="row">
                                          <p class="border-top mt-2">Documents</p>
                                          <div class="container col-lg-5 border">
                                            <div class="row justify-content-center align-items-center" style="cursor: pointer;">
                                                <img id="vanCR" class="w-75 h-auto " alt="Image" data-bs-toggle="modal" data-bs-target="#imageModal">
                                                <div class="label text-center bg-dark text-white">Certificate of Registration</div>
                                            </div>
                                          </div>
                                          <div class="container col-lg-5 border">
                                            <div class="row justify-content-center align-items-center" style="cursor: pointer;">
                                                <img id="vanOR" class="w-75 h-auto" alt="Image" data-bs-toggle="modal" data-bs-target="#imageModal">
                                                <div class="label text-center bg-dark text-white">Official Receipt of Registration</div>
                                            </div>
                                          </div>
                                          
                                       
                                        </div>
                                    </div>
                                    <div class="col-lg-2 align-items-center justify-content-center border-start">
                                        <div class="row w-100">
                                        <button class="btn btn-primary mb-2 mx-2 shadow"  data-bs-toggle="modal" data-bs-target="#updatevehicleModal">Update</button>
                                        </div>
                                        <div class="row w-100">
                                        <button class="btn btn-danger mb-2 mx-2 shadow">Disable</button>
                                        </div>
                                        <div class="row w-100">
                                        <button class="btn btn-light mb-2 mx-2 shadow" id="vanRatingsButton" data-bs-toggle="modal" data-bs-target="#vanratingsModal"><i class="fas fa-star text-warning"></i>Ratings</button>
                                        </div>
                                    </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary shadow" data-bs-dismiss="modal">CLose</button>
                                
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- MODAL FOR Update-->
                        <div class="modal fade" id="updatevehicleModal" tabindex="-1" role="dialog" aria-labelledby="updatevehicleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="updatevehicleModalLabel">Edit Details</h5>
                                  <button type="button" class="close btn btn-light" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                    <div class="col-lg-6 border-end">
                                        <label for="vanPhotoEdit" style="cursor: pointer;">
                                          <img id="vanPhotoUpdate" class="w-100 h-auto img-thumbnail mb-1 shadow">
                                          <div class="label text-center bg-dark text-white rounded shadow">Edit Image</div>
                                          </label>
                                          <input type="file" id="vanPhotoEdit" name="vanPhotoEdit" class="d-none">
                                    </div>
                                    <div class="col-lg-6 ">
                                        <div id="errorMessage" class="text-danger mt-2"></div>
                                        <h4 class="border-bottom"><span id="vanNameUpdate"></span></h4>
                                        <p>Make: <input class="form-control" type="text" id="vanMakeUpdate" required></p>
                                        <p>Model: <input class="form-control" type="text" id="vanModelUpdate" required></p>
                                        <p>Year: <input class="form-control" type="text" id="vanYearUpdate" required></p>
                                        <p>Capacity: <input class="form-control" type="text" id="vanCapacityUpdate" required></p>
                                        <p>Plate Number: <input class="form-control" type="text" id="plateNumberUpdate" required></p>
                                        <p>Rate: ₱<input class="form-control" type="text" id="vanRateUpdate" required></p>
                                        <div class="row">
                                          <p class="border-top mt-2">Documents</p>
                                          <div class="container col-lg-6 ">
                                            <div class="row justify-content-center align-items-center" style="cursor: pointer;">
                                                
                                                <img id="vanCRUpdate" class="w-75 h-auto " alt="Image" data-bs-toggle="modal" data-bs-target="#imageModal">
                                                <label for="vanCREdit" class="border row justify-content-center align-items-center" style="cursor: pointer;">
                                                <div class="label text-center bg-dark text-white"><i class="fa-solid fa-pen"></i>Certificate of Registration</div>
                                                </label>
                                                <input type="file" id="vanCREdit" class="d-none">
                                            </div>
                                          </div>
                                          <div class="container col-lg-6 ">
                                            <div class="row justify-content-center align-items-center" style="cursor: pointer;">
                                            
                                                <img id="vanORUpdate" class="w-75 h-auto" alt="Image" data-bs-toggle="modal" data-bs-target="#imageModal">
                                                <label for="vanOREdit" class="border row justify-content-center align-items-center" style="cursor: pointer;">
                                                <div class="label text-center bg-dark text-white"><i class="fa-solid fa-pen"></i>Official Receipt of Registration</div>
                                                </label>
                                                <input type="file" id="vanOREdit" class="d-none">
                                            </div>
                                          </div>
                                          
                                       
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary shadow" data-bs-dismiss="modal">Back</button>
                                  <button type="button" class="btn btn-primary shadow" id="updateButton" data-bs-dismiss="modal">Save Changes</button>
                                </div>
                              </div>
                            </div>
                          </div>

                            <!-- MODAL FOR RATINGS-->
                          <div class="modal fade" id="vanratingsModal" tabindex="-1" role="dialog" aria-labelledby="vanratingsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="vanratingsModalLabel">Ratings</h5>
                                  <button type="button" class="close btn btn-light" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row" id='ratingsContainer'></div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- Modal for full-size image -->
                          <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
                            <div class="modal-dialog " role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="imageModalLabel"> </h5>
                                  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <img id="fullSizeImage" class="img-fluid" alt="Full-size Image">
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
  <script>
      var vans = <?php echo json_encode($vans); ?>;
      var vanReviews = <?php echo json_encode($vanReviews); ?>;
      var container = document.querySelector('#cardContainer');
      const updateButton = document.getElementById('updateButton');
      const vanPhotoEdit = document.getElementById("vanPhotoEdit");
      const vanPhotoUpdate = document.getElementById("vanPhotoUpdate");
      const vanCREdit = document.getElementById("vanCREdit");
      const vanCRUpdate = document.getElementById("vanCRUpdate");
      const vanOREdit = document.getElementById("vanOREdit");
      const vanORUpdate = document.getElementById("vanORUpdate");
      const profilePhotoContainer = document.getElementById("profilePhotoContainer");
      const profilePhoto = document.getElementById("profilePhoto");
      
      var vanId;
      const tabs = document.querySelectorAll('.nav-link');
      
      function regenerateCards() {

        container.innerHTML = "";

        vans.forEach((van) => {
          // Create the main card element
          const card = document.createElement('div');
          card.classList.add('card', 'row', 'shadow', 'mb-2');
          card.style.cursor = 'pointer';
          card.setAttribute('data-bs-toggle', 'modal');
          card.setAttribute('data-bs-target', '#vehicleModal');

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
          image.src = '../registration/' + van.V_Photo;
          image.classList.add('w-100', 'h-100', 'img-thumbnail');
          image.dataset.imageSrc = image.src;

          // Append the image element to the image column
          imageCol.appendChild(image);

          // Create the details column
          const detailsCol = document.createElement('div');
          detailsCol.classList.add('col-lg-10');

          // Create the title row
          const titleRow = document.createElement('div');
          titleRow.classList.add('row');

          // Create the anchor element
          const anchor = document.createElement('a');
          anchor.href = '#';
          anchor.textContent = van.V_Name;

          // Append the anchor element to the title row
          titleRow.appendChild(anchor);

          // Append the title row to the details column
          detailsCol.appendChild(titleRow);

          // Append the image column and details column to the flex row
          flexRow.appendChild(imageCol);
          flexRow.appendChild(detailsCol);

          // Append the flex row to the card body
          cardBody.appendChild(flexRow);

          // Append the card body to the main card
          card.appendChild(cardBody);
          card.dataset.vanId = van.Van_ID;

          // Append the card to the card container
          container.appendChild(card);

          card.addEventListener('click', () => {
            vanId = card.dataset.vanId;
            const imageSrc = image.dataset.imageSrc;

            console.log(vanId);

            // Get the modal elements
            const modalImage = document.querySelector('#vanPhoto');
            const modalVanName = document.querySelector('#vanName');
            const modalVanModel = document.querySelector('#vanModel');
            const modalVanMake = document.querySelector('#vanMake');
            const modalVanYear = document.querySelector('#vanYear');
            const modalVanCapacity = document.querySelector('#vanCapacity');
            const modalPlateNumber = document.querySelector('#plateNumber');
            const modalVanRate = document.querySelector('#vanRate');
            const modalVanCR = document.querySelector('#vanCR');
            const modalVanOR = document.querySelector('#vanOR');

            const modalImageUpdate = document.querySelector('#vanPhotoUpdate');
            const modalVanNameUpdate = document.querySelector('#vanNameUpdate');
            const modalVanModelUpdate = document.querySelector('#vanModelUpdate');
            const modalVanYearUpdate = document.querySelector('#vanYearUpdate');
            const modalVanMakeUpdate = document.querySelector('#vanMakeUpdate');
            const modalVanCapacityUpdate = document.querySelector('#vanCapacityUpdate');
            const modalPlateNumberUpdate = document.querySelector('#plateNumberUpdate');
            const modalVanRateUpdate = document.querySelector('#vanRateUpdate');
            const modalVanCRUpdate = document.querySelector('#vanCRUpdate');
            const modalVanORUpdate = document.querySelector('#vanORUpdate');

            //const cancelButton = document.querySelector('#cancelRentalButton');
            //const rateNowButton = document.querySelector('#rateNowButton');

            // Set the van data as attributes of the modal elements
            modalImage.src = imageSrc;
            modalVanName.textContent = van.V_Name;
            modalVanModel.textContent = van.V_Model
            modalVanMake.textContent = van.V_Make;
            modalVanYear.textContent = van.V_Year;
            modalVanCapacity.textContent = van.V_Capacity;
            modalPlateNumber.textContent = van.V_PlateNo;
            modalVanRate.textContent = van.V_Rate;
            modalVanCR.src = '../registration/' + van.V_CR; 
            modalVanOR.src = '../registration/' + van.V_OR;

            modalImageUpdate.src = imageSrc;
            modalVanNameUpdate.textContent = van.V_Name;
            modalVanModelUpdate.value = van.V_Model;
            modalVanMakeUpdate.value = van.V_Make;
            modalVanYearUpdate.value = van.V_Year;
            modalVanCapacityUpdate.value = van.V_Capacity;
            modalPlateNumberUpdate.value = van.V_PlateNo;
            modalVanRateUpdate.value = van.V_Rate;
            modalVanCRUpdate.src = '../registration/' + van.V_CR; 
            modalVanORUpdate.src = '../registration/' + van.V_OR;

            modalVanCR.addEventListener('click', () => {
              changeFullSizeImage('../registration/' + van.V_CR, 'Certificate of Registration');
            });

            modalVanOR.addEventListener('click', () => {
              changeFullSizeImage('../registration/' + van.V_OR, 'Official Receipt of Registration');
            });
            modalVanCRUpdate.addEventListener('click', () => {
              changeFullSizeImage('../registration/' + van.V_CR, 'Certificate of Registration');
            });

            modalVanORUpdate.addEventListener('click', () => {
              changeFullSizeImage('../registration/' + van.V_OR, 'Official Receipt of Registration');
            });

          });
        
        });
        
      }

      function displayRatings(ratings) {
        const ratingContainer = document.getElementById('ratingsContainer');
        ratingContainer.innerHTML = '';

        ratings.forEach(rating => {
          const card = createRatingCard(rating);
          ratingContainer.appendChild(card);
        });
      }

      function createRatingCard(rating) {
        const card = document.createElement('div');
        card.classList.add('card', 'mb-2');

        const cardBody = document.createElement('div');
        cardBody.classList.add('card-body');

        const flexRow = document.createElement('div');
        flexRow.classList.add('row', 'flex-row', 'd-flex');

        const col1 = document.createElement('div');
        col1.classList.add('col-lg-2');

        const image = document.createElement('img');
        image.src = '../registration/uploads/reviews/' + rating.Review_Photo;
        image.classList.add('w-100', 'h-100', 'img-thumbnail');

        const col2 = document.createElement('div');
        col2.classList.add('col-lg-10');

        const row1 = document.createElement('div');
        row1.classList.add('row');

        const username = document.createElement('p');
        username.classList.add('mb-0');
        var tempName = rating.C_FullName;
        var modifiedName = tempName.replace(/\B\w(?=\w)/g, "*");
        username.textContent = modifiedName;
        

        const ratingStars = document.createElement('div');
        ratingStars.classList.add('ratings');

        const starsHTML = getRatingStarsHTML(rating.Review_Rating);
        ratingStars.innerHTML = starsHTML; // Set the HTML content

        const row2 = document.createElement('div');
        row2.classList.add('row');
        const date = document.createElement('span');
        date.classList.add('text-muted', 'small');
        date.textContent = rating.Review_Datetime;
        const row3 = document.createElement('div');
        row3.classList.add('row');
        const comment = document.createElement('p');
        comment.textContent = rating.Review_Comment;

        col1.appendChild(image);
        row1.appendChild(username);
        row1.appendChild(ratingStars);
        row2.appendChild(date);
        row3.appendChild(comment);
        col2.appendChild(row1);
        col2.appendChild(row2);
        col2.appendChild(row3);
        flexRow.appendChild(col1);
        flexRow.appendChild(col2);
        cardBody.appendChild(flexRow);
        card.appendChild(cardBody);

        return card;
      }


      function getRatingStarsHTML(rating) {
        const fullStars = '<i class="fas fa-star text-warning"></i>'.repeat(rating);
        const emptyStars = '<i class="far fa-star text-warning"></i>'.repeat(5 - rating);
        return fullStars + emptyStars;
      }

      function getRatingsByVanID(vanID) {
        const ratings = [];
        for (const rating of vanReviews) {
          if (rating.Van_ID == vanID) {
            ratings.push(rating);
          }
        }
        return ratings;
      }



      const button = document.getElementById('vanRatingsButton');
      button.addEventListener('click', () => {
        const ratingsContainer = document.getElementById('ratingsContainer');
        ratingsContainer.innerHTML = ''; // Clear the container before adding new ratings

        console.log(vanId);
        console.log(vanReviews);
      

        const vanID = vanId;
        console.log(getRatingsByVanID(vanID)); // Replace 'yourVanID' with the actual van ID from the button's data or wherever you get it from.
        const ratings = getRatingsByVanID(vanID);
        displayRatings(ratings);
      });

      updateButton.addEventListener("click", function(event) {

         // Retrieve the form data
        const vanPhoto = document.getElementById("vanPhotoEdit").files;
        const vanMake = document.getElementById('vanMakeUpdate').value;
        const vanModel = document.getElementById('vanModelUpdate').value;
        const vanYear = document.getElementById('vanYearUpdate').value;
        const vanCapacity = document.getElementById('vanCapacityUpdate').value;
        const plateNumber = document.getElementById('plateNumberUpdate').value;
        const vanRate = document.getElementById('vanRateUpdate').value;
        const vanCR = document.getElementById("vanCREdit").files;
        const vanOR = document.getElementById("vanOREdit").files;

        const errors = [];

          // Perform validation
        if (!/^\d+$/.test(vanCapacity)) {
            errors.push('Capacity must be a valid number.');
        }
        if (plateNumber.length > 7) {
            errors.push('Plate Number must not exceed 7 characters long.');
        }
        if (!/^\d{4}$/.test(vanYear)) {
            errors.push('Year must be exactly 4 digits long.');
        }
        if (!/^\d+(\.\d+)?$/.test(vanRate)) {
            errors.push('Rate must be a valid number or decimal.');
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
        formData.append('vanId', vanId);
        formData.append('vanPhoto', vanPhoto); // Assuming only one file is selected for vanPhoto
        formData.append('vanMake', vanMake);
        formData.append('vanModel', vanModel);
        formData.append('vanYear', vanYear);
        formData.append('vanCapacity', vanCapacity);
        formData.append('plateNumber', plateNumber);
        formData.append('vanRate', vanRate);
        formData.append('vanCR', vanCR); // Assuming only one file is selected for vanCR
        formData.append('vanOR', vanOR);

          // Perform the form submission using fetch or any other AJAX method
        fetch("updateVanDetails.php", {
          method: "POST",
          body: formData
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              console.log("Updated Successfully!");
              
            } else {
              console.log("Update Failed!");
            }
            location.reload();
          })
          .catch(error => {
            const errorMessage = error.toString();
            if (errorMessage.includes("is not valid JSON")) {
              location.reload();
              return;
            }
            
        });
      });

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


      function changeFullSizeImage(imageSrc, title) {
        const fullSizeImage = document.getElementById('fullSizeImage');
        const imageModalLabel = document.getElementById('imageModalLabel');

        // Set the src attribute of the full-size image
        fullSizeImage.src = imageSrc;

        // Set the modal title
        imageModalLabel.textContent = title;
      }
 
      handleImagePreview(vanPhotoEdit, vanPhotoUpdate);
      handleImagePreview(vanCREdit, vanCRUpdate);
      handleImagePreview(vanOREdit, vanORUpdate);
      handleImagePreview(profilePhoto, profilePhotoContainer);

      function getQueryParam(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
      }

      regenerateCards(); // Refresh


      
  </script>
</body>
</html>