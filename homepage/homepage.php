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
  <link rel="icon" href="images/EACS logo.png" type="Logo">
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
    <a href="volunteer/about_us.html"> <img src="images/EACS logo.png" alt="EACS Logo" class="logo">
</a>
    <h1>Elderly Assistance Coordination System</h1>
  </div>
</nav>


<!-- Hero Section -->
<div class="hero">
  <div class="hero-overlay">
    <h1 class="mb-4">Sign up today to get the support you need and stay connected!</h1>
    <a href="login/register.php" class="btn me-2">Sign Up</a>
    <a href="admin/index.php" class="btn">Log In</a>
  </div>
</div>

<!-- Footer --> <!-- sectioning to ng footer -->
<footer class="site-footer">

  <div class="footer-top">

    <!-- LEFT -->
    <div class="footer-logos">
      <h4><strong>Learn more about EACS</strong></h4><br>
      <p style="font-size: 18px; text-align: justify;" ">
  EACS is a digital platform designed to streamline the coordination, management, and monitoring of elderly assistance programs. It provides a centralized system for tracking requests, managing beneficiaries, and improving community support services.
</p>
    </div>

    <!-- MIDDLE -->
    <div class="footer-contact">
      <h4>For inquiries and concerns:</h4><br>
      <p><strong>Telephone number:</strong> (043) 756 9468</p>
      <p><strong>Email:</strong> barangaybalintawak2024@gmail.com</p>
      <p><strong>Address:</strong> Balintawak Road, Lipa City, Batangas 4232, Philippines</p>
      <p><strong>Support hours:</strong> Mon–Sunday, Always open</p>
    </div>

    <!-- RIGHT -->
    <div class="footer-social">
      <div>
        <h4><strong>About the system</strong></h4><br>
        <p>This system is developed to support efficiency, transparency, and accessibility in managing elderly assistance. It aims to simplify workflows, enhance data accuracy, and ensure better service delivery for all stakeholders.</p>
      </div>
    </div>

  </div>

  <div class="footer-bottom">
    <p>© 2025 EACS | Barangay Balintawak | Terms of Service | Privacy Policy</p>
  </div>

</footer>


<script src="cookieguest/cookie_consent.js"></script>
</body>
</html>
