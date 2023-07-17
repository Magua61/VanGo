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
                          <span style="color: rgb(166, 168, 170); font: bold 8pt Arial;">140x140</span>
                        </div>
                      </div>
                    </div>
                    <div class="col d-flex flex-column flex-sm-row justify-content-between mb-3">
                      <div class=" text-sm-left mb-2 mb-sm-0">
                        <h4 class="pt-sm-2 pb-1 mb-0 text-nowrap">Juan Dela Cruz</h4>
                        <p class="mb-0 text-muted">@juandelacruz@example.com</p>
                        <div class="mt-2">
                          <button class="btn btn-primary" type="button">
                            <i class="fa fa-fw fa-camera"></i>
                            <span>Change Photo</span>
                          </button>
                        </div>
                      </div>
                      <div class="text-center text-sm-right">
                        <div class="text-muted"><small>Joined 09 Dec 2017</small></div>
                      </div>
                    </div>
                  </div>
                  <ul class="nav nav-tabs">
                    <li class="nav-item"><a href="#profile" class="active nav-link" data-bs-toggle="tab" role="tab" aria-controls="profile" aria-selected="true">Personal Details</a></li>
                    <li class="nav-item"><a href="#history" class="nav-link" data-bs-toggle="tab" role="tab" aria-controls="history" aria-selected="false">History</a></li>
                  </ul>
                  <div class="tab-content pt-3">
                    <div class="tab-pane active" id="profile">
                      <form class="form" novalidate="">
                        <div class="row">
                          <div class="col">
                            <div class="row">
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label>First Name</label>
                                  <input class="form-control" type="text" name="name" placeholder="Juan" >
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label>Middle Name</label>
                                  <input class="form-control" type="text" name="name" placeholder="Dela">
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label>Last Name</label>
                                  <input class="form-control" type="text" name="name" placeholder="Cruz">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label>Birthday</label>
                                  <input class="form-control " type="date" value="2001-01-04">
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label>Email</label>
                                  <input class="form-control " type="email" placeholder="user@example.com">
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label>Phone</label>
                                  <input class="form-control" type="text" placeholder="0912345678">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col mb-3">
                                <div class="form-group">
                                  <label>Address</label>
                                  <textarea class="form-control" rows="3" placeholder="Lt. 123 Blk. 1"></textarea>
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
                                  <label>Current Password</label>
                                  <input class="form-control" type="password" placeholder="••••••">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col">
                                <div class="form-group">
                                  <label>New Password</label>
                                  <input class="form-control" type="password" placeholder="••••••">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col">
                                <div class="form-group">
                                  <label>Confirm <span class="d-none d-xl-inline">Password</span></label>
                                  <input class="form-control" type="password" placeholder="••••••"></div>
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
                            <button class="btn btn-primary" type="submit">Save Changes</button>
                          </div>
                        </div>
                      </form>
    
                    </div>

                    <div class="tab-pane" id="history">

                        <div class="card row shadow mb-2" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#vandetailsModal">
                            <div class="card-body">
                                <div class="row flex-row d-flex">
                                <div class="col-lg-2">
                                <img src="assets/van1.jpg" class="w-100 h-100 img-thumbnail">
                                </div>
                                <div class="col-lg-10">
                                    <div class="row">
                                    <a href="#">Toyota Hiace Super Grandia 2023</a>
                                    </div>
                                    <div class="row">
                                    <span class="text-muted">From: 07-27-2023 To: 08-03-2023</span>
                                    </div>
                                </div>
                                </div>
                            </div>

                        </div>

                        <div class="card row shadow mb-2" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#vandetailsModal">
                            <div class="card-body">
                                <div class="row flex-row d-flex">
                                <div class="col-lg-2">
                                <img src="assets/van1.jpg" class="w-100 h-100 img-thumbnail">
                                </div>
                                <div class="col-lg-10">
                                    <div class="row">
                                    <a href="#">Toyota Hiace Super Grandia 2023</a>
                                    </div>
                                    <div class="row">
                                    <span class="text-muted">From: 07-27-2023 To: 08-03-2023</span>
                                    </div>
                                </div>
                                </div>
                            </div>

                        </div>
                        

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
                                        <img src="assets/van2.jpg" class="w-100 h-auto img-thumbnail mb-1 shadow">
                                    </div>
                                    <div class="col-lg-5 ">
                                        <h4 class="border-bottom">Toyota Hiace Super Grandia 2023</h4>
                                        <p>Van: <span id="vanName"></span></p>
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
                                        <button class="btn btn-primary mb-2 mx-2 shadow">Reschedule</button>
                                        </div>
                                        <div class="row w-100">
                                        <button class="btn btn-danger mb-2 mx-2 shadow">Cancel</button>
                                        </div>
                                        <div class="row w-100">
                                        <button class="btn btn-light mb-2 mx-2 shadow" data-bs-toggle="modal" data-bs-target="#ratevanModal"><i class="fas fa-star text-warning"></i> Rate now</button>
                                        </div>
                                    </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary shadow" data-bs-dismiss="modal">Back</button>
                                  <button type="button" class="btn btn-primary shadow">Confirm</button>
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
                                        <h5>Quality of Service</h5>
                                        <div class="star-rating">
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="form-group pb-2">
                                            <textarea class="form-control" rows="4" placeholder="Write something here..."></textarea>
                                        </div>
                                        <div class=" form-outline d-flex">
                                            <i class="fa-solid fa-camera fa-lg pt-3 px-1"></i>
                                            <input type="file" id="fileInput" class="form-control custom-file-input "  multiple>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button>
                                  <button type="button" class="btn btn-primary">Confirm</button>
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
        const stars = document.querySelectorAll(".star-rating .fa-star");
        
      
        stars.forEach((star, index) => {
          star.addEventListener("mouseover", () => {
            addActiveStars(index);
          });
      
      
          star.addEventListener("click", () => {
            addPermanentStars(index);
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
      </script>
</body>
</html>