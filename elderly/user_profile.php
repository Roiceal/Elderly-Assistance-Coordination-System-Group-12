<?php
session_start();
include_once '../database/db_connection.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

$db = new Database();
$conn = $db->getConnection();
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT username, email, phone_number, address, gender, birthday, role, rfid_pin 
                        FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Profile</title>
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

<div class="user_profile">
        <h3 class="text-center">User Profile</h3>

        <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Phone Number:</strong> <?= htmlspecialchars($user['phone_number']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($user['address']) ?></p>
        <p><strong>Gender:</strong> <?= htmlspecialchars($user['gender']) ?></p>
        <p><strong>Birthday:</strong> <?= htmlspecialchars($user['birthday']) ?></p>
        <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
        <p><strong>RFID Pin:</strong> <?= htmlspecialchars($user['rfid_pin']) ?></p>

        <div class="text-center mt-4">
          <a href="edit_profile.php" class="btn btn-success rounded-pill px-4">Edit Profile</a>
        </div>
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
