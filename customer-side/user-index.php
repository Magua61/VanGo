<?php 

require_once '../db_connect.php';
session_start();

$query = "SELECT * FROM customer WHERE C_Email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$result = $stmt->get_result();

$customer = $result->fetch_assoc();
$_SESSION['customerid'] = $customer['Customer_ID']; 

$query = "SELECT * FROM van_unavailable_date";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$unavailableDates = [];
while ($row = $result->fetch_assoc()) {
    $unavailableDates[] = $row;
}

$query = "SELECT V.Van_ID, V_Photo, concat_ws(' ', V_Make, V_Model) as 'V_Name', V_Year, V_Capacity, concat_ws(' ', O_FName, O_LName) as 'O_FullName', O_Address, O_PhoneNo, V_Rate, V_PlateNo
          FROM owner O JOIN van V ON
            O.Owner_ID = V.Owner_ID
          LEFT JOIN van_rate VR ON
            V.Van_ID = VR.Van_ID
          LEFT JOIN van_photo VP ON
            V.Van_ID = VP.Van_ID";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$vans = [];
while ($row = $result->fetch_assoc()) {
    $vans[] = $row;
}

$query = "SELECT Van_ID, Review_Photo, concat_ws(' ', C_FName, C_LName) as 'C_FullName', Review_Rating, Review_Comment, DATE_FORMAT(Review_Datetime, '%Y-%m-%d %H:%i') as 'Review_Datetime'
          FROM customer C JOIN rental RL
            ON C.Customer_ID = RL.Customer_ID
          JOIN review RW
            ON RL.Rental_ID = RW.Rental_ID
          LEFT JOIN review_photo RP
          ON RW.Review_ID = RP.Review_ID
          ORDER BY Review_Datetime DESC"; 
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$vanReviews = [];
while ($row = $result->fetch_assoc()) {
    $vanReviews[] = $row;
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
           <a class="dropdown-item" href="../signin/user-signin.php" >Logout </a>
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
    <div class="row">
      <!-- sidebar -->
      <div class="col-lg-3">
        <!-- Toggle button -->
        <button
                class="btn btn-outline-secondary mb-3 w-100 d-lg-none"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation"
                >
          <span>Show filter</span>
        </button>
        
        <!-- Collapsible wrapper -->
        <div class="collapse card d-lg-block mb-5" id="navbarSupportedContent">
          <div class="accordion" id="accordionPanelsStayOpenExample">

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button
                        class="accordion-button text-dark bg-light"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#panelsStayOpen-collapseOne"
                        aria-expanded="false"
                        aria-controls="panelsStayOpen-collapseOne"
                        >
                  Search
                </button>
              </h2>
              <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingThree">
                <div class="accordion-body">
                  <div class="row mb-3">
                    <div class="col-12">
                      <div class="form-outline">
                        <input type="text" id="typeSearch" class="form-control" placeholder="Toyota 12 Seater..."/>
                      </div>
                    </div>
                  </div>
                  <button type="button" class="btn btn-white w-100 border border-secondary" onclick="searchVans()">Search</button>
                </div>
              </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSix">
                    <button
                            class="accordion-button text-dark bg-light"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapseSix"
                            aria-expanded="false"
                            aria-controls="panelsStayOpen-collapseSix"
                            >
                    Date
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseSix" class="accordion-collapse collapse show" aria-labelledby="headingSix">
                    <div class="accordion-body">
                    <div class="row mb-3">
                        <div class="col-6">
                        <p class="mb-0">
                            Start
                        </p>
                        <div class="form-outline">
                            <input type="date" id="startDate" class="form-control" />
                        </div>
                        </div>
                        <div class="col-6">
                        <p class="mb-0">
                            End
                        </p>
                        <div class="form-outline">
                            <input type="date" id="endDate" class="form-control" />
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
              </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingTwo">
                <button
                        class="accordion-button text-dark bg-light"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#panelsStayOpen-collapseTwo"
                        aria-expanded="true"
                        aria-controls="panelsStayOpen-collapseTwo"
                        >
                  Brands
                </button>
              </h2>
              <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo">
                <div class="accordion-body">
                  <div>
                    <!-- Checked checkbox -->
                    <div class="form-check">
                        <input class="form-check-input brand-checkbox" type="checkbox" value="Mercedes" id="flexCheckChecked1" checked />
                        <label class="form-check-label" for="flexCheckChecked1">Mercedes</label>
                        <span class="badge badge-secondary float-end">120</span>
                    </div>
                    <!-- Checked checkbox -->
                    <div class="form-check">
                        <input class="form-check-input brand-checkbox" type="checkbox" value="Toyota" id="flexCheckChecked2" checked />
                        <label class="form-check-label" for="flexCheckChecked2">Toyota</label>
                        <span class="badge badge-secondary float-end">15</span>
                    </div>
                    <!-- Checked checkbox -->
                    <div class="form-check">
                        <input class="form-check-input brand-checkbox" type="checkbox" value="Mitsubishi" id="flexCheckChecked3" checked />
                        <label class="form-check-label" for="flexCheckChecked3">Mitsubishi</label>
                        <span class="badge badge-secondary float-end">35</span>
                    </div>
                    <!-- Checked checkbox -->
                    <div class="form-check">
                        <input class="form-check-input brand-checkbox" type="checkbox" value="Nissan" id="flexCheckChecked4" checked />
                        <label class="form-check-label" for="flexCheckChecked4">Nissan</label>
                        <span class="badge badge-secondary float-end">89</span>
                    </div>
                    <!-- Default checkbox -->
                    <div class="form-check">
                        <input class="form-check-input brand-checkbox" type="checkbox" value="Honda" id="flexCheckDefault" checked/>
                        <label class="form-check-label" for="flexCheckDefault">Honda</label>
                        <span class="badge badge-secondary float-end">30</span>
                    </div>
                    <!-- Default checkbox -->
                    <div class="form-check">
                        <input class="form-check-input brand-checkbox" type="checkbox" value="Suzuki" id="flexCheckDefault" checked/>
                        <label class="form-check-label" for="flexCheckDefault">Suzuki</label>
                        <span class="badge badge-secondary float-end">30</span>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingThree">
                <button
                        class="accordion-button text-dark bg-light"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#panelsStayOpen-collapseThree"
                        aria-expanded="false"
                        aria-controls="panelsStayOpen-collapseThree"
                        >
                  Price
                </button>
              </h2>
              <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show" aria-labelledby="headingThree">
                <div class="accordion-body">
                  <div class="range">
                    <input type="range" class="form-range" id="customRange1" min="0" max="10000" step="500" onchange="adjustPriceRange()"/>
                  </div>
                  <div class="row mb-3">
                    <div class="col-6">
                      <p class="mb-0">
                        Min
                      </p>
                      <div class="form-outline">
                        <input type="number" id="minPrice" min="0" max="10000" step="500" class="form-control" />
                      </div>
                    </div>
                    <div class="col-6">
                      <p class="mb-0">
                        Max
                      </p>
                      <div class="form-outline">
                        <input type="number" id="maxPrice" min="0" max="10000" step="500" class="form-control" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingThree">
                <button
                        class="accordion-button text-dark bg-light"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#panelsStayOpen-collapseFour"
                        aria-expanded="false"
                        aria-controls="panelsStayOpen-collapseFour"
                        >
                  Capacity
                </button>
              </h2>
              <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingThree">
                <div class="accordion-body">
                  <div class="range">
                    <input type="range" class="form-range" id="customRange2" onchange="adjustSeatCapacity()"/>
                  </div>
                  <div class="row mb-3">
                    <div class="col-12">
                      <p class="mb-0">
                        Seat
                      </p>
                      <div class="form-outline">
                        <input type="number" id="seatCapacity" class="form-control" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingThree">
                <button
                        class="accordion-button text-dark bg-light"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#panelsStayOpen-collapseFive"
                        aria-expanded="false"
                        aria-controls="panelsStayOpen-collapseFive"
                        >
                  Ratings
                </button>
              </h2>
              <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse show" aria-labelledby="headingThree">
              <div class="accordion-body">
                    <!-- Default checkbox -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="5" id="ratingCheckbox1" checked />
                        <label class="form-check-label" for="ratingCheckbox1">
                            <i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </label>
                    </div>
                    <!-- Default checkbox -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="4" id="ratingCheckbox2" checked />
                        <label class="form-check-label" for="ratingCheckbox2">
                            <i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-secondary"></i>
                        </label>
                    </div>
                    <!-- Default checkbox -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="3" id="ratingCheckbox3" checked />
                        <label class="form-check-label" for="ratingCheckbox3">
                            <i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-secondary"></i>
                            <i class="fas fa-star text-secondary"></i>
                        </label>
                    </div>
                    <!-- Default checkbox -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="2" id="ratingCheckbox4" checked />
                        <label class="form-check-label" for="ratingCheckbox4">
                            <i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-secondary"></i><i class="fas fa-star text-secondary"></i>
                            <i class="fas fa-star text-secondary"></i>
                        </label>
                    </div>
                    <!-- Default checkbox -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="ratingCheckbox5" checked />
                        <label class="form-check-label" for="ratingCheckbox5">
                            <i class="fas fa-star text-warning"></i><i class="fas fa-star text-secondary"></i><i class="fas fa-star text-secondary"></i><i class="fas fa-star text-secondary"></i>
                            <i class="fas fa-star text-secondary"></i>
                        </label>
                    </div>
                    <!-- Default checkbox -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="0" id="ratingCheckbox6" checked />
                        <label class="form-check-label" for="ratingCheckbox6">
                            <i class="fas fa-star text-secondary"></i><i class="fas fa-star text-secondary"></i><i class="fas fa-star text-secondary"></i><i class="fas fa-star text-secondary"></i>
                            <i class="fas fa-star text-secondary"></i>
                        </label>
                    </div>
                </div>
              </div>
            </div>

                <button type="button" class="btn btn-white w-100 border border-secondary" onclick="filterVans()" >Apply</button>

             </div>
        </div>
      </div>
      <!-- sidebar -->
      <!-- content -->
      <div class="col-lg-9">
        <header class="d-sm-flex align-items-center border-bottom mb-4 pb-3">
          <strong class="d-block py-2"><span id="itemCount"></span> Items found </strong>
        </header>

        <div id="products"></div>

        <hr />

        <!-- Pagination -->
        <nav aria-label="Page navigation example" class="d-flex justify-content-center mt-3">
          <ul class="pagination">
            <li class="page-item disabled">
              <a class="page-link" href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item">
              <a class="page-link" href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          </ul>
        </nav>
        <!-- Pagination -->
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
  <!-- Modal for Van Details -->
<div class="modal fade" id="detailsModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Van Information</h5>
      </div>
      <div class="modal-body">  
        <div id="vanInfo">
          <img id="vanPhoto" src="" alt="Van Photo" class="w-100 rounded border shadow">
          <h5 class="border-bottom pt-2"><span id="vanName"></span></h5>
          <p>Capacity: <span id="vanCapacity"></span></p>
          <p>Plate Number: <span id="plateNumber"></span></p>
          <p>Owner's Full Name: <span id="ownerFullName"></span></p>
          <p>Owner's Address: <span id="ownerAddress"></span></p>
          <p>Owner's Phone Number: <span id="ownerPhoneNo"></span></p>
          <p>Daily Rate: ₱<span id="dailyRate"></span></p>
        </div>
        <ul id="reviewsList" style="list-style: none;"></ul>
        <hr>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" id="book-now-btn" data-van-id="">Book now</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal for Rental Details-->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Van Rental Details</h5>
      </div>
      <div class="modal-body">
      <div id="error-messages">
          <?php if (!empty($errors)) : ?>
              <ul class="error"  style="color: red;">
                  <?php foreach ($errors as $error) : ?>
                      <li><?php echo $error; ?></li>
                  <?php endforeach; ?>
              </ul>
          <?php endif; ?>
      </div>
        <form>
          <div class="radio-input">
            <label class="radio" >
              <input value="value-2" name="value-radio" id="value-2" type="radio" checked="">
              <span class="name" >Without Driver</span>
            </label>
            <label class="radio" >
              <input value="value-1" name="value-radio" id="value-1" type="radio">
              <span class="name" >With Driver</span>
            </label>
            <span class="selection"></span>
          </div>
          <div class="form-group">
            <br/>
            <label for="destination">Destination:</label>
            <input type="text" class="form-control" id="destination" placeholder="Enter destination" required>
          </div>
          <div class="form-group">
            <label for="pickup-address">Pickup Address:</label>
            <input type="text" class="form-control" id="pickup-address" placeholder="Enter pickup address" required>
          </div>
          <div class="form-group">
            <label for="pickup-date">Pickup Date:</label>
            <input type="date" class="form-control" id="pickup-date" >
          </div>
          <div class="form-group">
            <label for="pickup-time">Pickup Time:</label>
            <input type="time" class="form-control" id="pickup-time" required>
          </div>
          <div class="form-group">
            <label for="return-address" id="return-address-label" >Return Address:</label>
            <input type="text" class="form-control" id="return-address" placeholder="Enter return address">
          </div>
          <div class="form-group">
            <label for="return-date">Return Date:</label>
            <input type="date" class="form-control" id="return-date" >
          </div>
          <div class="form-group">
            <label for="return-time">Return Time:</label>
            <input type="time" class="form-control"  id="return-time" readonly>
          </div>
          <div class="form-group">
            <label for="total-price">Total Price:</label>
            <input type="number" class="form-control" id="total-price" readonly>
          </div>
        </form>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="save-changes-btn" data-van-id="">Proceed to payment</button>
        </div>
        
    </div>
  </div>
  
</div>
	
	<!-- JAVASCRIPT -->
  <script src="https://kit.fontawesome.com/c08dde9054.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
      var vans = <?php echo json_encode($vans); ?>;
      var unavailableDates = <?php echo json_encode($unavailableDates); ?>;
      var vanReviews = <?php echo json_encode($vanReviews); ?>;
      var vanId;
      var dailyRateValue = "";
      var filteredVans = vans.slice();
      var vanDriverRate = 1000;

      function renderVans() {
          var vansContainer = document.getElementById("products");
          vansContainer.innerHTML = "";

          var row = document.createElement("div");
          row.classList.add("row");

          if (filteredVans.length === 0) {
              var itemCountElement = document.getElementById("itemCount");
              itemCountElement.textContent = filteredVans.length;

              var noResults = document.createElement("p");
              noResults.innerText = "No vans found.";
              vansContainer.appendChild(noResults);

          } else {
              filteredVans.forEach(function (van) {
                  var vanReviewsForVan = vanReviews.filter(function (review) {
                      return review.Van_ID === van.Van_ID;
                  });

                  var card = document.createElement("div");
                  card.classList.add("col-lg-4", "col-md-6", "col-sm-6", "d-flex");
                  row.appendChild(card);

                  var innerCard = document.createElement("div");
                  innerCard.classList.add("card", "w-100", "my-2", "shadow");
                  card.appendChild(innerCard);

                  var image = document.createElement("img");
                  if (van.hasOwnProperty("V_Photo")) {
                      image.setAttribute("src", "../registration/" + van.V_Photo);
                  }
                  image.classList.add("card-img-top","w-100","h-50");
                  innerCard.appendChild(image);

                  var cardBody = document.createElement("div");
                  cardBody.classList.add("card-body", "d-flex", "flex-column");
                  innerCard.appendChild(cardBody);

                  var priceContainer = document.createElement("div");
                  priceContainer.classList.add("d-flex", "flex-row");
                  cardBody.appendChild(priceContainer);

                  var price = document.createElement("h5");
                  price.classList.add("mb-1", "me-1");
                  price.innerText = "₱" + (van.V_Rate ? van.V_Rate : "");
                  priceContainer.appendChild(price);

                  var description = document.createElement("p");
                  description.classList.add("card-text");
                  description.innerText = (van.V_Name ? van.V_Name : "") + " " + (van.V_Capacity ? van.V_Capacity : "") + "-Seater";
                  cardBody.appendChild(description);

                  var buttonContainer = document.createElement("div");
                  buttonContainer.classList.add("d-flex", "align-items-end", "pt-3", "px-0", "pb-0", "mt-auto");
                  cardBody.appendChild(buttonContainer);

                  var bookButton = document.createElement("a");
                  bookButton.classList.add("btn", "btn-primary", "shadow-0", "me-1");
                  bookButton.innerText = "Book";
                  bookButton.setAttribute("data-van-name", van.V_Name);
                  buttonContainer.appendChild(bookButton);

                  var ratingContainer = document.createElement("div");
                  ratingContainer.classList.add("rating");
                  buttonContainer.appendChild(ratingContainer);

                  var starIcon = document.createElement("i");
                  starIcon.classList.add("fa-solid", "fa-star");
                  starIcon.style.color = "#fed445";
                  ratingContainer.appendChild(starIcon);

                  var averageRating = calculateAverageRating(vanReviewsForVan);
                  var ratingValue = document.createTextNode(" " + averageRating.toFixed(1));
                  ratingContainer.appendChild(ratingValue);

                  vansContainer.appendChild(row);

                  bookButton.addEventListener("click", function() {
                      var vanName = this.getAttribute("data-van-name");;
                      vanId = getVanIdByName(vanName);
                      var matchingReviews = getVanReviews(vanId);

                      document.getElementById("vanPhoto").setAttribute("src", "../registration/" + van.V_Photo);
                      document.getElementById("vanName").innerText = van.V_Name ? van.V_Name.toUpperCase() + " " + (van.V_Year ? van.V_Year : "") : "";
                      document.getElementById("vanCapacity").innerText = van.V_Capacity ? van.V_Capacity : "";
                      document.getElementById("plateNumber").innerText = van.V_PlateNo ? van.V_PlateNo : "";
                      document.getElementById("ownerFullName").innerText = van.O_FullName ? van.O_FullName : "";
                      document.getElementById("ownerAddress").innerText = van.O_Address ? van.O_Address : "";
                      document.getElementById("ownerPhoneNo").innerText = van.O_PhoneNo ? van.O_PhoneNo : "";
                      document.getElementById("dailyRate").innerText = van.V_Rate ? van.V_Rate : "";
                      dailyRateValue = document.getElementById("dailyRate").innerText;

                      var vanPhoto = document.getElementById("vanPhoto");
                      vanPhoto.style.width = "90%";
                      vanPhoto.style.height = "auto";

                      var reviewsList = document.getElementById("reviewsList");
                      reviewsList.innerHTML = "";

                      matchingReviews.forEach(function(vanReview) {
                        var listItem = document.createElement("li");

                        var reviewCustomer = document.createElement("p");
                        var customerName = vanReview.C_FullName ? vanReview.C_FullName : "";
                        var modifiedName = customerName.replace(/\B\w(?=\w)/g, "*");
                        reviewCustomer.innerHTML = "<br>" + modifiedName + "<br>";
                        reviewCustomer.style.fontWeight = "bold";
                        reviewCustomer.style.fontSize = "18px";
                        listItem.appendChild(reviewCustomer);

                        var reviewRatingDateContainer = document.createElement("div");
                        reviewRatingDateContainer.style.display = "flex";

                        var reviewRating = document.createElement("p");
                        var ratingStars = document.createElement("span");

                        var roundedRating = Math.round(vanReview.Review_Rating); // Rounded rating value
                        var emptyStarsCount = 5 - roundedRating; // Number of empty stars to add

                        // Add filled stars based on the rounded rating
                        ratingStars.innerHTML = "&#9733;".repeat(roundedRating);
                        ratingStars.style.color = "gold";
                        ratingStars.style.fontSize = "20px";
                        

                        // Add empty stars
                        ratingStars.innerHTML += "&#9734;".repeat(emptyStarsCount);

                        reviewRating.appendChild(ratingStars);
                        reviewRating.style.marginRight = "10px";
                        reviewRatingDateContainer.appendChild(reviewRating);


                        var reviewDate = document.createElement("p");
                        reviewDate.innerHTML = (vanReview.Review_Datetime ? vanReview.Review_Datetime : "") + "<br>";
                        reviewDate.classList.add("text-muted","pt-1");
                        reviewRatingDateContainer.appendChild(reviewDate);

                        listItem.appendChild(reviewRatingDateContainer);

                        var reviewComment = document.createElement("p");
                        reviewComment.innerHTML = (vanReview.Review_Comment ? vanReview.Review_Comment : "") + "<br>";
                        listItem.appendChild(reviewComment);

                        if (vanReview.Review_Photo) {
                          var reviewPhoto = document.createElement("img");
                          reviewPhoto.src = "../registration/uploads/reviews/" + vanReview.Review_Photo;
                          reviewPhoto.style.width = "200px"; // Adjust the width as needed
                          reviewPhoto.style.height = "auto"; // Adjust the height as needed
                          listItem.appendChild(reviewPhoto);
                        }

                        reviewsList.appendChild(listItem);
                      });

                      // Calculate average rating and review count
                      var averageRating = calculateAverageRating(matchingReviews);
                      var reviewCount = matchingReviews.length;

                      // Display average rating and review count
                      var overallRatingContainer = document.createElement("div");
                      overallRatingContainer.classList.add("overall-rating");

                      var ratingLabel = document.createElement("span");
                      ratingLabel.innerText = "Van Ratings: ";
                      ratingLabel.style.fontSize = "22px";
                      overallRatingContainer.appendChild(ratingLabel);

                      var averageRatingSpan = document.createElement("span");
                      averageRatingSpan.id = "num";
                      averageRatingSpan.style.fontSize = "20px";
                      averageRatingSpan.innerText = averageRating.toFixed(1) + " ";
                      overallRatingContainer.appendChild(averageRatingSpan);

                      var starRatingSpan = document.createElement("span");
                      starRatingSpan.id = "stars";
                      starRatingSpan.style.fontSize = "20px";
                      starRatingSpan.style.color = "gold";

                      var roundedRating = Math.round(averageRating); // Rounded rating value
                      var emptyStarsCount = 5 - roundedRating; // Number of empty stars to add

                      // Add filled stars based on the rounded rating
                      starRatingSpan.innerHTML = "&#9733;".repeat(roundedRating);

                      // Add empty stars
                      starRatingSpan.innerHTML += "&#9734;".repeat(emptyStarsCount);

                      overallRatingContainer.appendChild(starRatingSpan);

                      var reviewCountSpan = document.createElement("span");
                      reviewCountSpan.id = "total";
                      reviewCountSpan.style.fontSize = "15px";
                      reviewCountSpan.innerText = "("+ reviewCount + " reviews)";
                      reviewCountSpan.classList.add("text-muted")
                      overallRatingContainer.appendChild(reviewCountSpan);

                      // Remove existing overall rating container, if any
                      var existingOverallRatingContainer = document.querySelector(".overall-rating");
                      if (existingOverallRatingContainer) {
                        existingOverallRatingContainer.remove();
                      }

                      // Append the overall rating container to the modal body
                      var modalBody = document.querySelector(".modal-body");
                      modalBody.insertBefore(overallRatingContainer, reviewsList);

                      var modal = new bootstrap.Modal(document.getElementById("detailsModalCenter"));
                      modal.show();
                    });
              });

              var itemCountElement = document.getElementById("itemCount");
              itemCountElement.textContent = filteredVans.length;
          }
      }

      function calculateAverageRating(reviews) {
          if (reviews.length === 0) {
              return 0;
          }

          var totalRating = reviews.reduce(function (sum, review) {
              return sum + review.Review_Rating;
          }, 0);

          return totalRating / reviews.length;
      }

      function isVanMatchingSeatCapacity(van, seatCapacity) {
          if (seatCapacity === null || seatCapacity === "") {
              return true; // Skip seat capacity filtering
          }
          return van.V_Capacity === seatCapacity;
      }

      function getVanIdByName(vanName) {
          var matchingVan = vans.find(function (van) {
            return van.V_Name.toUpperCase() === vanName.toUpperCase();
          });

          if (matchingVan) {
            return matchingVan.Van_ID;
          }

          return null;
      }

      function getVanReviews(vanId) {
          return vanReviews.filter(function(review) {
            return review.Van_ID === vanId;
          });
      }

      function searchVans() {
          var keyword = document.getElementById("typeSearch").value.trim().toLowerCase();
          var brandCheckboxes = document.getElementsByClassName("brand-checkbox");
          var minPriceInput = document.getElementById("minPrice");
          var maxPriceInput = document.getElementById("maxPrice");
          var seatCapacityInput = document.getElementById("seatCapacity");
          var checkedCheckboxes = document.querySelectorAll('.form-check-input');

          for (var i = 0; i < brandCheckboxes.length; i++) {
              brandCheckboxes[i].checked = true; // Set the "checked" property to true
          }

          for (var i = 0; i < checkedCheckboxes.length; i++) {
              checkedCheckboxes[i].checked = true; // Set the "checked" property to true
          }

          minPriceInput.value = "";
          maxPriceInput.value = "";
          seatCapacityInput.value = "";

          // Perform van filtering based on the keyword
          filteredVans = vans.filter(function (van) {
              var vanName = van.V_Name ? van.V_Name.toLowerCase() : "";
              var vanCapacity = van.V_Capacity ? van.V_Capacity.toString().toLowerCase() : "";
              return vanName.includes(keyword) || vanCapacity.includes(keyword);

              return (
              vanName.includes(keyword) &&
              vanCapacity.includes(keyword) 
              );
          });

          // Render the filtered vans
          renderVans();
      }

      function isVanAvailable(van, startDate, endDate, unavailableDates) {
        var vanId = van.Van_ID;

        // Check if the van's ID matches and the start or end date falls within the unavailable dates
        var overlappingUnavailableDate = unavailableDates.find(function (date) {
          return date.Van_ID === vanId && (
            (date.Start_Date <= startDate && startDate <= date.End_Date) ||
            (date.Start_Date <= endDate && endDate <= date.End_Date)
          );
        });

        return !overlappingUnavailableDate; // Return true if no overlapping entry found, indicating availability
      }

      function checkVanAvailability(vanId, startDate, endDate) {
        var overlappingUnavailableDate = unavailableDates.find(function (date) {
          return (
            date.Van_ID === vanId &&
            ((date.Start_Date <= startDate && startDate <= date.End_Date) ||
              (date.Start_Date <= endDate && endDate <= date.End_Date))
          );
        });

        return !overlappingUnavailableDate;
      }

      function adjustPriceRange() {
          var rangeInput = document.getElementById("customRange1");
          var minPriceInput = document.getElementById("minPrice");
          var maxPriceInput = document.getElementById("maxPrice");

          // Update the price inputs based on the range input value
          minPriceInput.value = rangeInput.value;
          maxPriceInput.value = rangeInput.value;
      }

      function adjustSeatCapacity() {
          var rangeValue = document.getElementById("customRange2").value;
          var seatCapacityInput = document.getElementById("seatCapacity");
          seatCapacityInput.value = rangeValue;
      }

      function filterVans() {
          var selectedBrands = [];
          var brandCheckboxes = document.getElementsByClassName("brand-checkbox");
          var searchField = document.getElementById("typeSearch");
          searchField.value = "";

          for (var i = 0; i < brandCheckboxes.length; i++) {
              if (brandCheckboxes[i].checked) {
              selectedBrands.push(brandCheckboxes[i].value);
              }
          }

          var minPriceInput = document.getElementById("minPrice");
          var maxPriceInput = document.getElementById("maxPrice");
          var minPrice = minPriceInput.value !== "" ? parseInt(minPriceInput.value) : -Infinity;
          var maxPrice = maxPriceInput.value !== "" ? parseInt(maxPriceInput.value) : Infinity;

          var seatCapacityInput = document.getElementById("seatCapacity");
          var seatCapacity = seatCapacityInput.value !== "" ? parseInt(seatCapacityInput.value) : null;

          var checkedCheckboxes = document.querySelectorAll('.form-check-input:checked');
          var selectedRatings = Array.from(checkedCheckboxes).map(function (checkbox) {
              return parseInt(checkbox.value);
          });

          var startDate =document.getElementById("startDate").value;
          var endDate = document.getElementById("endDate").value;

          filteredVans = vans.filter(function (van) {
              var brand = van.V_Name ? van.V_Name.split(" ")[0] : "";
              var vanPrice = parseInt(van.V_Rate);
              var vanRating = calculateAverageRating(vanReviews.filter(function (review) {
              return review.Van_ID === van.Van_ID;
              }));

              return (
              (selectedBrands.length === 0 || selectedBrands.includes(brand)) &&
              (vanPrice >= minPrice && vanPrice <= maxPrice) &&
              isVanMatchingSeatCapacity(van, seatCapacity) &&
              (selectedRatings.length === 0 || selectedRatings.includes(vanRating)) &&
              isVanAvailable(van, startDate, endDate, unavailableDates)
              );
          });

          renderVans();
      }

      // Get the radio buttons and the return address input field
      var radioButtons = document.querySelectorAll('input[name="value-radio"]');
      var returnAddressLabel = document.querySelector('label[for="return-address"]');
      var returnAddressField = document.getElementById("return-address");
      var totalPriceField = document.getElementById("total-price");
      var totalLabel = document.querySelector('label[for="total-price"]');

      // Add event listener to the radio buttons
      radioButtons.forEach(function (radioButton) {
        radioButton.addEventListener("change", function () {

          var pickupDate = document.getElementById("pickup-date").value;
          var returnDate = document.getElementById("return-date").value;
          var totalPrice = document.getElementById("total-price").value;

          var startDate = new Date(pickupDate);
          var endDate = new Date(returnDate);
          var timeDiff = Math.abs(endDate.getTime() - startDate.getTime());
          var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

          if (diffDays === 0) {
            diffDays = 1;
          }

          var withoutDriverRadio = document.getElementById("value-2");
          var withDriverRadio = document.getElementById("value-1");
          
          var tempTotal = parseFloat(totalPrice);

          var selectedVanId = document.querySelector(".modal-footer .btn-primary").getAttribute("data-van-id");
          var selectedVan = vans.find(function (van) {
            return van.Van_ID === selectedVanId;
          });

          if (withDriverRadio.checked) {
            returnAddressField.style.display = "none"; // Hide the return address field
            returnAddressLabel.style.display = "none"; 
            totalLabel.innerText = "Total Price (includes Driver's Fee):";

            vanDriverRate = 1000;
            vanDriverRate = diffDays * vanDriverRate;
            tempTotal += vanDriverRate;

            document.getElementById("total-price").value = tempTotal ? tempTotal.toFixed(2) : "0.00";
           // Add vanDriverRate to the rate if "With Driver" is selected
          }else if(withoutDriverRadio.checked) {
            returnAddressField.style.display = "block"; // Show the return address field
            returnAddressLabel.style.display = "block"; 
            totalLabel.innerText = "Total Price:";

            vanDriverRate = 1000;
            vanDriverRate = diffDays * vanDriverRate;
            tempTotal -= vanDriverRate;

            document.getElementById("total-price").value = tempTotal ? tempTotal.toFixed(2) : "0.00";
          }
        });
      });

      document.getElementById("pickup-time").addEventListener("input", function() {
        var pickupTime = this.value;
        document.getElementById("return-time").value = pickupTime;
      });

      document.getElementById("book-now-btn").addEventListener("click", function() {
          // Retrieve the necessary form values
          
          var pickupDate = document.getElementById("startDate").value;
          var returnDate = document.getElementById("endDate").value;
          var withoutDriverRadio = document.getElementById("value-2");

          console.log(vanId);

          withoutDriverRadio.checked = true;

          // Calculate the number of days between pickup date and return date
          var startDate = new Date(pickupDate);
          var endDate = new Date(returnDate);
          var timeDiff = Math.abs(endDate.getTime() - startDate.getTime());
          var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

          if (diffDays === 0) {
            diffDays = 1;
          }

          // Calculate the total price based on the number of days and van rate
          var vanRate = parseFloat(dailyRateValue);
          var totalPrice = diffDays * vanRate;
          
          console.log(vanRate);
          console.log(totalPrice);

          // Update the modal form dynamically with the van's information, date values, and total price
          document.getElementById("destination").value = ""; // Clear the value of the "Destination" field
          document.getElementById("pickup-date").value = pickupDate;
          document.getElementById("return-date").value = returnDate;
          document.getElementById("total-price").value = totalPrice ? totalPrice.toFixed(2) : "0.00"; // Update the total price field

          // Set the vanId as the value of data-van-id attribute
          var saveChangesButton = document.querySelector(".modal-footer .btn-primary");
          saveChangesButton.setAttribute("data-van-id", vanId);
      });

      document.getElementById("save-changes-btn").addEventListener("click", function() {
        // Retrieve the values of the fields in the modal form
        var withoutDriverRadio = document.getElementById("value-2");
        var destination = document.getElementById("destination").value;
        var pickupAddress = document.getElementById("pickup-address").value;
        var pickupDate = document.getElementById("pickup-date").value;
        var pickupTime = document.getElementById("pickup-time").value;
        var returnAddress = document.getElementById("return-address").value;
        var returnDate = document.getElementById("return-date").value;
        var returnTime = document.getElementById("return-time").value;
        var totalPrice = document.getElementById("total-price").value;
        var isVanAvailable = checkVanAvailability(vanId, pickupDate, returnDate);

        
        // Create a new form element
        var form = document.createElement("form");
        form.setAttribute("method", "POST");
        form.setAttribute("action", "payment.php");

        // Create input elements and append them to the form
        var vanIdInput = document.createElement("input");
        vanIdInput.setAttribute("type", "hidden");
        vanIdInput.setAttribute("name", "vanId");
        vanIdInput.setAttribute("value", vanId);
        form.appendChild(vanIdInput);

        var destinationInput = document.createElement("input");
        destinationInput.setAttribute("type", "hidden");
        destinationInput.setAttribute("name", "destination");
        destinationInput.setAttribute("value", destination);
        form.appendChild(destinationInput);

        var pickupAddressInput = document.createElement("input");
        pickupAddressInput.setAttribute("type", "hidden");
        pickupAddressInput.setAttribute("name", "pickupAddress");
        pickupAddressInput.setAttribute("value", pickupAddress);
        form.appendChild(pickupAddressInput);

        var pickupDateInput = document.createElement("input");
        pickupDateInput.setAttribute("type", "hidden");
        pickupDateInput.setAttribute("name", "pickupDate");
        pickupDateInput.setAttribute("value", pickupDate);
        form.appendChild(pickupDateInput);

        var pickupTimeInput = document.createElement("input");
        pickupTimeInput.setAttribute("type", "hidden");
        pickupTimeInput.setAttribute("name", "pickupTime");
        pickupTimeInput.setAttribute("value", pickupTime);
        form.appendChild(pickupTimeInput);

        var returnAddressInput = document.createElement("input");
        returnAddressInput.setAttribute("type", "hidden");
        returnAddressInput.setAttribute("name", "returnAddress");
        returnAddressInput.setAttribute("value", returnAddress);
        form.appendChild(returnAddressInput);

        var returnDateInput = document.createElement("input");
        returnDateInput.setAttribute("type", "hidden");
        returnDateInput.setAttribute("name", "returnDate");
        returnDateInput.setAttribute("value", returnDate);
        form.appendChild(returnDateInput);

        var returnTimeInput = document.createElement("input");
        returnTimeInput.setAttribute("type", "hidden");
        returnTimeInput.setAttribute("name", "returnTime");
        returnTimeInput.setAttribute("value", returnTime);
        form.appendChild(returnTimeInput);

        var totalPriceInput = document.createElement("input");
        totalPriceInput.setAttribute("type", "hidden");
        totalPriceInput.setAttribute("name", "totalPrice");
        totalPriceInput.setAttribute("value", totalPrice);
        form.appendChild(totalPriceInput);

            // Remove existing error messages
        var errorMessages = document.getElementById("error-messages");
        if (errorMessages) {
            errorMessages.innerHTML = "";
        }

        // Validate form fields
        var errors = [];
        if (destination.trim() === "") {
            errors.push("Destination is required.");
        }
        if (pickupAddress.trim() === "") {
            errors.push("Pickup Address is required.");
        }
        if (pickupTime.trim() === "") {
            errors.push("Pickup Time is required.");
        }
        if (withoutDriverRadio.checked && returnAddress.trim() === "") {
            errors.push("Return Address is required.");
        }
        if (pickupDate > returnDate) {
            errors.push("Return date must be after the pickup date.");
        }
        if (!isVanAvailable) {
            errors.push("The van is not available for these dates.");
        }
    
        // If there are errors, display them and prevent form submission
        if (errors.length > 0) {
            var errorList = document.createElement("ul");
            errorList.className = "error";
            errors.forEach(function(error) {
                var listItem = document.createElement("li");
                listItem.textContent = error;
                errorList.appendChild(listItem);
            });
            errorMessages.appendChild(errorList);
            return;
        }

        // Append the form to the document body and submit it
        document.body.appendChild(form);
        form.submit();
      });

      document.getElementById("pickup-date").addEventListener("input", updateTotalPrice);
      document.getElementById("return-date").addEventListener("input", updateTotalPrice);

      function updateTotalPrice() {
        var vanDriverRate = 1000;
        console.log(vanDriverRate);
        var withDriverRadio = document.getElementById("value-1");

        var pickupDate = new Date(document.getElementById("pickup-date").value);
        var returnDate = new Date(document.getElementById("return-date").value);

        console.log(pickupDate);
        console.log(returnDate);

        var startDate = new Date(pickupDate);
        var endDate = new Date(returnDate);
        var timeDiff = Math.abs(endDate.getTime() - startDate.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

        if (diffDays === 0) {
          diffDays = 1;
        }

        // Calculate the total price based on the number of days and van rate
        var vanRate = parseFloat(dailyRateValue);
        var totalPrice = diffDays * vanRate;
        
        if (withDriverRadio.checked) {
          vanDriverRate = diffDays * vanDriverRate;
          totalPrice += vanDriverRate;
          console.log(totalPrice);
        }
        
        document.getElementById("total-price").value = totalPrice ? totalPrice.toFixed(2) : "0.00";
      }


      window.onload = function () {
        renderVans();
      };
  </script>

	
</body>
</html>
