<?php
session_start();
include_once '../database/db_connection.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

$db = new Database();
$conn = $db->getConnection();
$user_id = $_SESSION['user_id'];

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['address']);
    $gender = trim($_POST['gender']);
    $birthday = trim($_POST['birthday']);
    $rfid_pin = trim($_POST['rfid_pin']);

    $stmt = $conn->prepare("CALL UpdateUserProfile(?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $address, $gender, $birthday, $rfid_pin);

    if ($stmt->execute()) {
        $success_message = "Profile updated successfully!";
         header("Location: user_profile.php?updated=1");
         exit;
    } else {
        $error_message = "Error updating profile: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch updated user info
$stmt = $conn->prepare("SELECT username, email, phone_number, address, gender, birthday, role, rfid_pin FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5 pt-5">
  <div class="col-md-6 mx-auto bg-white shadow p-4 rounded">
    <h3 class="text-center mb-4">Edit Profile</h3>

    <?php if (isset($success_message)): ?>
      <div class="alert alert-success text-center"><?= htmlspecialchars($success_message) ?></div>
    <?php elseif (isset($error_message)): ?>
      <div class="alert alert-danger text-center"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <form action="edit_profile.php" method="POST">
      <div class="mb-3">
        <label class="form-label">Address</label>
        <input type="text" name="address" class="form-control rounded-pill" value="<?= htmlspecialchars($user['address']) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Gender</label>
        <input type="text" name="gender" class="form-control rounded-pill" value="<?= htmlspecialchars($user['gender']) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Birthday</label>
        <input type="date" name="birthday" class="form-control rounded-pill" value="<?= htmlspecialchars($user['birthday']) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">RFID Pin</label>
        <input type="text" name="rfid_pin" class="form-control rounded-pill" value="<?= htmlspecialchars($user['rfid_pin']) ?>">
      </div>

      <div class="text-center">
        <button type="submit" class="btn btn-success rounded-pill px-4">Save Changes</button>
        <a href="user_profile.php" class="btn btn-secondary rounded-pill px-4">Cancel</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
