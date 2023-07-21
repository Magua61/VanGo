<?php

require_once 'db_connect.php';

$query = "SELECT V.Van_ID, V_Photo, concat_ws(' ', V_Make, V_Model, V_Capacity) as 'V_Name', V_Rate, COALESCE(FORMAT(AVG(Review_Rating), 1), 0.0) AS 'Average_Rating'
			FROM van V LEFT JOIN van_photo VP
				ON V.Van_ID = VP.Van_ID
			LEFT JOIN van_rate VR 
				ON V.Van_ID = VR.Van_ID
			LEFT JOIN rental R
				ON V.Van_ID = R.Van_ID
			LEFT JOIN review RW
				ON R.Rental_ID = RW.Rental_ID
			GROUP BY V.Van_ID
			ORDER BY Average_Rating DESC
			LIMIT 3";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();


$vans = [];
while ($row = $result->fetch_assoc()) {
    $vans[] = $row;
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
	<link href="style.css" rel="stylesheet">
</head>
<!-- <body>
    <!-- NAVIGATION BAR -->
	<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
		<div class="container">
			<a class="navbar-brand" href="#"><span class="text-info">Van</span>Go</a> 
			<button aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-bs-target="#navbarSupportedContent" data-bs-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
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
					<li class="nav-item">
						<a class="btn action_btn" href="signin/user-signin.php">Sign in</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
    <!-- CAROUSEL / HOME -->
 
	<div class="carousel slide" data-bs-ride="carousel" id="carouselExampleIndicators">
		<div class="carousel-indicators">
			<button aria-label="Slide 1" class="active" data-bs-slide-to="0" data-bs-target="#carouselExampleIndicators" type="button"></button> <button aria-label="Slide 2" data-bs-slide-to="1" data-bs-target="#carouselExampleIndicators" type="button"></button> <button aria-label="Slide 3" data-bs-slide-to="2" data-bs-target="#carouselExampleIndicators" type="button"></button>
		</div>
		<div class="carousel-inner">
			<div class="carousel-item active" data-bs-interval="5000">
				<img alt="..." class="d-block w-100" src="assets/image4.JPG">
				<div class="carousel-caption">
                    <h1>Van on the go! <br>Anytime, anywhere, wherever.</h1>
				</div>
			</div>
			<div class="carousel-item" data-bs-interval="2000">
				<img alt="..." class="d-block w-100" src="assets/image5.JPG">
				<div class="carousel-caption">
					<h1>Explore the Philippines!</h1>
				</div>
			</div>
			<div class="carousel-item">
				<img alt="..." class="d-block w-100" src="assets/image2.JPG">
				<div class="carousel-caption">
					<h1>Travel with your loved ones!</h1>
				</div>
			</div>
		</div><button class="carousel-control-prev" data-bs-slide="prev" data-bs-target="#carouselExampleIndicators" type="button"><span aria-hidden="true" class="carousel-control-prev-icon"></span> <span class="visually-hidden">Previous</span></button> <button class="carousel-control-next" data-bs-slide="next" data-bs-target="#carouselExampleIndicators" type="button"><span aria-hidden="true" class="carousel-control-next-icon"></span> <span class="visually-hidden">Next</span></button>
	</div>

    <!-- ABOUT -->
	<section class="about section-padding" id="about">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-md-12 col-12">
					<div class="about-img"><img alt="" class="img-fluid" src="assets/van4.jpg"></div>
				</div>
				<div class="col-lg-8 col-md-12 col-12 ps-lg-5 mt-md-5">
					<div class="about-text">
						<h2>Van on the go! <br>
                            Anytime, anywhere, wherever.</h2>
						<p>Make your trip in just a click. We are the best van rental service
                            here in the Philippines. We provide cheap, fast, and reliable vans
                            as we support local van owners by giving them a platform to rent their vans!</a>
					</div>
                    <a href="signin/user-signin.php" class="btn btn-primary">Rent a Van</a>
				</div>
			</div>
		</div>
	</section>

	<!-- SERVICES -->
	<section class="services section-padding" id="services">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="section-header text-center pb-5">
						<h2>Our Services</h2>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12 col-md-12 col-lg-6">
					<div class="card text-white text-center bg-dark pb-2">
						<div class="card-body">
							<i class="fa-solid fa-van-shuttle"></i>
							<h3 class="card-title">Rent a Van</h3>
							<p class="lead">With our wide selection of vans for rent, you can choose which suits you best.</p>
                            <button class="book__now">Read More</button>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-12 col-lg-6">
					<div class="card text-white text-center bg-dark pb-2">
						<div class="card-body">
							<i class="fa-regular fa-handshake"></i>
							<h3 class="card-title">Become our Partner</h3>
							<p class="lead">We support our local van operators by providing a platform to rent their van!</p>
                            <button class="book__now">Read More</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- TOP VAN -->
	<section class="trip section-padding">
        <div class="container">
          <h2 class="section__title">Best Vans</h2>
          <p class="section__subtitle">
            Explore your suitable and preferred type of van. Here you can
            find the right van for you.
          </p>
          <div class="row" id="vanCardsContainer">
            
        
           
          </div>
        </div>
      </section>
	
	<!-- FOOTER -->
	<footer class="footer bg-dark ">
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
	<script src="https://kit.fontawesome.com/c08dde9054.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<script>
		
		const vans = <?php echo json_encode($vans); ?>;

		console.log(vans);

		// Function to create a dynamic card based on the data
		function createCard(vanData) {
			const cardContainer = document.createElement("div");
			cardContainer.classList.add("col-12", "col-md-12", "col-lg-4");

			const card = document.createElement("div");
			card.classList.add("trip__card");

			const img = document.createElement("img");
			img.classList.add("img-fluid");
			img.src = 'registration/' + vanData.V_Photo;
			img.alt = "trip";
			img.style.width = "474px"; // Set the desired width here
			img.style.height = "316px";

			const tripDetails = document.createElement("div");
			tripDetails.classList.add("trip__details");

			const name = document.createElement("p");
			name.textContent = vanData.V_Name + '-Seater';

			const rating = document.createElement("div");
			rating.classList.add("rating");

			const starIcon = document.createElement("i");
			starIcon.classList.add("fas", "fa-star"); // Use Font Awesome class for a solid star icon

			rating.appendChild(starIcon); // Append the star icon to the rating element
			rating.appendChild(document.createTextNode(` ${vanData.Average_Rating}`));

			const bookingPrice = document.createElement("div");
			bookingPrice.classList.add("booking__price");

			const price = document.createElement("div");
			price.classList.add("price");
			price.innerHTML = `<span>From</span> ₱ ${vanData.V_Rate}`;

			const bookNowBtn = document.createElement("button");
			bookNowBtn.classList.add("book__now");
			bookNowBtn.textContent = "Book Now";

			bookNowBtn.addEventListener("click", function() {
				window.location.href = "signin/user-signin.php";
			});

			bookingPrice.appendChild(price);
			bookingPrice.appendChild(bookNowBtn);

			tripDetails.appendChild(name);
			tripDetails.appendChild(rating);
			tripDetails.appendChild(bookingPrice);

			card.appendChild(img);
			card.appendChild(tripDetails);

			cardContainer.appendChild(card);

			return cardContainer;
		}

		// Function to add dynamic cards to the container
		function addCardsToContainer(container, data) {
			data.forEach((van) => {
			const card = createCard(van);
			container.appendChild(card);
			});
		}

		// Get the container element
		const container = document.getElementById("vanCardsContainer");

		// Add dynamic cards to the container
		addCardsToContainer(container, vans);
	</script>

</body> 
</html>
