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
  <title>Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
 <link rel="stylesheet" href="elder.css">
</head>
<body>

  <nav class="navbar navbar-expand-lg bg-light fixed-top">
    <button class="toggle-btn" id="toggleSidebar">
        ☰
      </button>
    <div class="container">
       

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
            <a class="nav-link" href="elder_about_us.php">About Us</a>
          </li>
        </ul>

        <a class="btn btn-danger" href="../login/logout.php">Logout</a>
      </div>
    </div>
  </nav>

   <nav class="sidebar" id="sidebar">
    <ul class="sidebar-menu">
      <li class="menu-item"><a href="user_profile.php" class="menu-link">View Profile</a></li>
      <li class="menu-item"><a href="#" class="menu-link">Change Password</a></li>
      <li class="menu-item"><a href="#" class="menu-link">Language</a></li>
      <li class="menu-item"><a href="elder_privacy_and_policy.php" class="menu-link">Privacy and Policy</a></li>
    </ul>
  </nav>

<div class="description-container">
         <h1 class="text-center mb-4">Privacy and Policy Statement</h1>

    <h3>Introduction</h3>
    <p>
      The Elderly Assistance Coordination System values your privacy and is fully committed to protecting the personal information of all its users. 
      This Privacy Policy explains how we collect, use, and safeguard your information in accordance with the Data Privacy Act of 2012 (Republic Act No. 10173).
      By using our system, you acknowledge and agree to the terms outlined in this policy.
    </p>

    <h3>Data Collection and Usage</h3>
    <p>
      We collect personal data necessary for providing assistance and maintaining accurate records, such as your name, address, contact details, and other related information.
      The data collected will be used solely for legitimate and lawful purposes including coordination of assistance requests, record management, and service improvement.
      We do not sell, rent, or share your data with unauthorized entities.
    </p>

    <h3>Data Protection and Security</h3>
    <p>
      We implement appropriate organizational, physical, and technical measures to protect your information from unauthorized access, misuse, or loss.
      Access to personal data is restricted only to authorized personnel who are required to handle such information responsibly and confidentially.
    </p>

    <h3>Prohibition of Malicious Use</h3>
    <p>
      All collected information shall not be used for any malicious acts or unlawful activities such as identity theft, fraud, phishing, or any other form of misuse.
      We strictly prohibit unauthorized access, alteration, or disclosure of user information. 
      Any violation of this policy may result in disciplinary and legal actions under existing laws.
    </p>

    <h3>Compliance with the Data Privacy Act of 2012 (RA 10173)</h3>
    <p>
      We comply with the provisions of the Data Privacy Act of 2012, which ensures the protection of personal data and the right to privacy.
      As a data subject, you are entitled to the following rights:
    </p>
    <ul>
      <li>The right to be informed about how your data is collected and processed.</li>
      <li>The right to access and correct your personal data.</li>
      <li>The right to object to processing or request data deletion.</li>
      <li>The right to data portability.</li>
      <li>The right to seek compensation for damages arising from misuse or unauthorized use of your data.</li>
    </ul>

    <h3>Data Retention and Disposal</h3>
    <p>
      Personal data shall only be retained for as long as necessary to achieve the stated purposes or as required by law. 
      Once data is no longer needed, it will be securely deleted or anonymized to prevent unauthorized access or recovery.
    </p>

   
  </div>
     <footer>
      <p>&copy; 2025 Elderly Assistance Coordination System. All Rights Reserved.</p>
    </footer>
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

</body>
</html>
