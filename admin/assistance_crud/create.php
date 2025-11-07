<?php
require_once '../../database/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db = new Database();
    $conn = $db->getConnection();

    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $type = $_POST['type'];
    $details = $_POST['details'];
    $date_requested = date('Y-m-d H:i:s'); // current date and time

    // Call the stored procedure (now 6 parameters)
    $stmt = $conn->prepare("CALL add_assistance_request(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $fullname, $address, $contact, $type, $details, $date_requested);

    if ($stmt->execute()) {
        header("Location: read_request.php?success=1");
        exit;
    } else {
        header("Location: read_request.php?error=1");
        exit;
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
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow p-4 mx-auto" style="max-width: 600px;">
            <h2 class="text-center mb-4">Request Assistance</h2>
            
            <form action="create.php" method="POST">
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

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                    <a href="read_request.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
