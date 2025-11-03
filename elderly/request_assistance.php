<?php
require_once '../database/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db = new Database();
    $conn = $db->getConnection();

    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $type = $_POST['type'];
    $details = $_POST['details'];

    // Call the stored procedure
    $stmt = $conn->prepare("CALL add_assistance_request(?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fullname, $address, $contact, $type, $details);

    if ($stmt->execute()) {
        echo "<script>alert('Request submitted successfully!'); window.location='view_requests.php';</script>";
    } else {
        echo "<script>alert('Error submitting request.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Request Assistance</title>
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
    <div class="request-form">
      <h2>Request Assistance</h2>
      <form action="../database/create.php" method="POST">
        <div class="mb-3">
          <label for="fullname" class="form-label">Full Name</label>
          <input type="text" name="fullname" id="fullname" class="form-control" placeholder="Enter your full name" required>
        </div>

        <div class="mb-3">
          <label for="address" class="form-label">Address</label>
          <input type="text" name="address" id="address" class="form-control" placeholder="Enter your address" required>
        </div>

        <div class="mb-3">
          <label for="contact" class="form-label">Contact Number</label>
          <input type="tel" name="contact" id="contact" class="form-control" placeholder="Enter your contact number" required>
        </div>

        <div class="mb-3">
          <label for="type" class="form-label">Type of Assistance</label>
          <input type="text" name="type" id="type" class="form-control" placeholder="What type of assistance do you need?" required>
        </div>

        <div class="mb-3">
          <label for="details" class="form-label">Description / Details</label>
          <textarea name="details" id="details" rows="4" class="form-control" placeholder="Describe what kind of help you need..." required></textarea>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Submit Request</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
