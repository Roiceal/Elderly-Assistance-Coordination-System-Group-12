<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['volunteer_id'])) {
    header("Location: volunteer_login.php");
    exit();
}

include __DIR__ . '/../db_connect.php'; // adjust path if needed

$volunteer_id = $_SESSION['volunteer_id'];

// Get volunteer info
$stmt = $pdo->prepare("SELECT * FROM volunteers WHERE id = ?");
$stmt->execute([$volunteer_id]);
$volunteer = $stmt->fetch(PDO::FETCH_ASSOC);

// Stats: total assigned requests
$stmt = $pdo->prepare("SELECT COUNT(*) as total_assigned FROM assistance_requests WHERE assigned_volunteer_id = ?");
$stmt->execute([$volunteer_id]);
$totalAssigned = $stmt->fetch(PDO::FETCH_ASSOC)['total_assigned'] ?? 0;

// Stats: pending requests assigned
$stmt = $pdo->prepare("SELECT COUNT(*) as pending_assigned FROM assistance_requests WHERE assigned_volunteer_id = ? AND status='pending'");
$stmt->execute([$volunteer_id]);
$pendingAssigned = $stmt->fetch(PDO::FETCH_ASSOC)['pending_assigned'] ?? 0;

// Volunteer age stats
$stmt = $pdo->query("SELECT MIN(age) as min_age, MAX(age) as max_age, COUNT(*) as total_volunteers FROM volunteers");
$ageStats = $stmt->fetch(PDO::FETCH_ASSOC);

// Volunteer gender stats
$stmt = $pdo->query("SELECT gender, COUNT(*) as count FROM volunteers GROUP BY gender");
$genderStatsRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);
$genderStats = ['Male'=>0,'Female'=>0,'Other'=>0];
foreach ($genderStatsRaw as $g) {
    $genderStats[$g['gender']] = $g['count'];
}

// Fetch assigned assistance requests
$stmt = $pdo->prepare("
    SELECT ar.id, u.first_name, u.last_name, ar.request_type, ar.description, ar.location, ar.status, ar.requested_at
    FROM assistance_requests ar
    JOIN users u ON ar.user_id = u.id
    WHERE ar.assigned_volunteer_id = ?
    ORDER BY ar.requested_at DESC
");
$stmt->execute([$volunteer_id]);
$assignedRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Volunteer Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
body { font-family: Arial,sans-serif; background:#f8f9fa; }
.card { border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
.stat-card { background: #fff; border-radius: 14px; padding:20px; text-align:center; box-shadow:0 4px 15px rgba(0,0,0,0.15); }
.stat-icon { font-size:45px; margin-bottom:10px; display:inline-block; border-radius:50%; padding:15px; }
.stat-icon.male { background: rgba(77,175,255,0.15); color:#4dafff; }
.stat-icon.female { background: rgba(255,107,159,0.15); color:#ff6b9f; }
.stat-icon.other { background: rgba(157,255,107,0.15); color:#9dff6b; }
#sidebar { width:250px; position:fixed; height:100vh; background:#343a40; color:#fff; padding-top:20px; transition:0.3s; }
#sidebar.collapsed { width:70px; }
#sidebar .nav-link { color:#fff; padding:12px 20px; display:flex; align-items:center; gap:10px; border-radius:6px; transition:0.2s; }
#sidebar .nav-link:hover { background:#495057; padding-left:25px; }
#sidebar.collapsed .text { display:none; }
#content { margin-left:250px; transition:0.3s; padding:20px; }
#content.expanded { margin-left:70px; }
@media(max-width:768px){ #sidebar,#content{margin-left:0;width:100% !important;} }
</style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
    <button class="btn btn-sm btn-outline-light mb-3 ms-3" id="toggleSidebar"><i class="bi bi-list"></i></button>
    <ul class="nav flex-column mt-3">
        <li class="nav-item"><a href="volunteer_dashboard.php" class="nav-link"><i class="bi bi-house"></i><span class="text">Home</span></a></li>
        <li class="nav-item"><a href="volunteer_profile.php" class="nav-link"><i class="bi bi-person"></i><span class="text">Profile</span></a></li>
        <li class="nav-item"><a href="../logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i><span class="text">Logout</span></a></li>
    </ul>
</div>

<!-- Main Content -->
<div id="content">
    <div class="mb-4">
        <h2>Welcome, <?= htmlspecialchars($volunteer['fname']) ?>!</h2>
        <p>Your dashboard to manage assigned assistance requests.</p>
    </div>

    <!-- Stats -->
    <div class="row g-2 mb-4">
        <div class="col-md-6">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-list-check"></i></div>
                <div>Total Assigned</div>
                <div class="h4"><?= $totalAssigned ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                <div>Pending</div>
                <div class="h4"><?= $pendingAssigned ?></div>
            </div>
        </div>
        <!-- <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon male"><i class="bi bi-gender-male"></i></div>
                <div>Male Volunteers</div>
                <div class="h4"></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon female"><i class="bi bi-gender-female"></i></div>
                <div>Female Volunteers</div>
                <div class="h4"></div>
            </div>
        </div> -->
    </div>

    <!-- Assigned Requests Table -->
    <div class="card p-3">
        <h5>My Assigned Assistance Requests</h5>
        <table id="assignedRequestsTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Requester</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Requested At</th>
                </tr>
            </thead>
            <tbody>
                <?php if($assignedRequests): ?>
                    <?php foreach($assignedRequests as $req): ?>
                        <tr>
                            <td><?= $req['id'] ?></td>
                            <td><?= htmlspecialchars($req['first_name'].' '.$req['last_name']) ?></td>
                            <td><?= htmlspecialchars($req['request_type']) ?></td>
                            <td><?= htmlspecialchars($req['description']) ?></td>
                            <td><?= htmlspecialchars($req['location']) ?></td>
                            <td><?= ucfirst($req['status']) ?></td>
                            <td><?= date('Y-m-d H:i', strtotime($req['requested_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">No requests assigned yet</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#assignedRequestsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [5,10,25,50],
        order:[[0,'desc']],
        language:{ search:"_INPUT_", searchPlaceholder:"Search requests..." }
    });

    // Sidebar toggle
    $('#toggleSidebar').click(function(){
        $('#sidebar').toggleClass('collapsed');
        $('#content').toggleClass('expanded');
    });
});
</script>

</body>
</html>
