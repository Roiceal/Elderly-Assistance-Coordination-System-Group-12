<?php
session_start();
include_once '../../database/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit;
}

$db = new Database();
$conn = $db->getConnection();
$request_id = $_GET['id'] ?? null;

if (!$request_id) {
    echo "No request selected.";
    exit;
}

// Fetch all volunteers
$volunteers = $conn->query("SELECT user_id FROM users WHERE role = 'volunteer'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    while ($vol = $volunteers->fetch_assoc()) {
        $volunteer_id = $vol['user_id'];

        // Check if already assigned
        $check = $conn->prepare("SELECT * FROM volunteer_assignments WHERE request_id=? AND volunteer_user_id=?");
        $check->bind_param("ii", $request_id, $volunteer_id);
        $check->execute();
        $check_result = $check->get_result();

        if ($check_result->num_rows == 0) {
            $stmt = $conn->prepare("INSERT INTO volunteer_assignments (request_id, volunteer_user_id, status, date_assigned) VALUES (?, ?, 'pending', NOW())");
            $stmt->bind_param("ii", $request_id, $volunteer_id);
            $stmt->execute();
            $stmt->close();
        }
        $check->close();
    }

    echo "<script>alert('Request assigned to all volunteers successfully!'); window.location='read_request.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Request to Volunteers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<h2>Assign Request #<?= htmlspecialchars($request_id); ?> to All Volunteers</h2>

<form method="POST">
    <button type="submit" class="btn btn-success">Assign to All Volunteers</button>
    <a href="read_request.php" class="btn btn-secondary">Cancel</a>
</form>
</body>
</html>
