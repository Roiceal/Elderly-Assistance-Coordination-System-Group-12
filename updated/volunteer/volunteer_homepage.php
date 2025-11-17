<?php 
session_start();
// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="volunteer.css">
    <title>Volunteer_UI</title>
</head>
<body>
 <nav class="navbar navbar-expand-lg bg-light fixed-top">
    <button class="toggle-btn" id="toggleSidebar">
        ☰
      </button>
</nav>
    <div class="container">
           <nav class="sidebar" id="sidebar">
    <ul class="sidebar-menu">
        <li class="menu-item"><a href="volunteer_homepage.php" class="menu-link">Home</a></li>
        <li class="menu-item"><a href="#" class="menu-link">Notification</a></li>
        <li class="menu-item"><a href="volunteer_requests.php" class="menu-link">View Available Request</a></li>
        <li class="menu-item"><a href="#" class="menu-link">View Elder Location</a></li>
        <li class="menu-item"><a href="#" class="menu-link">Task History</a></li>
        <li class="menu-item"><a href="volunteer_settings.php" class="menu-link">Settings</a></li>
        
    </ul>
           </nav>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
 <script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');

    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('hidden');
      content.classList.toggle('full-width');
    });
  </script>
</html>