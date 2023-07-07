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

$query = "SELECT V_Photo, concat_ws(' ', V_Make, V_Model, V_Capacity) as 'V_Name', V_Capacity, concat_ws(' ', O_FName, O_LName) as 'O_FullName', V_Rate
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
    <!-- Stylesheet -->
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <div class="wrapper">
      <div id="search-container">
        <input
          type="search"
          id="search-input"
          placeholder="Search van name here.."
        />
        <button id="search">Search</button>
      </div>
      <div id="buttons">
        <button class="button-value" onclick="filterProduct('all')">All</button>
        <button class="button-value" onclick="filterProduct('Topwear')">
          Topwear
        </button>
        <button class="button-value" onclick="filterProduct('Bottomwear')">
          Bottomwear
        </button>
        <button class="button-value" onclick="filterProduct('Jacket')">
          Jacket
        </button>
        <button class="button-value" onclick="filterProduct('Watch')">
          Watch
        </button>
      </div>
      <div id="products"></div>
    </div>
    
    <!-- Script -->
    <script>
        var vans = <?php echo json_encode($vans); ?>;
        var filteredVans = vans;

        function renderVans() {
            var vansContainer = document.getElementById("products");
            vansContainer.innerHTML = "";

            if (filteredVans.length === 0) {
                var noResults = document.createElement("p");
                noResults.innerText = "No vans found.";
                vansContainer.appendChild(noResults);
            } else {
                filteredVans.forEach(function(van) {
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

                    var rate = document.createElement("h6");
                    rate.innerText = "Rate: $" + (van.V_Rate ? van.V_Rate : "");
                    container.appendChild(rate);

                    card.appendChild(container);
                    vansContainer.appendChild(card);
                });
            }
        }

        function filterProduct(category) {
            if (category === "all") {
                filteredVans = vans;
            } else {
                filteredVans = vans.filter(function(van) {
                    return van.category === category;
                });
            }

            renderVans();
        }

        document.getElementById("search").addEventListener("click", function() {
            var searchInput = document.getElementById("search-input").value.toUpperCase();
            filteredVans = vans.filter(function(van) {
                return van.V_Name.toUpperCase().includes(searchInput);
            });

            renderVans();
        });

        window.onload = function() {
            renderVans();
        };
    </script>
  </body>
</html>
