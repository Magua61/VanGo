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

  $query = "SELECT R.Rental_ID, concat_ws(' ', C_FName, C_LName) as 'C_FullName', Destination, Pickup_Address, Pickup_Date, Pickup_Time, Return_Address, Return_Date, Return_Time
          FROM customer C JOIN rental R 
            ON C.Customer_ID = R.Customer_ID
          LEFT JOIN rental_without_driver RWD
            ON R.Rental_ID = RWD.Rental_ID";
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

      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="">
      <meta name="author" content="">

      <title>Bare - Start Bootstrap Template</title>

      <!-- Bootstrap Core CSS -->
      <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FullCalendar -->
    <link href='css/fullcalendar.css' rel='stylesheet' />


      <!-- Custom CSS -->
      <style>
      body {
          padding-top: 40px;
          /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
      }
    #calendar {
      max-width: 600px;
    }
    .col-centered{
      float: none;
      margin: 0 auto;
    }
    #rentalInfo {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: white;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
      width: 350px;
    }

      </style>

      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->

  </head>

  <body>

      <!-- Page Content -->
      <div class="container">

          <div class="row">
              <div class="col-lg-12 text-center">
                  <div id="calendar" class="col-centered">
                  </div>
              </div>
        
          </div>
          <!-- /.row -->
      
      <!-- Modal -->
      <div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
        <form class="form-horizontal" method="POST" action="addDate.php">
        
          <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Unavailable Date</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
            <label for="start" class="col-sm-2 control-label">Start date</label>
            <div class="col-sm-10">
              <input type="text" name="start" class="form-control" id="start" readonly>
            </div>
            </div>
            <div class="form-group">
            <label for="end" class="col-sm-2 control-label">End date</label>
            <div class="col-sm-10">
              <input type="text" name="end" class="form-control" id="end" readonly>
            </div>
            </div>
          
          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
        <form class="form-horizontal" method="POST" action="deleteDate.php">
          <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Remove Unavailable Date/s?</h4>
          </div>

            <input type="hidden" name="id" class="form-control" id="id">	

          <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" name="submit"  class="btn btn-primary">Save changes</button>
          </div>
        </form>
        </div>
        </div>
      </div>

      </div>
      <!-- /.container -->

      <div class="container">
        <div id="rentalInfo">
            <h2>Rental Details</h2>
            <p>Customer Name: <span id="customerName"></span></p>
            <p>Destination: <span id="destination"></span></p>
            <p>Pickup Address: <span id="pickupAddress"></span></p>
            <p>Pickup Date: <span id="pickupDate"></span></p>
            <p>Pickup Time: <span id="pickupTime"></span></p>
            <p>Return Address: <span id="returnAddress"></span></p>
            <p>Return Date: <span id="returnDate"></span></p>
            <p>Return Time: <span id="returnTime"></span></p>
        </div>
      </div>

      <!-- jQuery Version 1.11.1 -->
      <script src="js/jquery.js"></script>

      <!-- Bootstrap Core JavaScript -->
      <script src="js/bootstrap.min.js"></script>
    
    <!-- FullCalendar -->
    <script src='js/moment.min.js'></script>
    <script src='js/fullcalendar.min.js'></script>
    
    <script>

  $(document).ready(function() {
      $('#calendar').fullCalendar({
          header: {
              left: 'title',
              center: '',
              right: 'today prev,next'
          },
          defaultDate: '<?php echo date("Y-m-d"); ?>',
          editable: true,
          eventLimit: true, // allow "more" link when too many events
          selectable: true,
          selectHelper: true,
          select: function(start, end) {
              $('#ModalAdd #start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
              $('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
              $('#ModalAdd').modal('show');
          },
          eventRender: function(event, element) {
              element.bind('dblclick', function() {
                  $('#ModalEdit #id').val(event.id);
                  $('#ModalEdit #title').val(event.title);
                  $('#ModalEdit #color').val(event.color);
                  $('#ModalEdit').modal('show');
              });
              
          },
          eventClick: function (event, element) {
                    var eventId = event.id;
                    var eventTitle = event.title;
                    var eventStart = event.start.format('YYYY-MM-DD');
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
                      var rentalID = matchedRental.Rental_ID;
                      var customerFullName = matchedRental.C_FullName;
                      var destination = matchedRental.Destination;
                      var pickupAddress = matchedRental.Pickup_Address;
                      var pickupDate = matchedRental.Pickup_Date;
                      var pickupTime = matchedRental.Pickup_Time;
                      var returnAddress = matchedRental.Return_Address;
                      var returnDate = matchedRental.Return_Date;
                      var returnTime = matchedRental.Return_Time;

                      // Example: Display rental details in the rentalInfo div
                      $('#rentalInfo #customerName').text(customerFullName);
                      $('#rentalInfo #destination').text(destination);
                      $('#rentalInfo #pickupAddress').text(pickupAddress);
                      $('#rentalInfo #pickupDate').text(pickupDate);
                      $('#rentalInfo #pickupTime').text(pickupTime);
                      $('#rentalInfo #returnAddress').text(returnAddress);
                      $('#rentalInfo #returnDate').text(returnDate);
                      $('#rentalInfo #returnTime').text(returnTime);
                    }
                  },
          eventDrop: function(event, delta, revertFunc) { // si changement de position
              edit(event);
          },
          eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur
              edit(event);
          },
          events: [
              <?php while($row = $dates->fetch_assoc()): ?>
                  {
                      id: '<?php echo $row['XDate_ID']; ?>',
                      title: '<?php echo $row['Status']; ?>',
                      start: '<?php echo $row['Start_Date']; ?>',
                      end: moment('<?php echo $row['End_Date']; ?>').add(1, 'days').format('YYYY-MM-DD'),
                      color: '<?php echo ($row['Status'] === 'Unavailable') ? 'red' : 'darkblue'; ?>'
                  },
              <?php endwhile; ?>
          ]
      });

      function edit(event) {
          var start = event.start.format('YYYY-MM-DD HH:mm:ss');
          var end = event.end ? event.end.format('YYYY-MM-DD HH:mm:ss') : start;
          var id = event.id;
          var eventData = [id, start, end];
      }
  });

  </script>

  </body>

  </html>
