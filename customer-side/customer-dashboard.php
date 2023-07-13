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

$query = "SELECT V.Van_ID, V_Photo, concat_ws(' ', V_Make, V_Model, V_Year) as 'V_Name', V_Capacity, concat_ws(' ', O_FName, O_LName) as 'O_FullName', O_Address, O_PhoneNo, V_Rate, V_PlateNo
        FROM owner O JOIN
            van V ON
            O.Owner_ID = V.Owner_ID
        LEFT JOIN van_rate VR ON
            V.Van_ID = VR.Van_ID";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$vans = [];
while ($row = $result->fetch_assoc()) {
    $vans[] = $row;
}

$query = "SELECT Van_ID, concat_ws(' ', C_FName, C_LName) as 'C_FullName', Review_Rating, Review_Comment, DATE_FORMAT(Review_Datetime, '%Y-%m-%d %H:%i') as 'Review_Datetime'
        FROM customer C JOIN rental RL
          ON C.Customer_ID = RL.Customer_ID
        JOIN review RW
          ON RL.Rental_ID = RW.Rental_ID
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vans Booking</title>
    <!-- Google Font -->
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="style.css" />

  </head>
  <body>
    <div class="wrapper">
      <div id="search-container">
        <input
          type="search"
          id="search-input"
          placeholder="Search van name, capacity, or price here.."
        />
        <div id="date-container">
            <label for="start-date">Pickup date:</label>
            <input type="date" id="start-date" placeholder="Start Date" required>
            <label for="end-date">Return date:</label>
            <input type="date" id="end-date" placeholder="End Date" required>
        </div>
        
      </div>
      <div id="buttons">
        <button class="button-value" onclick="filterVan('all')">Show All</button>
      </div>
      <div id="products"></div>
    </div>

    <!-- Modal for Van Details -->
    <div class="modal fade" id="detailsModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Van Information</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">  
            <div id="vanInfo">
              <img id="vanPhoto" src="" alt="Van Photo">
              <h2 id="vanName"></h2>
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
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#exampleModalCenter" id="book-now-btn" data-van-id="">Book now</button>
          </div>
        </div>
      </div>
    </div>


    <!-- Modal for Rental Details-->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Van Rental Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <div id="error-messages">
              <?php if (!empty($errors)) : ?>
                  <ul class="error">
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
                <input type="date" class="form-control" id="pickup-date" readonly>
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
                <input type="date" class="form-control" id="return-date" readonly>
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
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="save-changes-btn" data-van-id="">Proceed to payment</button>
            </div>
            
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <!-- Script -->
    <script>
      var vans = <?php echo json_encode($vans); ?>;
      var unavailableDates = <?php echo json_encode($unavailableDates); ?>;
      var vanReviews = <?php echo json_encode($vanReviews); ?>;
      var filteredVans = vans;
      var vanId;
      var dailyRateValue = "";

      function renderVans() {
        var vansContainer = document.getElementById("products");
        vansContainer.innerHTML = "";

        if (filteredVans.length === 0) {
          var noResults = document.createElement("p");
          noResults.innerText = "No vans found.";
          vansContainer.appendChild(noResults);
        } else {
          filteredVans.forEach(function (van) {
            var card = document.createElement("div");
            card.classList.add("card");

            var imageContainer = document.createElement("div");
            imageContainer.classList.add("image-container");

            var image = document.createElement("img");
            if (van.hasOwnProperty("V_Photo")) {
              image.setAttribute("src", "../registration/" + van.V_Photo);
            }
            imageContainer.appendChild(image);
            card.appendChild(imageContainer);

            var container = document.createElement("div");
            container.classList.add("container");

            var name = document.createElement("h5");
            name.classList.add("van-name");
            name.innerText = van.V_Name ? van.V_Name.toUpperCase() : "";
            container.appendChild(name);

            var capacity = document.createElement("p");
            capacity.innerText = "Capacity: " + (van.V_Capacity ? van.V_Capacity : "");
            container.appendChild(capacity);

            var owner = document.createElement("p");
            owner.innerText = "Owner: " + (van.O_FullName ? van.O_FullName : "");
            container.appendChild(owner);

            var rate = document.createElement("p");
            rate.innerText = "Rate: ₱" + (van.V_Rate ? van.V_Rate : "");
            container.appendChild(rate);

            card.appendChild(container);
            vansContainer.appendChild(card);

            // Add animation and click functionality to the card element
            card.addEventListener("mouseenter", function () {
              this.classList.add("card-hover");
            });

            card.addEventListener("mouseleave", function () {
              this.classList.remove("card-hover");
            });

            card.addEventListener("click", function () {
              var vanName = this.querySelector(".van-name").innerText;
              vanId = getVanIdByName(vanName);
              var matchingReviews = getVanReviews(vanId);

              console.log(vanId);

              document.getElementById("vanPhoto").setAttribute("src", "../registration/" + van.V_Photo);
              document.getElementById("vanName").innerText = van.V_Name ? van.V_Name.toUpperCase() : "";
              document.getElementById("vanCapacity").innerText = van.V_Capacity ? van.V_Capacity : "";
              document.getElementById("plateNumber").innerText = van.V_PlateNo ? van.V_PlateNo : "";
              document.getElementById("ownerFullName").innerText = van.O_FullName ? van.O_FullName : "";
              document.getElementById("ownerAddress").innerText = van.O_Address ? van.O_Address : "";
              document.getElementById("ownerPhoneNo").innerText = van.O_PhoneNo ? van.O_PhoneNo : "";
              document.getElementById("dailyRate").innerText = van.V_Rate ? van.V_Rate : "";
              dailyRateValue = document.getElementById("dailyRate").innerText;

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

                // Add empty stars
                ratingStars.innerHTML += "&#9734;".repeat(emptyStarsCount);

                reviewRating.appendChild(ratingStars);
                reviewRating.style.marginRight = "10px";
                reviewRatingDateContainer.appendChild(reviewRating);


                var reviewDate = document.createElement("p");
                reviewDate.innerHTML = (vanReview.Review_Datetime ? vanReview.Review_Datetime : "") + "<br>";
                reviewRatingDateContainer.appendChild(reviewDate);

                listItem.appendChild(reviewRatingDateContainer);

                var reviewComment = document.createElement("p");
                reviewComment.innerHTML = (vanReview.Review_Comment ? vanReview.Review_Comment : "") + "<br>";
                listItem.appendChild(reviewComment);

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
              ratingLabel.style.fontWeight = "bold";
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
              reviewCountSpan.style.fontSize = "20px";
              reviewCountSpan.innerText = reviewCount + " reviews";
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
        }
      }

      function filterVan(category) {
        if (category === "all") {
          filteredVans = vans;
          document.getElementById("start-date").value = ""; // Clear the start date field
          document.getElementById("end-date").value = ""; // Clear the end date field
        }
        
        renderVans();
      }
      
      document.getElementById("search-input").addEventListener("input", function() {
        var searchInput = this.value.toUpperCase();
        var startDate = document.getElementById("start-date").value;
        var endDate = document.getElementById("end-date").value;

        filteredVans = vans.filter(function(van) {
          var vanName = van.V_Name.toUpperCase();
          var capacity = van.V_Capacity.toString().toUpperCase();
          var price = van.V_Rate.toString().toUpperCase();
          var isAvailable = isVanAvailable(van, startDate, endDate, unavailableDates);

          return (
            vanName.includes(searchInput) ||
            capacity.includes(searchInput) ||
            price.includes(searchInput)
          ) && isAvailable;
        });

        renderVans();
      });

      // Get the radio buttons and the return address input field
      var radioButtons = document.querySelectorAll('input[name="value-radio"]');
      var returnAddressLabel = document.querySelector('label[for="return-address"]');
      var returnAddressField = document.getElementById("return-address");
      var totalPriceField = document.getElementById("total-price");
      var totalLabel = document.querySelector('label[for="total-price"]');
      var vanDriverRate = 1000; // Additional fee for van with driver

      // Add event listener to the radio buttons
      radioButtons.forEach(function (radioButton) {
        radioButton.addEventListener("change", function () {
          console.log(vanDriverRate);
          console.log(totalPriceField.value);

          var pickupDate = document.getElementById("pickup-date").value;
          var returnDate = document.getElementById("return-date").value;
          var startDate = new Date(pickupDate);
          var endDate = new Date(returnDate);
          var timeDiff = Math.abs(endDate.getTime() - startDate.getTime());
          var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

          if (diffDays === 0) {
            diffDays = 1;
          }

          var withoutDriverRadio = document.getElementById("value-2");
          var withDriverRadio = document.getElementById("value-1");
          
          var tempTotal = parseFloat(totalPriceField.value);

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
            totalPriceField.value = tempTotal.toFixed(2);
           // Add vanDriverRate to the rate if "With Driver" is selected
          }else if(withoutDriverRadio.checked) {
            returnAddressField.style.display = "block"; // Show the return address field
            returnAddressLabel.style.display = "block"; 
            totalLabel.innerText = "Total Price:";

            vanDriverRate = 1000;
            vanDriverRate = diffDays * vanDriverRate;
            tempTotal -= vanDriverRate;

            totalPriceField.value = tempTotal.toFixed(2);
          }
        });
      });

      document.getElementById("book-now-btn").addEventListener("click", function() {
          // Retrieve the necessary form values
          
          var pickupDate = document.getElementById("start-date").value;
          var returnDate = document.getElementById("end-date").value;
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
          document.getElementById("total-price").value = totalPrice.toFixed(2); // Update the total price field

          // Set the vanId as the value of data-van-id attribute
          var saveChangesButton = document.querySelector(".modal-footer .btn-primary");
          saveChangesButton.setAttribute("data-van-id", vanId);

          // // Show the modal form
          // var modal = new bootstrap.Modal(document.getElementById("exampleModalCenter"));
          // modal.show();
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


      // Start Date Field
      document.getElementById("start-date").addEventListener("input", function() {
        var searchInput = document.getElementById("search-input").value.toUpperCase();
        var startDate = this.value;
        var endDate = document.getElementById("end-date").value;

        filteredVans = vans.filter(function(van) {
          var vanName = van.V_Name.toUpperCase();
          var capacity = van.V_Capacity.toString().toUpperCase();
          var price = van.V_Rate.toString().toUpperCase();
          var isAvailable = isVanAvailable(van, startDate, endDate, unavailableDates);

          return (
            vanName.includes(searchInput) ||
            capacity.includes(searchInput) ||
            price.includes(searchInput)
          ) && isAvailable;
        });

        renderVans();
      });

      // End Date Field
      document.getElementById("end-date").addEventListener("input", function() {
        var searchInput = document.getElementById("search-input").value.toUpperCase();
        var startDate = document.getElementById("start-date").value;
        var endDate = this.value;

        filteredVans = vans.filter(function(van) {
          var vanName = van.V_Name.toUpperCase();
          var capacity = van.V_Capacity.toString().toUpperCase();
          var price = van.V_Rate.toString().toUpperCase();
          var isAvailable = isVanAvailable(van, startDate, endDate, unavailableDates);

          return (
            vanName.includes(searchInput) ||
            capacity.includes(searchInput) ||
            price.includes(searchInput)
          ) && isAvailable;
        });

        renderVans();
      });


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

      window.onload = function () {
        renderVans();
      };

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
      
      function calculateAverageRating(reviews) {
        if (reviews.length === 0) {
          return 0;
        }

        var totalRating = reviews.reduce(function (sum, review) {
          return sum + (review.Review_Rating || 0);
        }, 0);

        return totalRating / reviews.length;
      }

      document.getElementById("pickup-time").addEventListener("input", function() {
        var pickupTime = this.value;
        document.getElementById("return-time").value = pickupTime;
      });

    </script>
  </body>
</html>