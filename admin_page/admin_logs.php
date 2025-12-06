<?php
session_start();
include __DIR__ . '/../db_connect.php';

// Fetch activity logs JOIN admin table
$stmt = $pdo->query("
    SELECT activity_logs.*, admin.fullname
    FROM activity_logs
    JOIN admin ON activity_logs.admin_id = admin.id
    ORDER BY activity_logs.timestamp DESC
");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

</head>
<body>

<div class="container mt-4">
    <h3 class="mb-3">Admin Activity Logs</h3>

    <table id="logsTable" class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Admin Name</th>
                <th>Activity</th>
                <th>IP Address</th>
                <th>User Agent</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $count = 1;
            foreach ($logs as $log): ?>
                <tr>
                    <td><?= $count++ ?></td>
                    <td><?= htmlspecialchars($log['fullname']) ?></td>
                    <td><?= htmlspecialchars($log['activity']) ?></td>
                    <td><?= htmlspecialchars($log['ip_address']) ?></td>
                    <td><?= htmlspecialchars($log['user_agent']) ?></td>
                    <td><?= date("F d, Y h:i:s A", strtotime($log['timestamp'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#logsTable').DataTable({
        "pageLength": 10,
        "order": [[5, "desc"]],
        "responsive": true
    });
});
</script>

</body>
</html>
