<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="elder.css">
</head>
<body>


  <nav class="navbar navbar-expand-lg bg-light fixed-top">
    <div class="container">
      <h1 class="navbar-brand fw-bold mb-0">EACS</h1>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item">
            <a class="nav-link" href="home.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="notif.php">Notification</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="request_assistance.php">Request Assistance</a>
          </li>
           <li class="nav-item">
            <a class="nav-link" href="user_profile.php">User Profile</a>
          </li>
        </ul>

        <a class="btn btn-danger" href="logout.php">Logout</a>
      </div>
    </div>
  </nav>

    <div class="complete_profile">
        <form action="user_profile.php" method="$_POST">
            <div class="box">
                <input type="text" name="address" class="form-control rounded-pill" placeholder="Enter your Address">
            </div>
            <div class="box">
                <input type="text" name="gender" class="form-control rounded-pill" placeholder="Type your Gender"> 
            </div>
            <div class="box">
                <input type="text" name="birthday" class="form-control rounded-pill" placeholder="Enter your Birthday">
            </div>
            <div class="box">
                <input type="text" name="rfid_pin" class="form-control rounded-pill" placeholder="Enter RFID Pin">
            </div>
            <div class="button">
                <button type="submit" onclick="window.location.href=''" class="btn btn-success rounded-pill d-grid gap-2 col-6 mx-auto">confirm</button>
            </div>
            </form>
    </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
