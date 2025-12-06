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
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            overflow-x: hidden;
            transition: all 0.2s ease-in-out;
        }

        body.high-contrast {
            background: black;
            color: white;
        }

        body.high-contrast a {
            color: yellow;
        }

        body.high-contrast .card {
            background: #333;
            color: white;
        }

        body.high-contrast .btn {
            background-color: #555;
            color: white;
        }


        /* SIDEBAR */
        #sidebar {
            width: 250px;
            background: #1f4f3c;
            color: #fff;
            transition: 0.3s ease;
            position: fixed;
            height: 100vh;
            padding-top: 20px;
            z-index: 5000;
            /* FIX: should be above header */
            left: 0;
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

        /* CONTENT */
        #content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
            padding: 20px;
        }

        #content.expanded {
            margin-left: 70px;
        }

        /* MOBILE SIDEBAR FIX */
        @media (max-width: 992px) {

            #sidebar {
                width: 260px !important;
                position: fixed;
                /* REQUIRED */
                top: 0;
                /* REQUIRED */
                left: 0;
                transform: translateX(-260px);
                height: 100vh;
                /* Ensures full screen sidebar */
                backdrop-filter: blur(12px);
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.25);
                z-index: 5000;
            }


            #sidebar.open {
                transform: translateX(0);
            }

            #sidebar .text {
                display: inline !important;
            }

            #overlay {
                display: none;
                position: fixed;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.45);
                backdrop-filter: blur(3px);
                top: 0;
                left: 0;
                z-index: 4000;
            }

            #overlay.show {
                display: block;
            }

            #content {
                margin-left: 0 !important;
            }
        }



        /* NAVIGATIOn */

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

        .btn {
            background-color: #2a7f62;
            border: none;
        }

        .btn-primary {
            background-color: #2a7f62;
            color: white;
            border: none;
        }

        /* burger button */
        #mobileMenuBtn {
            border: solid 2px #2a7f62;
            background-color: white;
            color: black;
        }

        #mobileMenuBtn:hover {
            border: solid 2px #2a7f62;
            background-color: #2a7f62;
            color: white;
        }

        /* burger button */

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

<body class="<?= $high_contrast ? 'high-contrast' : '' ?>" style="font-size: <?= $font_size ?>;">

    <!-- MOBILE HAMBURGER -->
    <button id="mobileMenuBtn" class="btn btn-light d-md-none" style="z-index: 3000;margin-left:10px;margin-top:10px;">
        <i class="bi bi-list fs-3"></i>
    </button>

    <!-- SIDEBAR -->
    <div id="sidebar">
        <button class="btn btn-sm btn-outline-light mb-3 ms-3 d-none d-md-block" id="toggleSidebar"><i class="bi bi-list"></i></button>
        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a href="dashboard_elders.php" class="nav-link"><i class="bi bi-house"></i> <span class="text">Home</span></a></li>
            <li class="nav-item"><a href="user_profile.php" class="nav-link"><i class="bi bi-person"></i> <span class="text">Profile</span></a></li>
            <li class="nav-item"><a href="all_events.php" class="nav-link"><i class="bi bi-calendar-event"></i> <span class="text">Events</span></a></li>
            <li class="nav-item"><a href="settings.php" class="nav-link"><i class="bi bi-gear"></i> <span class="text">Settings</span></a></li>
            <li class="nav-item"><a href="logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i> <span class="text">Logout</span></a></li>
        </ul>
    </div>

    <!-- OVERLAY -->
    <div id="overlay"></div>

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
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="requests-tab" data-bs-toggle="tab" data-bs-target="#requests" type="button" role="tab">Requests</button>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="activity" role="tabpanel">
                <p>Recent activities and logins will appear here.</p>
            </div>
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
                status.textContent = 'Geolocation not supported by your browser.';
                return;
            }

            status.textContent = 'Requesting permission...';

            navigator.geolocation.getCurrentPosition(
                pos => {
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
                        })
                        .catch(err => {
                            console.error(err);
                            status.textContent = "Error saving location!";
                        });
                },
                err => {
                    console.error(err);
                    switch (err.code) {
                        case err.PERMISSION_DENIED:
                            status.textContent = "Permission denied. Please allow location access.";
                            break;
                        case err.POSITION_UNAVAILABLE:
                            status.textContent = "Location unavailable.";
                            break;
                        case err.TIMEOUT:
                            status.textContent = "Request timed out. Try again.";
                            break;
                        default:
                            status.textContent = "Error: " + err.message;
                    }
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        });


        // Mobile menu
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("overlay");
        const mobileMenuBtn = document.getElementById("mobileMenuBtn");

        mobileMenuBtn.addEventListener("click", () => {
            sidebar.classList.add("open");
            overlay.classList.add("show");
        });

        overlay.addEventListener("click", () => {
            sidebar.classList.remove("open");
            overlay.classList.remove("show");
        });

        document.getElementById("toggleSidebar").addEventListener("click", () => {
            const sidebar = document.getElementById("sidebar");
            const content = document.getElementById("content");
            sidebar.classList.toggle("collapsed");
            content.classList.toggle("expanded");
        });

        // ------------------- Accessibility -------------------
        function getCookie(name) {
            let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            if (match) return match[2];
            return null;
        }

        function setCookie(name, value, days) {
            let d = new Date();
            d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = name + "=" + value + ";expires=" + d.toUTCString() + ";path=/";
        }

        function adjustFontSize(action) {
            let currentSize = parseInt(window.getComputedStyle(document.body).fontSize);
            if (action === 'increase') currentSize += 2;
            else if (action === 'decrease') currentSize = Math.max(12, currentSize - 2);

            // Apply font size to all elements including sidebar
            document.querySelectorAll('body, #content, #sidebar, #content *, #sidebar *').forEach(el => {
                el.style.fontSize = currentSize + 'px';
            });

            setCookie('font_size', currentSize + 'px', 30);
        }

        function toggleContrast() {
            document.body.classList.toggle('high-contrast');
            setCookie('high_contrast', document.body.classList.contains('high-contrast') ? 1 : 0, 30);
        }

        // Apply saved preferences
        window.addEventListener('DOMContentLoaded', () => {
            const fontSize = getCookie('font_size');
            const highContrast = getCookie('high_contrast');

            if (fontSize) {
                document.querySelectorAll('body, #content, #sidebar, #content *, #sidebar *').forEach(el => el.style.fontSize = fontSize);
            }
            if (highContrast === '1') document.body.classList.add('high-contrast');
        });
    </script>
</body>

</html>