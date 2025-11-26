<?php
session_start();

include "db_connect.php"; // adjust path if needed

$user_id = $_SESSION['user_id'];
$user_phone = $_SESSION['phone'];
$username = $_SESSION['username'];

if (!isset($user_id) && !isset($username) && !isset($username)) {
    header("location:login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elderly Assistance Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            overflow-x: hidden;
        }

        /* Sidebar */
        #sidebar {
            width: 250px;
            background: #1f4f3c;
            color: #fff;
            transition: 0.3s;
            position: fixed;
            height: 100vh;
            padding-top: 20px;
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

        /* Content */
        #content {
            margin-left: 250px;
            transition: margin-left 0.3s;
            padding: 20px;
        }

        #content.expanded {
            margin-left: 70px;
        }

        /* Dashboard Cards */
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        /* Responsive */
        @media (max-width: 768px) {

            #sidebar,
            #content {
                margin-left: 0 !important;
                width: 100%;
            }
        }

        /* ============================
           MODERN WELCOME HEADER DESIGN
           ============================ */

        .welcome-box {
            background: white;
            color: #1f4f3c;
            padding: 35px;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .welcome-box h1 {
            font-size: 2.2rem;
            font-weight: 800;
        }

        .welcome-box p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Floating Circles */
        .welcome-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            filter: blur(2px);
            animation: float 6s infinite ease-in-out;
        }

        .circle1 {
            width: 120px;
            height: 120px;
            top: -20px;
            right: -20px;
        }

        .circle2 {
            width: 80px;
            height: 80px;
            bottom: -15px;
            left: -15px;
            animation-delay: 2s;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        /* Fade in effect */
        .fade-in {
            animation: fadeIn 0.8s ease-in-out forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0px);
            }
        }

        .btn{
            background-color: #2a7f62;
            border: none;
        }
        .btn:hover{
            background-color: #1f4f3c;
        }
    </style>
</head>

<body>

    <!-- Desktop Sidebar -->
    <div id="sidebar" class="d-none d-md-block">
        <button class="btn btn-sm btn-outline-light mb-3 ms-3" id="toggleSidebar">
            <i class="bi bi-list"></i>
        </button>

        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-house"></i><span class="text">Home</span></a></li>
            <li class="nav-item"><a href="user_profile.php" class="nav-link"><i class="bi bi-person"></i><span class="text">Profile</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-calendar-event"></i><span class="text">Events</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-gear"></i><span class="text">Settings</span></a></li>
            <li class="nav-item"><a href="logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i><span class="text">Logout</span></a></li>
        </ul>
    </div>

    <!-- Mobile Navbar -->
    <nav class="navbar navbar-dark bg-dark d-md-none">
        <div class="container-fluid">
            <button class="btn btn-outline-light" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                <i class="bi bi-list"></i>
            </button>
            <span class="navbar-brand">Elderly Assistance</span>
        </div>
    </nav>

    <!-- Mobile Sidebar -->
    <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <a href="#" class="nav-link"><i class="bi bi-house"></i> Home</a>
            <a href="user_profile.php" class="nav-link"><i class="bi bi-person"></i> Profile</a>
            <a href="#" class="nav-link"><i class="bi bi-calendar-event"></i> Events</a>
            <a href="#" class="nav-link"><i class="bi bi-gear"></i> Settings</a>
            <a href="logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div id="content">
        <div class="d-flex align-items-center mb-3 p-4 bg-white rounded shadow-sm dashboard-header"
            style="border-left: 6px solid #1f4f3c;">

            <!-- Profile Image -->
            <div class="me-4">
                <img
                    src="data:image/jpeg;base64,<?= base64_encode($user['profile_image']) ?>"
                    alt="Profile"
                    class="rounded-circle shadow-sm"
                    style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #1f4f3c;">
            </div>

            <!-- Text -->
            <div>
                <h1 class="m-0 fw-bold" style="text-transform: uppercase; font-size: 2.5rem; color:#1f4f3c;">
                    Welcome, <?= htmlspecialchars($user['first_name']) ?>!
                </h1>
                <p class="m-0 mt-2" style="color:#3c6e57; font-size: 1.25rem;">
                    Your safety, health, and comfort are our top priority. How can we assist you today?
                </p>
            </div>

        </div>

        <!-- Dashboard Cards -->
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card p-3">
                    <i class="bi bi-bell fs-2 text-success"></i>
                    <h5 class="mt-2">Request Assistance</h5>
                    <p class="mt-2">Get help whenever you need it</p>
                    <a href="request_assistance.php" class="btn btn-primary btn-sm">Request</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3">
                    <i class="bi bi-heart fs-2 text-success"></i>
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

        <div class="share_location mt-5 p-4 bg-white rounded shadow-sm">
            <h4 id="status" class="mb-2">Click the button to share your location.</h4>
            <p>To locate your current location, click the button to share your location.</p>
            <button id="getLoc" class="btn btn-primary mb-3">Share my current location</button>
        </div>



    </div>

    <!-- Bootstrap JS -->
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
                // fetch('admin_page/map/save_location.php', {
                //     method: 'POST',
                //     headers: {
                //         'Content-Type': 'application/json'
                //     },
                //     body: JSON.stringify({
                //         latitude: pos.coords.latitude,
                //         longitude: pos.coords.longitude,
                //         accuracy: pos.coords.accuracy
                //     })
                // }).then(r => r.json()).then(res => {
                //     status.textContent = res.success ? 'Location saved!' : 'Error: ' + res.message;
                //     if (res.success) location.reload();
                // });

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