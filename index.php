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
			<p class="faq-link">FAQs</p>
			<p class="terms-link" >Terms & Conditions</p>
			<p class="privacy-link" >Privacy Policy</p>
			<p class="contact-link">Contact Us</p>
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

	<!-- Modal Terms-->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="termsModalLabel">Terms & Conditions</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Replace the content of the modal body with the actual Terms & Conditions -->
          <p>
            Welcome to VanGo! These Terms & Conditions govern your use of the VanGo Website as a
            platform to connect van drivers and customers looking to rent vans. By accessing
            or using the Website, you agree to be bound by these Terms. Please read them carefully.
          </p>
          <p>
            <strong>1. Acceptance of Terms</strong>
            <br>
            By creating an account or using any part of the Website, you acknowledge that you have read, understood,
            and agree to be bound by these Terms. If you do not agree with these Terms, you may not use the Website.
          </p>
          <p>
            <strong>2. Account Registration</strong>
            <br>
            2.1. Account Creation: To access the full features of the Website, you must create an account. You will be
            required to provide accurate and up-to-date information during the registration process.
            <br>
            2.2. Account Types: Users can register as either a Traveler or a Driver. The type of account determines the
            services and functionalities available to each user.
            <br>
            2.3. Account Security: You are responsible for maintaining the security and confidentiality of your account
            credentials. You agree to notify VanGo immediately of any unauthorized use of your account or any other
            breach of security.
            <br>
            2.4. Account Usage: You agree not to use the account of another user without permission. VanGo reserves the
            right to suspend or terminate your account if any unauthorized activity is detected.
          </p>
          <p>
            <strong>3. Rental Services</strong>
            <br>
            3.1. Van Listings: Drivers can create listings for their vans, providing accurate and detailed information
            about the vehicle and its availability.
            <br>
            3.2. Booking and Payments: Travelers can browse available vans, make booking requests, and submit payments
            for the rental service. VanGo will facilitate the payment process and may charge a service fee for its
            services.
            <br>
            3.3. Rental Agreements: VanGo is not a party to any rental agreement between Drivers and Travelers. The terms
            and conditions of each rental agreement are solely between the parties involved. VanGo shall not be liable
            for any disputes arising from the rental transaction.
          </p>
          <p>
            <strong>4. User Responsibilities</strong>
            <br>
            4.1. Accurate Information: Users are responsible for providing accurate information during registration, van
            listing, and any other interactions on the Website.
            <br>
            4.2. Compliance with Laws: Users agree to comply with all applicable laws, regulations, and third-party rights
            while using the Website.
            <br>
            4.3. Prohibited Content: Users must not post, upload, or transmit any content that is unlawful, offensive,
            defamatory, or infringing on intellectual property rights.
          </p>
          <p>
            <strong>5. Intellectual Property</strong>
            <br>
            5.1. VanGo Content: All content on the Website, including but not limited to logos, graphics, text, and
            software, is the property of VanGo and is protected by intellectual property laws.
            <br>
            5.2. User Content: By posting content on the Website, users grant VanGo a non-exclusive, worldwide, royalty-free
            license to use, modify, reproduce, and distribute the content for promotional purposes.
          </p>
          <p>
            <strong>6. Limitation of Liability</strong>
            <br>
            6.1. Use at Your Own Risk: The use of the Website is at your own risk. VanGo does not guarantee the accuracy,
            reliability, or availability of the Website's services and content.
            <br>
            6.2. Rental Transactions: VanGo is not responsible for any damages, losses, or disputes arising from rental
            transactions between Drivers and Travelers.
            <br>
            6.3. Indirect Damages: In no event shall VanGo be liable for any indirect, consequential, or incidental damages
            arising from the use of the Website.
          </p>
          <p>
            <strong>7. Modification of Terms</strong>
            <br>
            VanGo reserves the right to modify or update these Terms at any time without prior notice. The updated Terms
            will be posted on the Website, and your continued use of the Website after such changes constitutes acceptance
            of the modified Terms.
          </p>
          <p>
            <strong>8. Governing Law</strong>
            <br>
            These Terms shall be governed by and construed in accordance with the laws of Supreme Court, without
            regard to its conflict of law principles.
          </p>
          <p>
            <strong>9. Contact Us</strong>
            <br>
            If you have any questions or concerns about these Terms or the Website, please contact us at info@vango.com.
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<!-- Modal Terms-->
  <!-- MODAL PRIVACY POLICY -->
  <div class="modal fade" id="privacyModal" tabindex="-1" role="dialog" aria-labelledby="privacyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>
          Last Updated: July 21, 2023
          </p>
          <p>
            Welcome to VanGo! This Privacy Policy describes how VanGo collects,
            uses, and protects personal information obtained through the VanGo Website. By using the
            Website, you consent to the practices described in this Policy.
          </p>
          <p>
            <strong>1. Information We Collect</strong>
            <br>
            1.1. Account Information: When you create an account on the Website, we may collect your name, email address,
            password, and account type (Traveler or Driver).
            <br>
            1.2. Profile Information: Users have the option to provide additional information on their profiles, such as a
            profile picture, location, and phone number.
            <br>
            1.3. Van Listings: If you are a Driver, we collect information about your van, including its make, model, year,
            location, availability, and rental rates.
            <br>
            1.4. Rental Transactions: When you book or rent a van, we collect information related to the rental
            transaction, such as rental dates, payment details, and communication between parties.
            <br>
            1.5. Communication: We may collect information from your communications with us, including customer support
            inquiries.
            <br>
            1.6. Cookies and Tracking Technologies: We use cookies and similar technologies to track user interactions with
            the Website and improve user experience. These technologies collect device information, IP addresses, and
            browsing activity.
          </p>
          <p>
            <strong>2. How We Use Your Information</strong>
            <br>
            2.1. Account Management: We use the collected information to create and manage your account, personalize your
            experience, and provide access to Website features.
            <br>
            2.2. Rental Services: Information related to van listings, bookings, and rental transactions is used to
            facilitate the rental services between Drivers and Travelers.
            <br>
            2.3. Communication: We may use your contact information to communicate with you regarding your account, rental
            requests, and customer support.
            <br>
            2.4. Improvements and Analytics: We analyze user behavior and feedback to improve the Website's functionality,
            content, and user experience.
            <br>
            2.5. Marketing: With your consent, we may use your information to send promotional emails or updates about VanGo
            services and promotions.
          </p>
          <p>
            <strong>3. Information Sharing</strong>
            <br>
            3.1. Rental Transactions: Information shared between Drivers and Travelers is necessary to facilitate rental
            services. Drivers will have access to Travelers' information for successful rental agreements.
            <br>
            3.2. Service Providers: We may share information with third-party service providers who assist us in operating
            the Website, processing payments, or conducting data analysis. These providers are contractually bound to
            protect your information and can only use it for specified purposes.
            <br>
            3.3. Legal Compliance: We may disclose information to comply with legal obligations, enforce our Terms &
            Conditions, respond to legal requests, or protect our rights, privacy, safety, or property.
          </p>
            <strong>4. Data Security</strong>
            <br>
            We implement reasonable security measures to protect your information from unauthorized access, alteration,
            disclosure, or destruction. However, no data transmission over the internet or electronic storage is entirely
            secure. We cannot guarantee the absolute security of your information.
            <br>
            <strong>5. Data Retention</strong>
            <br>
            We retain your information for as long as necessary to fulfill the purposes outlined in this Policy, unless a
            longer retention period is required by law.
            <br>
            <strong>6. Your Choices</strong>
            <br>
            6.1. Account Information: You can update your account information through your account settings.
            Contact us if you need assistance.
            <br>
            6.2. Marketing Communications: You can opt-out of receiving promotional emails by following the instructions in
            the emails. However, we may still send you non-promotional communications regarding your account or rentals.
            <br>
            <strong>7. Children's Privacy</strong>
            <br>
            The Website is not intended for children under the age of 18. We do not knowingly collect personal information
            from individuals under 18 years old. If we become aware that we have inadvertently collected such information, we
            will take steps to delete it.
            <br>
            <strong>8. Changes to this Privacy Policy</strong>
            <br>
            We may update this Privacy Policy from time to time. The "Last Updated" date at the beginning of this Policy
            indicates the most recent revisions. Your continued use of the Website after any changes signifies your
            acceptance of the updated Policy.
            <br>
            <strong>9. Contact Us</strong>
            <br>
            If you have any questions or concerns about this Privacy Policy or your personal information, please contact us
            at info@vango.com.
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- MODAL PRIVACY POLICY-->
  <!-- MODAL FAQs -->
  <div class="modal fade" id="faqModal" tabindex="-1" role="dialog" aria-labelledby="faqModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="faqModalLabel">Frequently Asked Questions (FAQs)</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>
            <strong>General Questions</strong>
          </p>
          <p>
            1. What is VanGo?
            <br>
            VanGo is an online platform that connects drivers with customers who want to rent a van for various
            purposes. Whether you need a van for a road trip, moving, or any other adventure, VanGo makes it easy to find
            the perfect van to rent.
            <br>
            2. How does VanGo work?
            <br>
            As a van owner, you can list your van on VanGo, providing all the necessary details such as location,
            availability, and rental rates. On the other hand, if you're looking to rent a van, you can browse through the
            available listings, make booking requests, and proceed with the rental transaction.
            <br>
            3. Do I need an account to use VanGo?
            <br>
            Yes, you need to create an account to access the full features of VanGo. Account creation is simple and allows
            you to personalize your experience as either a van renter or owner.
            <br>
            4. Is my personal information safe on VanGo?
            <br>
            We take data security seriously and employ industry-standard measures to protect your personal information.
            Please refer to our Privacy Policy for more details on how we handle and safeguard your data.
          </p>
          <p>
            <strong>For Drivers</strong>
          </p>
          <p>
            5. How do I list my van for rent on VanGo?
            <br>
            To list your van, you must first create an account as a Driver. After that, you can log in to your account
            and follow the steps to create your van listing. Make sure to include
            accurate and detailed information about your van to attract potential renters.
            <br>
            6. Can I set my own rental rates and availability?
            <br>
            Absolutely! As a van owner, you have full control over setting your rental rates and deciding when your van is
            available for rent. You can update this information at any time through your account settings.
            <br>
            7. How do I manage rental requests and bookings?
            <br>
            When a potential renter sends a booking request for your van, you will receive a notification. You can review
            the request and accept or decline it based on your van's availability and your preferences. Once you accept a
            booking, you can communicate with the renter to finalize the details.
          </p>
          <p>
            <strong>For Travelers</strong>
          </p>
          <p>
            8. How do I find a van to rent on VanGo?
            <br>
            As a van renter, you can use the search filters on our website to find the ideal van that suits your
            requirements. You can filter by rental dates, van size, and more to narrow down your choices.
            <br>
            9. What should I consider when renting a van?
            <br>
            Before finalizing your rental, make sure to read the van listing carefully. Pay attention to details such as
            the van's specifications, rental rates, and any additional fees. Also,
            don't forget to review any user ratings and feedback left by previous renters.
            <br>
            10. How do I make a booking and payment?
            <br>
            Once you find a van you'd like to rent, click on the "Book Now" button on the listing page. You'll be guided
            through the booking process, and you can securely make the payment for your rental using our platform.
          </p>
          <p>
            <strong>Payment and Fees</strong>
          </p>
          <p>
            11. Does VanGo charge any fees for its services?
            <br>
            Yes, VanGo charges a service fee to cover the costs of operating the platform and providing customer support.
            The service fee will be displayed before you proceed with the booking, so you know the total cost upfront.
            <br>
            12. What payment methods are accepted?
            <br>
            VanGo accepts various payment methods, including credit/debit cards and paypal. You can
            choose your preferred payment method during the checkout process.
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL CONTACT US -->
<div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="contactModalLabel">Contact Us</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>We'd Love to Hear From You!</p>
        <p>Thank you for using VanGo! If you have any questions, suggestions, or need assistance, please don't hesitate to reach out to us. We're here to help!</p>

        <h5>General Inquiries</h5>
        <p>Email: info@vango.com</p>
        <p>Phone: +68 9876543210</p>
        <p>Address: 456 Sta. Mesa, Manila 1000, Philippines</p>

        <p>Reach Out to Us Today!</p>
        <p>Whether you're a driver, traveler, or interested in a business partnership, we're here to help and provide a seamless experience on VanGo. Don't hesitate to contact us through the provided channels, and we'll get back to you as soon as possible.</p>

        <p>Thank you for being a part of the VanGo community!</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- CONTACT US -->
	
	<!-- JAVASCRIPT -->
	<script src="https://kit.fontawesome.com/c08dde9054.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="script.js"></script>

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
			img.src = 'registration/uploads/van_photos/' + vanData.V_Photo;
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
