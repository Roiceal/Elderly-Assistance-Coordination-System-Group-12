<?php
require_once 'set_guest_cookie.php'; // sets cookie and greeting for guests
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>EACS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="homepage/style.css">
</head>
<div id="cookieConsent">
  We use cookies to improve your experience.
  <button id="acceptCookies" style="margin-left:10px; padding:5px 10px;">Accept</button>
  <button id="declineCookies" style="margin-left:10px; padding:5px 10px;">Decline</button>
</div>

<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="nav-left">
      <button id="toggleBtn" class="hamburger">&#9776;</button>
      <h1 class="logo">Elderly Assistance Coordination System</h1>
    </div>
    <div class="nav-right">
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
  
<div class="main-content">
  
  <!-- LEFT CONTENT -->
  <div class="left-content">
    <h1 class="mb-4">Sign up today to get the support you <br> need and stay connected!</h1>
    <button class="btn me-2">Sign up</button>
    <button class="btn">Log-in</button>
  </div>

  <!-- RIGHT CONTENT -->
  <div class="right-content">
    <div class="right-box">
      <img src="images/Elderly.jpg" alt="Elderly" class="big-img">
    </div>
  </div>

</div>

<!-- FOOTER -->
<footer class="site-footer">

  <!-- Top Blue Section -->
  <div class="footer-top">
    <div class="footer-logos">
      <p>For testing</p>
    </div>

    <div class="footer-contact">
      <h4>For inquiries and concerns:</h4>
      <p>Hotline: 1234</p>
      <p>Email: EACS.gov.ph</p>
    </div>

    <div class="footer-social">
      <p>For testing</p>
    </div>
  </div>

  <!-- Middle Gray Section -->
  <div class="footer-middle">
    <div>
      <h5>REPUBLIC OF THE PHILIPPINES</h5>
      <p>All content is in the public domain unless otherwise stated.</p>
    </div>
 <div class="test" style="text-align: center;">
      <h5>FOR TESTING</h5>
      <p>TEST</p>
    </div>

    <div>
      <h5>ABOUT</h5>
      <p>Learn more about the website</p>
      <div class="name"> <a href="#">EACS</a>
      </div>
    </div>
  </div>
</div>
  <!-- Bottom Bar -->
  <div class="footer-bottom">
    <p>© 2025 EACS | Terms of Service | Privacy Policy</p>
  </div>

</footer>

  <!-- JS -->
   <script src="cookie_consent.js"></script>

  <script src="homepage/script.js"></script>
</body>
</html>
