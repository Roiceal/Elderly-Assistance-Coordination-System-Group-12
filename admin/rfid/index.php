<?php 
session_start();
// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location:  ../../login/login.php");
    exit;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    
    <title>RFID Attendance</title>
    
  </head>
  
  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-light fixed-top">
      <button class="toggle-btn" id="toggleSidebar">☰</button>
      
    </nav>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
      <ul class="sidebar-menu">
        <li class="menu-item"><a href="user_profile.php" class="menu-link">Dashboard</a></li>
         <li class="menu-item"><a href="../homepage_admin.php" class="menu-link">Home</a></li>
        <li class="menu-item"><a href="../users_crud/read_users.php" class="menu-link">Users</a></li>
        <li class="menu-item"><a href="..//assistance_crud/read_request.php" class="menu-link">Assistance Request</a></li>
        <li class="menu-item"><a href="#" class="menu-link">Programs</a></li>
        <li class="menu-item"><a href="index.php" class="menu-link">Attendance</a></li>
        <li class="menu-item"><a href="#" class="menu-link">Activity log</a></li>
        <li class="menu-item"><a href="../admin_settings.php" class="menu-link">Settings</a></li>
      </ul>
    </nav>

    <!-- Main Content -->
    <div class="container d-flex justify-content-start align-items-start mt-2 main-container" id="content">

      <!-- RFID card -->
      <div class="card rfid-card me-3">
        <img class="card-img-top" alt="image" id="img" src="images/iconic.png">
        <div class="card-body">
          <input type="text" id="rfidcard" class="form-control mb-3 rfid" placeholder="Tap RFID card here">
          <p id="name">Name:</p>
          <p id="age">Age:</p>
          <p id="DOB">Date of Birth:</p>
          <p id="card_id">Card ID:</p>
        </div>
      </div>

      <!-- Attendance -->
      <div class="attendance-log flex-fill">
        <div class="header-bar">
          <h4>Attendance</h4>
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
              <!-- Attendance module -->
            </tbody>
          </table>
        </div>
      </div>

    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    
    <script>
      const toggleBtn = document.getElementById('toggleSidebar');
      const sidebar = document.getElementById('sidebar');
      const content = document.getElementById('content');

      toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('hidden');
        content.classList.toggle('full-width');
      });
    </script>
  </body>
</html>
