<?php
session_start();
include "db_connect.php";

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header("Location: login.php");
    exit();
}

$font_size = $_COOKIE['font_size'] ?? '16px';
$high_contrast = isset($_COOKIE['high_contrast']) && $_COOKIE['high_contrast'] === '1';

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt2 = $pdo->prepare("
    SELECT ar.*, v.fname AS volunteer_fname, v.lname AS volunteer_lname
    FROM assistance_requests ar
    LEFT JOIN volunteers v ON ar.assigned_volunteer_id = v.id
    WHERE ar.user_id = ? 
    AND ar.status != 'completed'
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
    <title>Elderly Assistance Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
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


        /* BUTTONS */
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

        /* CARDS */
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn {
            background-color: #2a7f62;
            border: none;
        }

        .btn:hover {
            background-color: #1f4f3c;
        }

        .request-row:hover {
            cursor: pointer;
            background-color: rgba(42, 127, 98, 0.1);
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

    <!-- CONTENT -->
    <div id="content">
        <div class="d-flex align-items-center mb-4 p-4 bg-white rounded shadow-sm" style="border-left:6px solid #1f4f3c;">
            <div class="me-4">
                <img src="<?= $user['profile_image'] ? 'data:image/jpeg;base64,' . base64_encode($user['profile_image']) : 'assets/default-avatar.png' ?>"
                    class="rounded-circle shadow-sm" style="width:120px;height:120px;object-fit:cover;">
            </div>
            <div>
                <h1 class="fw-bold" style="text-transform:uppercase;color:#1f4f3c;">Welcome, <?= htmlspecialchars($user['first_name']) ?>!</h1>
                <p class="text-muted">Your safety, health, and comfort are our top priority.</p>
            </div>
        </div>

        <!-- CARDS -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card p-3">
                    <i class="bi bi-bell fs-2 text-success"></i>
                    <h5 class="mt-2">Request Assistance</h5>
                    <p>Get help whenever you need it.</p>
                    <a href="request_assistance.php" class="btn btn-sm text-white">Request</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <i class="bi bi-calendar-event fs-2 text-success"></i>
                    <h5 class="mt-2">Events</h5>
                    <p>See upcoming activities for seniors.</p>
                    <a href="all_events.php" class="btn btn-sm text-white">Check</a>
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="mt-5 p-4 bg-white rounded shadow-sm">
            <h4 class="fw-bold mb-3">All Assistance Requests</h4>
            <?php if (count($requests) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Request Type</th>
                                <th>Description</th>
                                <th>Volunteer</th>
                                <th>Status</th>
                                <th>Requested At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $i => $req):
                                $colors = [
                                    'pending' => 'bg-warning',
                                    'in_progress' => 'bg-info',
                                    'for_elders_approval' => 'bg-secondary',
                                    'completed' => 'bg-success'
                                ];
                            ?>
                                <tr class="request-row" data-id="<?= $req['id'] ?>">
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($req['request_type']) ?></td>
                                    <td><?= htmlspecialchars($req['description']) ?></td>
                                    <td><?= $req['assigned_volunteer_id'] ? $req['volunteer_fname'] . ' ' . $req['volunteer_lname'] : '<span class="text-muted">Not Assigned</span>' ?></td>
                                    <td><span class="badge <?= $colors[$req['status']] ?? 'bg-dark' ?>"><?= $req['status'] ?></span></td>
                                    <td><?= date("M d, Y H:i", strtotime($req['requested_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No requests yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>

        // ------------------ SIDEBAR FUNCTIONALITY ------------------
        document.addEventListener("DOMContentLoaded", function() {

            const sidebar = document.getElementById("sidebar");
            const overlay = document.getElementById("overlay");
            const mobileBtn = document.getElementById("mobileMenuBtn");
            const desktopToggle = document.getElementById("toggleSidebar");

            // ------------------ DESKTOP SIDEBAR TOGGLE ------------------
            desktopToggle.addEventListener("click", function(e) {
                e.stopPropagation();
                if (window.innerWidth >= 992) {
                    sidebar.classList.toggle("collapsed");
                    document.getElementById("content").classList.toggle("expanded");
                }
            });

            // ------------------ MOBILE HAMBURGER BUTTON ------------------
            mobileBtn.addEventListener("click", function() {
                sidebar.classList.add("open");
                overlay.classList.add("show");
            });

            // ------------------ CLOSE SIDEBAR WHEN OVERLAY CLICKED ------------------
            overlay.addEventListener("click", function() {
                sidebar.classList.remove("open");
                overlay.classList.remove("show");
            });

        });




        // Table row click
        document.querySelectorAll('.request-row').forEach(r => {
            r.addEventListener('click', () => {
                location.href = "confirmation_assistance.php?id=" + r.dataset.id;
            });
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