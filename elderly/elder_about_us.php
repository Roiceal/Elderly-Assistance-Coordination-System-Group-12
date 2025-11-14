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
      <li class="menu-item"><a href="elder_privacy_and_policy.html" class="menu-link">Privacy and Policy</a></li>
    </ul>
  </nav>

<div class="description-container">
        <h1>About Us</h1>
        <p>At Elderly Assistance Coordination System, our mission is to provide 
            compassionate and reliable support for our senior citizens. We understand 
            that as people age, they may face challenges in mobility, health, and daily 
            living. That’s why our platform is dedicated to making it easier for elders 
            to receive the care and help they deserve.
        </p>
        <p>We aim to bridge the gap between elders in need and the services or volunteers 
            who can assist them. Whether it’s helping with medical appointments, providing 
            home assistance, or offering companionship, our goal is to ensure that no elder 
            feels left behind or unattended.
        </p>
        <p>
            Our team believes in promoting dignity, independence, and respect for every 
            elderly individual. Through our system, families, caregivers, and community 
            volunteers can work together to create a safe and supportive environment for the elderly.
        </p>
        <p>
            At the heart of what we do is care, connection, and community - because every 
            elder deserves to live comfortably, safely, and with the respect they’ve earned 
            throughout their lives.
        </p>
    </div>
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
