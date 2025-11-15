<?php
session_start();
require_once '../../database/db_connection.php';

// Admin-only: no need for volunteer session check
$db = new Database();
$conn = $db->getConnection();

// Fetch requests where at least one volunteer accepted
$result = $conn->query("
    SELECT ar.*, 
           GROUP_CONCAT(CONCAT(u.username,' (',va.status,')') SEPARATOR ', ') AS volunteers_status
    FROM assistance_request ar
    JOIN volunteer_assignments va ON ar.request_id = va.request_id
    JOIN users u ON va.volunteer_user_id = u.user_id
    WHERE va.status='accepted'
    GROUP BY ar.request_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Accepted Assistance Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<h2>Accepted Assistance Requests</h2>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Request ID</th>
            <th>Full Name</th>
            <th>Address</th>
            <th>Contact</th>
            <th>Type</th>
            <th>Details</th>
            <th>Date Requested</th>
            <th>Volunteers Accepted</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['request_id']; ?></td>
            <td><?= htmlspecialchars($row['fullname']); ?></td>
            <td><?= htmlspecialchars($row['address']); ?></td>
            <td><?= htmlspecialchars($row['contact']); ?></td>
            <td><?= htmlspecialchars($row['type']); ?></td>
            <td><?= htmlspecialchars($row['details']); ?></td>
            <td><?= htmlspecialchars($row['date_requested']); ?></td>
            <td><?= $row['volunteers_status']; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
</body>
</html>
