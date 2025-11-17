<?php
include_once '../../database/db_connection.php';
$db = new Database();
$conn = $db->getConnection();

// --- Fetch record for editing ---
if (isset($_GET['id'])) {
    $id = (int) $_GET['id']; 

    $stmt = $conn->prepare("
        SELECT request_id, fullname, address, contact, type, details, date_requested
        FROM assistance_request
        WHERE request_id = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
}

// --- Handle update form submission ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = (int) $_POST['id'];
    $fullname = trim($_POST['fullname']);
    $address = trim($_POST['address']);
    $contact = trim($_POST['contact']);
    $type = trim($_POST['type']);
    $details = trim($_POST['details']);
    $date_requested = trim($_POST['date_requested']);

    $stmt = $conn->prepare("
        UPDATE assistance_request
        SET fullname = ?, address = ?, contact = ?, type = ?, details = ?, date_requested = ?
        WHERE request_id = ?
    ");
    $stmt->bind_param("ssssssi", $fullname, $address, $contact, $type, $details, $date_requested, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Request updated successfully!'); window.location='read_request.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error updating request: " . htmlspecialchars($stmt->error) . "');</script>";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Request</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../adminpannel.css"> 
</head>

<body class="p-4">
  <div class="container">
    <h2 class="mb-4">Edit Assistance Request</h2>

    <form action="update_request.php" method="POST">
      <input type="hidden" name="id" value="<?= htmlspecialchars($row['request_id']); ?>">

      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="fullname" value="<?= htmlspecialchars($row['fullname']); ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Address</label>
        <input type="text" name="address" value="<?= htmlspecialchars($row['address']); ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Contact</label>
        <input type="text" name="contact" value="<?= htmlspecialchars($row['contact']); ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Type</label>
        <input type="text" name="type" value="<?= htmlspecialchars($row['type']); ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Details</label>
        <textarea name="details" rows="4" class="form-control" required><?= htmlspecialchars($row['details']); ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Date Requested</label>
        <input type="datetime-local" name="date_requested" 
               value="<?= date('Y-m-d\TH:i', strtotime($row['date_requested'])); ?>" 
               class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary">Update</button>
      <a href="read_request.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</body>
</html>
