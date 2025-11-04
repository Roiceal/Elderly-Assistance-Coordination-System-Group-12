<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>RFID Attendance</title>
  </head>
  <body>
    <div class="container d-flex justify-content-start align-items-start mt-2 main-container">

      <!-- rfid card -->
      <div class="card rfid-card">
        <img class="card-img-top" alt="image" id="img" src="images/iconic.png">
        <div class="card-body">
          <input type="text" id="rfidcard" class="form-control mb-3 rfid" placeholder="Tap RFID card here">
          <p id="name">Name:</p>
          <p id="age">Age:</p>
          <p id="DOB">Date of Birth:</p>
          <p id="card_id">Card ID:</p>
        </div>
      </div>

      <!-- attendace -->
      <div class="attendance-log">
        <div class="header-bar">
          <h4>Attendance</h4>
          <button id="resetBtn" class="btn btn-danger btn-sm">Reset Attendance</button>
        </div>

        <div class="table-responsive">
          <table class="table table-striped center">
            <thead>
              <tr>
                <th>Name</th>
                <th>Card ID</th>
                <th>Address</th>
                <th>Time In</th>
                <th>Time Out</th>
              </tr>
            </thead>
            <tbody id="attendanceTable">
              <!-- shows the attendnace module -->
            </tbody>
          </table>
        </div>
      </div>

    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="script.js"></script>
  </body>
</html>
