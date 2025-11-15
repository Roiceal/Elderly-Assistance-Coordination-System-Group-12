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
  <link rel="stylesheet" href="adminpannel.css">
    <title>Admin</title>
</head>
<body>
 <nav class="navbar navbar-expand-lg bg-light fixed-top">
    <button class="toggle-btn" id="toggleSidebar">
        ☰
      </button>
    <div class="container">
</nav>
  
  <nav class="sidebar" id="sidebar">
    <ul class="sidebar-menu">
      <li class="menu-item"><a href="user_profile.php" class="menu-link">Dashboard</a></li>
      <li class="menu-item"><a href="homepage_admin.php" class="menu-link">Home</a></li>
      <li class="menu-item"><a href="./users_crud/read_users.php" class="menu-link">Users</a></li>
      <li class="menu-item"><a href="./assistance_crud/read_request.php" class="menu-link">Assistance Request</a></li>
      <li class="menu-item"><a href="" class="menu-link">Programs</a></li>
      <li class="menu-item"><a href="rfid/index.php" class="menu-link">Attendance</a></li>
      <li class="menu-item"><a href="" class="menu-link">Activity log</a></li>
      <li class="menu-item"><a href="admin_settings.php" class="menu-link">Settings</a></li>
    </ul>
  </nav>
   <div class="settings-container">
    <h2>Settings</h2>
    <ul class="settings-list">
        <li><a href="#" class="settings-link">test</a></li>
        <li><a href="#" class="settings-link">test</a></li>
        <li><a href="#" class="settings-link">test</a></li>
        <li><a href="#" class="settings-link">test</a></li>
        <li><a href="#" class="settings-link">test</a></li>
        <li> <a class="btn btn-danger" href="../login/logout.php">Logout</a></li>
    </ul>
</div>
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