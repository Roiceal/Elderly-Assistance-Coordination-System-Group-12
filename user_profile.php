<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("location:login.php");
    exit();
}

include 'db_connect.php';

// Fetch logged-in user info
$stmt = $pdo->prepare("SELECT first_name, last_name, username, phone, profile_image FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Prepare profile image
$profileImg = 'images/profile_placeholder.png'; // fallback

if (!empty($user['profile_image']) && file_exists(__DIR__ . '/' . $user['profile_image'])) {
    $profileImg = $user['profile_image']; // use relative path stored in DB
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Profile - Elderly Assistance</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
/* Your previous styles remain unchanged */
body { font-family: Arial, sans-serif; background: #f8f9fa; overflow-x: hidden; margin: 0; }
#sidebar { width: 250px; background: black; color: #fff; position: fixed; height: 100vh; padding-top: 20px; transition: 0.3s; }
#sidebar.collapsed { width: 70px; }
#sidebar .nav-link { color: #fff; padding: 12px 20px; display: flex; align-items: center; gap: 15px; border-radius: 8px; transition: all 0.2s; }
#sidebar .nav-link:hover { background: rgba(255, 255, 255, 0.15); padding-left: 25px; }
#sidebar.collapsed .text { display: none; }
#content { margin-left: 250px; padding: 20px; transition: margin-left 0.3s; }
#content.expanded { margin-left: 70px; }
.profile-card { background: white; border-radius: 20px; box-shadow: 0 8px 20px rgba(0,0,0,0.15); padding:30px; display:flex; align-items:center; gap:30px; margin-bottom:30px; flex-wrap:wrap; }
.profile-img { width:140px; height:140px; border-radius:50%; object-fit:cover; border:4px solid #4c6ef5; }
.profile-info h2 { font-weight:700; color:#4c6ef5; }
.profile-info p { font-size:1rem; margin:5px 0; color:#555; }
.profile-actions { margin-top:15px; }
.profile-actions .btn { margin-right:10px; }
.nav-tabs .nav-link { color:#4c6ef5; font-weight:600; }
.nav-tabs .nav-link.active { background-color:#4c6ef5; color:white; border-radius:10px 10px 0 0; }
.tab-content { background:white; padding:20px; border-radius:0 0 15px 15px; box-shadow:0 4px 15px rgba(0,0,0,0.1); }
.card { border-radius:15px; box-shadow:0 4px 15px rgba(0,0,0,0.1); transition: transform 0.2s; }
.card:hover { transform: translateY(-5px); }
@media (max-width: 768px) {
    #sidebar, #content { margin-left:0 !important; width:100%; }
    .profile-card { flex-direction:column; align-items:center; text-align:center; }
}
</style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar" class="d-none d-md-block">
    <button class="btn btn-sm btn-outline-light mb-3 ms-3" id="toggleSidebar"><i class="bi bi-list"></i></button>
    <ul class="nav flex-column mt-3">
        <li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="bi bi-house"></i><span class="text">Home</span></a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-person"></i><span class="text">Profile</span></a></li>
        <li class="nav-item"><a href="events.php" class="nav-link"><i class="bi bi-calendar-event"></i><span class="text">Events</span></a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-gear"></i><span class="text">Settings</span></a></li>
        <li class="nav-item"><a href="logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i><span class="text">Logout</span></a></li>
    </ul>
</div>

<!-- Content -->
<div id="content">

    <!-- Profile Card -->
    <div class="profile-card fade-in">
        <img src="<?= htmlspecialchars($profileImg) ?>" alt="Profile Picture" class="profile-img">
        <div class="profile-info">
            <h2><?= htmlspecialchars($user['username']) ?></h2>
            <p><strong>Name:</strong> <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? 'Not Set') ?></p>
            <div class="profile-actions">
                <a href="edit_profile.php" class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i> Edit Profile</a>
                <a href="change_password.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-lock"></i> Change Password</a>
            </div>
        </div>
    </div>

    <!-- Profile Tabs -->
    <ul class="nav nav-tabs mb-3" id="profileTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">Activity</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="health-tab" data-bs-toggle="tab" data-bs-target="#health" type="button" role="tab">Health Logs</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="requests-tab" data-bs-toggle="tab" data-bs-target="#requests" type="button" role="tab">Requests</button>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="activity" role="tabpanel">
            <p>Recent activities and logins will appear here.</p>
        </div>
        <div class="tab-pane fade" id="health" role="tabpanel">
            <p>Health records, exercise routines, and diet logs will appear here.</p>
        </div>
        <div class="tab-pane fade" id="requests" role="tabpanel">
            <p>Requests for assistance, past submissions, and statuses will appear here.</p>
        </div>
    </div>

    <!-- Quick Action Cards -->
    <div class="row g-4 mt-4">
        <div class="col-md-4">
            <div class="card p-3">
                <i class="bi bi-bell fs-2 text-primary"></i>
                <h5 class="mt-2">Request Assistance</h5>
                <p class="mt-2">Get help whenever you need it</p>
                <a href="request_assistance.php" class="btn btn-primary btn-sm">Request</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <i class="bi bi-heart fs-2 text-danger"></i>
                <h5 class="mt-2">Health & Wellness</h5>
                <p>Access exercise routines, diet tips, and reminders.</p>
                <a href="#" class="btn btn-danger btn-sm">Explore</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <i class="bi bi-calendar-event fs-2 text-success"></i>
                <h5 class="mt-2">Events</h5>
                <p>See upcoming community activities for seniors.</p>
                <a href="#" class="btn btn-success btn-sm">Check</a>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById("toggleSidebar").addEventListener("click", () => {
    const sidebar = document.getElementById("sidebar");
    const content = document.getElementById("content");
    sidebar.classList.toggle("collapsed");
    content.classList.toggle("expanded");
});
</script>
</body>
</html>
