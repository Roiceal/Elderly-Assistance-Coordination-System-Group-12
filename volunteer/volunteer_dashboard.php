<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['volunteer_id'])) {
    header("Location: volunteer_login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Volunteer Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['volunteer_name']) ?>!</h2>
    <p>You are logged in as a volunteer.</p>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>
</body>
</html>
