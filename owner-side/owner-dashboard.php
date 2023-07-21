<?php

require_once '../db_connect.php';
session_start();
      
$query = "SELECT * FROM van V JOIN owner O ON V.Owner_ID = O.Owner_ID WHERE O_Email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$result = $stmt->get_result();

$owner = $result->fetch_assoc();
$_SESSION['ownerid'] = $owner['Owner_ID']; 
$_SESSION['vanid'] = $owner['Van_ID']; 

$query = "SELECT * FROM van_unavailable_date WHERE Van_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_SESSION['vanid']);
$stmt->execute();
$dates = $stmt->get_result();

$query = "SELECT R.Rental_ID, C_ProfilePic, concat_ws(' ', C_FName, C_LName) as 'C_FullName', C_Email, C_PhoneNo, Destination, Pickup_Address, Pickup_Date, Pickup_Time, Return_Address, Return_Date, Return_Time
        FROM customer C JOIN rental R 
        ON C.Customer_ID = R.Customer_ID
        LEFT JOIN rental_without_driver RWD
        ON R.Rental_ID = RWD.Rental_ID
        LEFT JOIN customer_profile CP
            ON C.Customer_ID = CP.Customer_ID";
$stmt = $conn->prepare($query);
$stmt->execute();
$rentalDetails = $stmt->get_result();

$rentalDetailsArray = array();
while ($row = $rentalDetails->fetch_assoc()) {
  $rentalDetailsArray[] = $row;
}

// Convert rental details array to JSON format
$rentalDetailsJSON = json_encode($rentalDetailsArray);


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
	<link href="dashboard-style.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/c08dde9054.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
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
<div class="bg-primary mb-4">
  <div class="container py-5">
  </div>
</div>
</header>

<section class="">
  <div class="container">
    <div class="row flex-lg-nowrap">
    
      <div class="col vh-100">
        <div class="row">
          <div class="col-lg-8 mb-3">
            <div class="card">
              <div class="card-body">
                <div id='calendar'></div>
              </div>
            </div>
          </div>
    
          <div class="col-lg-4 col-md-3 mb-3">
            <div class="card" id="rentalInfo" >
                <div class="card-body">
                    <div class="row border-bottom pb-2">
                        <div class="col-lg-4 profile-container rounded border shadow ms-2">
                            <img id="customerPicture" class="img-thumbnail square-image">
                        </div>
                        <div class="col-lg-8 mt-2">
                            <h4><span id="customerName"></span></h4>
                            <p class="my-0 text-muted"><span id="customerEmail"></span></p>
                            <p class="my-0 text-muted"><span id="customerPhone"></span></p>
                        </div>
                    </div>
                    <div class="row py-2">
                        <p>Destination: <span id="destination"></span></p>
                        <p>Pickup Address: <span id="pickupAddress"></span></p>
                        <p>Pickup Date: <span id="pickupDate"></span></p>
                        <p>Pickup Time: <span id="pickupTime"></span></p>
                        <p>Return Address: <span id="returnAddress"></span></p>
                        <p>Return Date: <span id="returnDate"></span></p>
                        <p>Return Time: <span id="returnTime"></span></p>
                        <p>Additional Information: <span id="additionalInfo"></span></p>
                    </div>
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

    <!-- Modal Add-->
    <div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="addDate.php">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Unavailable Date</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="start" class="col-form-label">Start date</label>
                            <input type="text" name="start" class="form-control" id="start" readonly>
                        </div>
                        <div class="form-group">
                            <label for="end" class="col-form-label">End date</label>
                            <input type="text" name="end" class="form-control" id="end" readonly>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="deleteDate.php">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Remove this scheduled event?</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
                    </div>

                        <input type="hidden" name="id" class="form-control" id="id">	

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="submit"  class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    

	
	<!-- JAVASCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                themeSystem: 'bootstrap5',
                initialView: 'dayGridMonth',
                selectable: true,
                select: function(info) {
                    var start = moment(info.start).format('YYYY-MM-DD');
                    var end = moment(info.end).subtract(1, 'days').format('YYYY-MM-DD');
                    $('#ModalAdd #start').val(start);
                    $('#ModalAdd #end').val(end);
                    $('#ModalAdd').modal('show');
                },
                eventDidMount: function(info) {
                    var event = info.event;
                    var element = info.el;
                    var doubleClickTimeout;

                    element.addEventListener('click', function() {
                        if (doubleClickTimeout) {
                            clearTimeout(doubleClickTimeout);
                            doubleClickTimeout = null;
                            // Double-click event
                            $('#ModalEdit #id').val(event.id);
                            $('#ModalEdit #title').val(event.title);
                            $('#ModalEdit #color').val(event.backgroundColor);
                            $('#ModalEdit').modal('show');
                        } else {
                            // Single-click event
                            doubleClickTimeout = setTimeout(function() {
                                doubleClickTimeout = null;
                                // Access your rental details and display them in the rentalInfo div here
                                var eventId = event.id;
                                var eventTitle = event.title;
                                var eventStart = moment(event.start).format('YYYY-MM-DD');
                                var eventEnd = event.end ? moment(event.end).subtract(1, 'days').format('YYYY-MM-DD') : eventStart;

                                // Find the rental that matches the event start and end dates
                                var matchedRental = null;
                                var rentalDetails = <?php echo $rentalDetailsJSON; ?>;
                                rentalDetails.forEach(function(rental) {
                                    var rentalStartDate = rental.Pickup_Date;
                                    var rentalEndDate = rental.Return_Date;

                                    if (eventStart >= rentalStartDate && eventEnd <= rentalEndDate) {
                                        matchedRental = rental;
                                        return false; // Exit the loop once a match is found
                                    }
                                });

                                if (matchedRental) {
                                    // Access rental details properties
                                    var customerPicture = matchedRental.C_ProfilePic;
                                    var customerEmail = matchedRental.C_Email;
                                    var customerPhone = matchedRental.C_PhoneNo;
                                    var rentalID = matchedRental.Rental_ID;
                                    var customerFullName = matchedRental.C_FullName;
                                    var destination = matchedRental.Destination;
                                    var pickupAddress = matchedRental.Pickup_Address;
                                    var pickupDate = formatDate(matchedRental.Pickup_Date);
                                    var pickupTime = matchedRental.Pickup_Time;
                                    var returnAddress = matchedRental.Return_Address ? matchedRental.Return_Address : 'Not applicable';
                                    var returnDate = formatDate(matchedRental.Return_Date);
                                    var returnTime = matchedRental.Return_Time;
                                    var rentalDetail;

                                    if (returnAddress !== 'Not applicable' || returnAddress !== null) {
                                        rentalDetail = 'With Driver';
                                    } else {
                                        rentalDetail = 'Without Driver';
                                    }

                                    // Example: Display rental details in the rentalInfo div
                                    $('#rentalInfo #customerPicture').attr('src', customerPicture);
                                    $('#rentalInfo #customerEmail').text(customerEmail);
                                    $('#rentalInfo #customerPhone').text(customerPhone);
                                    $('#rentalInfo #customerName').text(customerFullName);
                                    $('#rentalInfo #destination').text(destination);
                                    $('#rentalInfo #pickupAddress').text(pickupAddress);
                                    $('#rentalInfo #pickupDate').text(pickupDate);
                                    $('#rentalInfo #pickupTime').text(pickupTime);
                                    $('#rentalInfo #returnAddress').text(returnAddress);
                                    $('#rentalInfo #returnDate').text(returnDate);
                                    $('#rentalInfo #returnTime').text(returnTime);
                                    $('#rentalInfo #additionalInfo').text(rentalDetail);
                                }
                            }, 300);
                        }
                    });
                },

                events: [
                    <?php while($row = $dates->fetch_assoc()): ?>
                    {
                        id: '<?php echo $row['XDate_ID']; ?>',
                        title: '<?php echo $row['Status']; ?>',
                        start: '<?php echo $row['Start_Date']; ?>',
                        end: moment('<?php echo $row['End_Date']; ?>').add(1, 'days').format('YYYY-MM-DD'),
                        color: '<?php echo ($row['Status'] === 'Unavailable') ? 'red' : (($row['Status'] === 'Booked') ? 'orange' : 'darkblue'); ?>'
                    },
                    <?php endwhile; ?>
                ]
            });
            calendar.render();
        });

        function formatDate(dateString) {
          var dateParts = dateString.split("-");
          var year = dateParts[0];
          var month = dateParts[1];
          var day = dateParts[2];
          return month + "-" + day + "-" + year;
        }
        </script>

</body>
</html>