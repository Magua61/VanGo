<?php

require_once '../db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $vanId = $conn->real_escape_string($_POST['vanId']);
    $destinationAddress = $conn->real_escape_string($_POST['destination']);
    $pickupAddress = $conn->real_escape_string($_POST['pickupAddress']);
    $pickupDate = $conn->real_escape_string($_POST['pickupDate']);
    $pickupTime = $conn->real_escape_string($_POST['pickupTime']);
    $returnAddress = $conn->real_escape_string($_POST['returnAddress']);
    $returnDate = $conn->real_escape_string($_POST['returnDate']);
    $returnTime = $conn->real_escape_string($_POST['returnTime']);
    $totalPrice = $conn->real_escape_string($_POST['totalPrice']);

    
    $query = "SELECT concat_ws(' ', V_Make, V_Model, V_Year) as 'V_Name', V_Rate
            FROM van V JOIN van_rate VR
                ON V.Van_ID = VR.Van_ID
            WHERE V.Van_ID=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $vanId);
    $stmt->execute();
    $result = $stmt->get_result();

    $van = $result->fetch_assoc();

    // Calculate the number of days
    $pickupTimestamp = strtotime($pickupDate);
    $returnTimestamp = strtotime($returnDate);
    $diffSeconds = $returnTimestamp - $pickupTimestamp;
    $diffDays = floor($diffSeconds / (60 * 60 * 24));

    $rentalFee = $van['V_Rate'] * $diffDays;
    $driverFee = $totalPrice - $rentalFee;

    $rentalFee = number_format($rentalFee, 2);
    $driverFee = number_format($driverFee, 2);
    $totalPrice = number_format($totalPrice, 2);

    $rentalFee = str_replace(',', '', $rentalFee);
    $driverFee = str_replace(',', '', $driverFee);
    $totalPrice = str_replace(',', '', $totalPrice);
  }


$conn->close();
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Snippet - BBBootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        ::-webkit-scrollbar {
        width: 8px;
        }
        /* Track */
        ::-webkit-scrollbar-track {
        background: #f1f1f1;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
        background: #888;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
        background: #555;
        }
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap");

        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
        }

        .container {
        margin: 30px auto;
        }

        .container .card {
        width: 100%;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        background: #fff;
        border-radius: 0px;
        }

        body {
        background: #eee;
        }

        .container .card .img-box {
        width: 80px;
        height: 50px;
        }

        .container .card img {
        width: 100%;
        object-fit: fill;
        }

        .container .card .number {
        font-size: 24px;
        }

        .container .card-body .btn.btn-primary .fab.fa-cc-paypal {
        font-size: 32px;
        color: #3333f7;
        }

        .fab.fa-cc-amex {
        color: #1c6acf;
        font-size: 32px;
        }

        .fab.fa-cc-mastercard {
        font-size: 32px;
        color: red;
        }

        .fab.fa-cc-discover {
        font-size: 32px;
        color: orange;
        }

        .box {
        height: 40px;
        width: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #ddd;
        }


        .form__div {
        height: 50px;
        position: relative;
        margin-bottom: 24px;
        }

        .form-control {
        width: 100%;
        height: 45px;
        font-size: 14px;
        border: 1px solid #dadce0;
        border-radius: 0;
        outline: none;
        padding: 2px;
        background: none;
        z-index: 1;
        box-shadow: none;
        }

        .form__label {
        position: absolute;
        left: 16px;
        top: 10px;
        background-color: #fff;
        color: #80868b;
        font-size: 16px;
        transition: 0.3s;
        text-transform: uppercase;
        }

        .form-control:focus + .form__label {
        top: -8px;
        left: 12px;
        color: #1a73e8;
        font-size: 12px;
        font-weight: 500;
        z-index: 10;
        }

        .form-control:not(:placeholder-shown).form-control:not(:focus) + .form__label {
        top: -8px;
        left: 12px;
        font-size: 12px;
        font-weight: 500;
        z-index: 10;
        }

        .form-control:focus {
        border: 1.5px solid #1a73e8;
        box-shadow: none;
        }

    </style>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    
</head>
<body className='snippet-body'>
    <div class="container">
        <div class="row">
            <div class="col-12 mt-4">
                <div class="card p-3">
                    <p class="mb-0 fw-bold h4">Payment Methods</p>
                </div>
            </div>
            <div class="col-12">
                <div class="card p-3">
                    <div class="card-body border p-0">
                        <p>
                            <a class="btn btn-primary w-100 h-100 d-flex align-items-center justify-content-between"
                                data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true"
                                aria-controls="collapseExample">
                                <span class="fw-bold">PayPal</span>
                                <span class="fab fa-cc-paypal">
                                </span>
                            </a>
                        </p>
                        <div class="collapse p-3 pt-0" id="collapseExample">
                            <div class="row">
                            <div class="col-8">
                                <p class="h4 mb-0" style="font-size:24px">Summary</p>
                                <p class="mb-0" style="font-size:22px">
                                    <span>&nbsp &nbsp Van:</span>
                                    <span><?php echo $van['V_Name']; ?></span>
                                </p>
                                <p class="mb-0" style="font-size:22px">
                                    <span>&nbsp &nbsp Rental Duration:</span>
                                    <span><?php echo $diffDays." days"; ?></span>
                                </p>
                                <p class="mb-0" style="font-size:22px">
                                    <span>&nbsp &nbsp Rental Fee:</span>
                                    <span>₱<?php echo $rentalFee; ?></span>
                                </p>
                                <p class="mb-0" style="font-size:22px">
                                    <span>&nbsp &nbsp Driver's Fee:</span>
                                    <span>₱<?php echo $driverFee; ?></span>
                                </p>
                                <p class="mb-0" style="font-size:22px">
                                    <span>&nbsp &nbsp Total Amount:</span>
                                    <span>₱<?php echo $totalPrice; ?></span>
                                </p>
                            </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-body border p-0">
                        <p>
                            <a class="btn btn-primary p-2 w-100 h-100 d-flex align-items-center justify-content-between"
                                data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true"
                                aria-controls="collapseExample">
                                <span class="fw-bold">Credit Card</span>
                                <span class="">
                                    <span class="fab fa-cc-amex"></span>
                                    <span class="fab fa-cc-mastercard"></span>
                                    <span class="fab fa-cc-discover"></span>
                                </span>
                            </a>
                        </p>
                        <div class="collapse show p-3 pt-0" id="collapseExample">
                            <div class="row">
                                <div class="col-lg-5 mb-lg-0 mb-3">
                                    <p class="h4 mb-0" style="font-size:24px">Summary</p>
                                    <p class="mb-0" style="font-size:22px">
                                        <span>&nbsp &nbsp Van:</span>
                                        <span><?php echo $van['V_Name']; ?></span>
                                    </p>
                                    <p class="mb-0" style="font-size:22px">
                                        <span>&nbsp &nbsp Rental Duration:</span>
                                        <span><?php echo $diffDays." days"; ?></span>
                                    </p>
                                    <p class="mb-0" style="font-size:22px">
                                        <span>&nbsp &nbsp Rental Fee:</span>
                                        <span>₱<?php echo $rentalFee; ?></span>
                                    </p>
                                    <p class="mb-0" style="font-size:22px">
                                        <span>&nbsp &nbsp Driver's Fee:</span>
                                        <span>₱<?php echo $driverFee; ?></span>
                                    </p>
                                    <p class="mb-0" style="font-size:22px">
                                        <span>&nbsp &nbsp Total Amount:</span>
                                        <span>₱<?php echo $totalPrice; ?></span>
                                    </p>
                                </div>
                                <div class="col-lg-7">
                                    <form action="" class="form">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form__div">
                                                    <input type="text" class="form-control" id="card-number" maxlength="12"  placeholder=" ">
                                                    <label for="" class="form__label">Card Number</label>
                                                    <div class="invalid-feedback">Please enter a valid card number.</div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="form__div">
                                                    <input type="text" class="form-control" id="expiry-date" maxlength="5" placeholder=" ">
                                                    <label for="" class="form__label">MM / YY</label>
                                                    <div class="invalid-feedback">Please enter a valid expiry date in the format MM/YY.</div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="form__div">
                                                    <input type="password" class="form-control" id="cvv" maxlength="3" placeholder=" ">
                                                    <label for="" class="form__label">CVV</label>
                                                    <div class="invalid-feedback">Please enter a valid CVV number.</div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form__div">
                                                    <input type="text" class="form-control" id="name-on-card" placeholder=" ">
                                                    <label for="" class="form__label">Name on Card</label>
                                                    <div class="invalid-feedback">Please enter the name on the card.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-end mt-2">
                <div class="btn btn-danger payment me-2" id="cancel-payment-btn">
                    Cancel Payment
                </div>
                <div class="btn btn-primary payment" id="make-payment-btn">
                    Make Payment
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
                                  <p><span id="confirmContent">Are you sure you want to proceed with the payment?</span></p>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button>
                                  <button type="button" class="btn btn-primary" id="confirmSubmit" name="confirmSubmit" data-bs-dismiss="modal" >Confirm</button>
                                </div>
                              </div>
                            </div>
                          </div>

                        <!-- MODAL ALERT -->
                        <div class="modal fade" id="alertModal" tabindex="-1">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">Payment Successful! </h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <p> Thank you for your payment. Your transaction has been successfully processed. <span id="alertContent"></span></p>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>


    <script type="text/javascript">

        const makePaymentBtn = document.getElementById("make-payment-btn");

        // Add the data-bs-target attribute to the element
        makePaymentBtn.setAttribute("data-bs-toggle", "modal");
        makePaymentBtn.setAttribute("data-bs-target", "#confirmationModal");

        // Event listener for the "Cancel Payment" button
        var cancelPaymentBtn = document.getElementById("cancel-payment-btn");
        cancelPaymentBtn.addEventListener("click", function() {
            
            window.location.href = "user-index.php";
        });

        var confirmSubmit = document.getElementById("confirmSubmit");
        confirmSubmit.addEventListener("click", function() {
            var returnAddress = "<?php echo addslashes($returnAddress); ?>";

            const confirmationModal = document.getElementById("confirmationModal");
            const bootstrapModal = bootstrap.Modal.getInstance(confirmationModal);
            if (bootstrapModal) {
            bootstrapModal.hide();
            }

            const modal = document.getElementById('alertModal');
            const newBootstrapModal = new bootstrap.Modal(modal);
            newBootstrapModal.show();
        
                // AJAX request to insert rental and payment records
                $.ajax({
                    url: "insertForTransaction.php", // Replace with the actual PHP file name
                    method: "POST",
                    data: {
                        vanId: "<?php echo $vanId; ?>",
                        customerId: "<?php echo $_SESSION['customerid']; ?>",
                        destination: "<?php echo $destinationAddress; ?>",
                        pickupAddress: "<?php echo $pickupAddress; ?>",
                        pickupDate: "<?php echo $pickupDate; ?>",
                        pickupTime: "<?php echo $pickupTime; ?>",
                        returnDate: "<?php echo $returnDate; ?>",
                        returnTime: "<?php echo $returnTime; ?>",
                        totalPrice: "<?php echo $totalPrice; ?>",
                        returnAddress: returnAddress
                    },
                    success: function(response) {
                        // Handle the response from the PHP file
                        if (response.success) {
                            console.log("Records inserted successfully.");
                        
                            
                        } else {
                            console.log("Error inserting records.");
                           // alert("Payment Failed! Please try again later.");
                        }
                    },
                    error: function() {
                        console.log("AJAX request failed.");
                      //  alert("Payment Failed! Please try again later.");
                    }
                });
            
        });
        $('#alertModal').on('hide.bs.modal', function (e) {
            window.location.href = "user-index.php";
         })
    </script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
           
</body>
</html>
