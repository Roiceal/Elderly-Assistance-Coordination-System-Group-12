<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
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

  <div class="container">
    <h4>Welcome to</h4>
    <h2>Elderly Assistance Coordination System</h2>
    <p class="details">Effective coordination and timely assistance to ensure the elderly members well-being. </p>
  </div>
  <a href="notif.php">  
  <div class=" container-fluid justify-content-center d-flex mx-auto">
    <div class="card m-3" style="width: 18rem;">
    <img src="notif.png" class="card-img-top img-fluid" alt="_blank">
    <div class="card-body">
        <p class="card-text">Tap to view notification</p>
     </div>
    </div>
  </a> 
  <a href="request_assistance.php">
    <div class="card m-3" style="width: 18rem;">
        <img src="assistance_pic.jpg" class="card-img-top img-fluid" alt="_blank">
        <div class="card-body">
            <p class="card-text">Tap to request assistance</p>
        </div>
    </div>
  </a>
  <a href="user_profile.php">
    <div class="card  m-3" style="width: 18rem;">
      <img src="info.jpg" class="card-img-top img-fluid" alt="_blank">
        <div class="card-body">
            <p class="card-text">Tap to complete user profile</p>
        </div>
    </div>
  </a>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>