<?php
session_start();

$user_id = $_SESSION['user_id'];

// Redirect if not logged in
if (!isset($user_id)) {
    header("Location: login.php");
    exit();
}


include 'db_connect.php';

// Fetch logged-in user info
$stmt = $pdo->prepare("SELECT first_name, last_name, username, phone, profile_image FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// // Prepare profile image
// $profileImg = 'images/profile_placeholder.png'; // fallback

// if (!empty($user['profile_image']) && file_exists(__DIR__ . '/' . $user['profile_image'])) {
//     $profileImg = $user['profile_image']; // use relative path stored in DB
// }

// Fetch all assistance requests
$stmt2 = $pdo->prepare("
    SELECT ar.*, v.fname AS volunteer_fname, v.lname AS volunteer_lname
    FROM assistance_requests ar
    LEFT JOIN volunteers v ON ar.assigned_volunteer_id = v.id
    WHERE ar.user_id = ?
    ORDER BY ar.requested_at DESC
");
$stmt2->execute([$user_id]);
$requests = $stmt2->fetchAll(PDO::FETCH_ASSOC);

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
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            overflow-x: hidden;
            margin: 0;
        }

        #sidebar {
            width: 250px;
            background: #1f4f3c;
            color: #fff;
            position: fixed;
            height: 100vh;
            padding-top: 20px;
            transition: 0.3s;
        }

        #sidebar.collapsed {
            width: 70px;
        }

        #sidebar .nav-link {
            color: #fff;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        #sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            padding-left: 25px;
        }

        #sidebar.collapsed .text {
            display: none;
        }

        #content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s;
        }

        #content.expanded {
            margin-left: 70px;
        }

        .profile-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            padding: 30px;
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .profile-img {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #c5e1dc;
        }

        .profile-info h2 {
            font-weight: 700;
            color: #1f4f3c;
        }

        .profile-info p {
            font-size: 1rem;
            margin: 5px 0;
            color: #555;
        }

        .profile-actions {
            margin-top: 15px;
        }

        .profile-actions .btn {
            margin-right: 10px;
        }

        .nav-tabs .nav-link {
            color: white;
            font-weight: 600;
        }

        .nav-tabs .nav-link.active {
            background-color: #2a7f62;
            color: white;
            border-radius: 10px 10px 0 0;
        }

        .nav-tabs .nav-link:not(.active) {
            background-color: #e8f0ed;
            color: #2a7f62;
            border-radius: 10px 10px 0 0;
        }

        .tab-content {
            background: white;
            padding: 20px;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .btn-primary {
            background-color: #2a7f62;
            color: white;
            border: none;
        }

        @media (max-width: 768px) {

            #sidebar,
            #content {
                margin-left: 0 !important;
                width: 100%;
            }

            .profile-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div id="sidebar" class="d-none d-md-block">
        <button class="btn btn-sm btn-outline-light mb-3 ms-3" id="toggleSidebar"><i class="bi bi-list"></i></button>
        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a href="dashboard_elders.php" class="nav-link"><i class="bi bi-house"></i><span class="text">Home</span></a></li>
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
            <img src="data:image/jpeg;base64,<?= base64_encode($user['profile_image']) ?>" alt="Profile Image" class="profile-img">
            <div class="profile-info">
                <h2><?= htmlspecialchars($user['username']) ?></h2>
                <p><strong>Name:</strong> <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? 'Not Set') ?></p>
                <div class="profile-actions">
                    <a href="edit_profile_elder.php" class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i> Edit Profile</a>
                    <!-- <a href="change_password.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-lock"></i> Change Password</a> -->
                </div>
            </div>
        </div>

        <!-- Profile Tabs -->
        <ul class="nav nav-tabs mb-3" id="profileTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">Activity</button>
            </li>
            <!-- <li class="nav-item" role="presentation">
                <button class="nav-link" id="health-tab" data-bs-toggle="tab" data-bs-target="#health" type="button" role="tab">Health Logs</button>
            </li> -->
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="requests-tab" data-bs-toggle="tab" data-bs-target="#requests" type="button" role="tab">Requests</button>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="activity" role="tabpanel">
                <p>Recent activities and logins will appear here.</p>
            </div>
            <!-- <div class="tab-pane fade" id="health" role="tabpanel">
                <p>Health records, exercise routines, and diet logs will appear here.</p>
            </div> -->
            <div class="tab-pane fade" id="requests" role="tabpanel">
                <div class="p-4 bg-white rounded shadow-sm">
                    <h4 class="fw-bold">All Assistance Requests</h4>

                    <?php if (count($requests) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Request Type</th>
                                        <th>Description</th>
                                        <th>Assigned Volunteer</th>
                                        <th>Status</th>
                                        <th>Requested At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($requests as $index => $req):
                                        $statusColors = [
                                            'pending' => 'bg-warning',
                                            'in_progress' => 'bg-info',
                                            'for_elders_approval' => 'bg-secondary',
                                            'completed' => 'bg-success'
                                        ];
                                        $badgeClass = $statusColors[$req['status']] ?? 'bg-dark';
                                    ?>
                                        <tr class="request-row" data-id="<?= $req['id'] ?>">
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($req['request_type']) ?></td>
                                            <td><?= htmlspecialchars($req['description']) ?></td>
                                            <td>
                                                <?= !empty($req['assigned_volunteer_id'])
                                                    ? htmlspecialchars($req['volunteer_fname'] . ' ' . $req['volunteer_lname'])
                                                    : '<span class="text-muted">Not assigned yet</span>' ?>
                                            </td>
                                            <td><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($req['status']) ?></span></td>
                                            <td><?= date('M d, Y H:i', strtotime($req['requested_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">You have no assistance requests yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="share_location mt-5 p-4 bg-white rounded shadow-sm">
            <h4 id="status" class="mb-2">Click the button to share your location.</h4>
            <p>To locate your current location, click the button to share your location.</p>
            <button id="getLoc" class="btn btn-primary mb-3">Share my current location</button>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('getLoc').addEventListener('click', () => {
            const status = document.getElementById('status');
            if (!navigator.geolocation) {
                status.textContent = 'Geolocation not supported.';
                return;
            }
            status.textContent = 'Requesting permission...';
            navigator.geolocation.getCurrentPosition(pos => {

                fetch('admin_page/map/save_location.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            latitude: pos.coords.latitude,
                            longitude: pos.coords.longitude,
                            accuracy: pos.coords.accuracy
                        })
                    })
                    .then(r => r.text())
                    .then(txt => {
                        console.log("RAW RESPONSE:", txt);
                        status.textContent = "Location saved!";
                    });

            }, err => {
                status.textContent = 'Error: ' + err.message;
            }, {
                enableHighAccuracy: true
            });
        });

        document.getElementById("toggleSidebar").addEventListener("click", () => {
            const sidebar = document.getElementById("sidebar");
            const content = document.getElementById("content");
            sidebar.classList.toggle("collapsed");
            content.classList.toggle("expanded");
        });
    </script>
</body>

</html>