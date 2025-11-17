<?php
require_once 'cookieguest/set_guest_cookie.php';
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>EACS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="landingpage/style.css">
</head>

<body>
<div id="cookieConsent">
  We use cookies to improve your experience.
  <button id="acceptCookies">Accept</button>
  <button id="declineCookies">Decline</button>
</div>

<!-- Navbar -->
<nav class="navbar">
  <div class="nav-left">
    <button id="toggleBtn" class="hamburger">&#9776;</button>
    <h1 class="logo">Elderly Assistance Coordination System</h1>
  </div>
</nav>

<!-- Sidebar -->
<div id="sidebar" class="sidebar">
  <button id="closeBtn" class="close-btn">&times;</button>
  <ul>
    <li><a href="#">Test</a></li>
    <li><a href="#">None</a></li>
    <li><a href="#">Settings</a></li>
    <li><a href="#">None</a></li>
  </ul>
</div>

<!-- Hero Section -->
<div class="hero">
  <div class="hero-overlay">
    <h1 class="mb-4">Sign up today to get the support you need and stay connected!</h1>
    <a href="login/register.php" class="btn me-2">Sign Up</a>
    <a href="admin/index.php" class="btn">Log In</a>
  </div>
</div>

<!-- Footer -->
<footer class="site-footer">

  <div class="footer-top">
    
    <div class="footer-logos">
      <p>For testing</p>
    </div>

    <div class="footer-contact">
      <h4>For inquiries and concerns:</h4><br>
      <p><strong>Telephone number:</strong> (043) 756 9468</p>
      <p><strong>Email:</strong> barangaybalintawak2024@gmail.com</p>
      <p><strong>Address:</strong> Balintawak Road, Lipa City, Batangas 4232, Philippines</p>
      <p><strong>Support hours:</strong> Mon–Sunday, Always open</p>
    </div>

    <div class="footer-social"><p>For testing</p>
    </div>

  </div> <!-- correct footer-top closing -->

  <div class="footer-bottom">
    <p>© 2025 EACS | Barangay Balintawak | Terms of Service | Privacy Policy</p>
  </div>

</footer>

<script src="cookieguest/cookie_consent.js"></script>
<script src="landingpage/script.js"></script>
</body>
</html>
