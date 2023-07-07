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

$query = "SELECT V.Van_ID, V_Photo, concat_ws(' ', V_Make, V_Model, V_Capacity) as 'V_Name', V_Capacity, concat_ws(' ', O_FName, O_LName) as 'O_FullName', V_Rate
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
            <input type="date" id="start-date" placeholder="Start Date" />
            <label for="end-date">Return date:</label>
            <input type="date" id="end-date" placeholder="End Date" />
        </div>
        
      </div>
      <div id="buttons">
        <button class="button-value" onclick="filterVan('all')">Show All</button>
      </div>
      <div id="products"></div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Van Rental Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
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
            <button type="button" class="btn btn-primary" id="save-changes-btn" data-van-id="">Save changes</button>
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
      var filteredVans = vans;

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
              var vanId = getVanIdByName(vanName);
              var pickupDate = document.getElementById("start-date").value;
              var returnDate = document.getElementById("end-date").value;
              //var pickupTime = document.getElementById("pickup-time").value;
              //document.getElementById("return-time").value = pickupTime;

              // Calculate the number of days between pickup date and return date
              var startDate = new Date(pickupDate);
              var endDate = new Date(returnDate);
              var timeDiff = Math.abs(endDate.getTime() - startDate.getTime());
              var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

              // Calculate the total price based on the number of days and van rate
              var vanRate = van.V_Rate ? parseFloat(van.V_Rate) : 0;
              var totalPrice = diffDays * vanRate;

              // Update the modal form dynamically with the van's information, date values, and total price
              document.getElementById("destination").value = ""; // Clear the value of the "Destination" field
              document.getElementById("pickup-date").value = pickupDate;
              document.getElementById("return-date").value = returnDate;
              //document.getElementById("pickup-time").value = pickupTime; 
              //document.getElementById("return-time").value = pickupTime;
              document.getElementById("total-price").value = totalPrice.toFixed(2); // Update the total price field
              // ... Update other fields as needed

              // Set the vanId as the value of data-van-id attribute
              var saveChangesButton = document.querySelector(".modal-footer .btn-primary");
              saveChangesButton.setAttribute("data-van-id", vanId);

              // Show the modal form
              var modal = new bootstrap.Modal(document.getElementById("exampleModalCenter"));
              modal.show();

              console.log(vanId);
              console.log(saveChangesButton);
            });

            
          });
        }
      }

      function filterVan(category) {
        if (category === "all") {
          filteredVans = vans;
        } else {
          filteredVans = vans.filter(function (van) {
            return van.category === category;
          });
        }

        renderVans();
      }
/*
      document.getElementById("search").addEventListener("click", function () {
        var searchInput = document.getElementById("search-input").value.toUpperCase();
        var startDate = document.getElementById("start-date").value;
        var endDate = document.getElementById("end-date").value;

        filteredVans = vans.filter(function (van) {
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
*/    
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

      document.getElementById("pickup-time").addEventListener("input", function() {
        var pickupTime = this.value;
        document.getElementById("return-time").value = pickupTime;
      });

    </script>
  </body>
</html>
