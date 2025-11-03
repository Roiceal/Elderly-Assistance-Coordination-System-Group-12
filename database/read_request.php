<?php
require_once 'db_connection.php';
$db = new Database();
$conn = $db->getConnection();

$result = $conn->query("CALL read_request()");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Requests</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
</head>

<body class="p-4">
  <h2>Assistance Requests</h2>
  <a href="create.php" class="btn btn-success mb-3">Add New Request</a>

  <table  id="myTable" class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Address</th>
        <th>Contact</th>
        <th>Type</th>
        <th>Details</th>
        <th>Actions</th>
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
          <td><a href="update_request.php?id=<?= $row['request_id']; ?>" class="btn btn-sm btn-primary">Update</a>
            <a href="delete_request.php?id=<?= $row['request_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
  <script>

  $(document).ready( function () {
    $('#myTable').DataTable();
} );
</script>

</body>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>



</html>
