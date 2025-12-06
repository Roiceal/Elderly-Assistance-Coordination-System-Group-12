<?php
session_start();
include "db_connect.php";

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header("Location: login.php");
    exit();
}

// Fetch user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Accessibility preferences from cookies or defaults
$font_size = $_COOKIE['font_size'] ?? '16px';
$high_contrast = isset($_COOKIE['high_contrast']) && $_COOKIE['high_contrast'] === '1';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - ElderCare Connect</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        /* Accessibility buttons */
        #accessibility-options {
            margin-bottom: 20px;
        }

        #accessibility-options button {
            margin-right: 10px;
        }

        /* High contrast */
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
    </style>
</head>

<body class="<?= $high_contrast ? 'high-contrast' : '' ?>">

    <!-- MOBILE HAMBURGER -->
    <button id="mobileMenuBtn" class="btn btn-light d-md-none"
        style="z-index: 3000;margin-left: 10px; margin-top: 10px;">
        <i class="bi bi-list fs-3"></i>
    </button>

    <!-- SIDEBAR -->
    <div id="sidebar">
        <button class="btn btn-sm btn-outline-light mb-3 ms-3 d-none d-md-block" id="toggleSidebar">
            <i class="bi bi-list"></i>
        </button>
        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a href="dashboard_elders.php" class="nav-link"><i class="bi bi-house"></i> <span class="text">Home</span></a></li>
            <li class="nav-item"><a href="user_profile.php" class="nav-link"><i class="bi bi-person"></i> <span class="text">Profile</span></a></li>
            <li class="nav-item"><a href="all_events.php" class="nav-link"><i class="bi bi-calendar-event"></i> <span class="text">Events</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-gear"></i> <span class="text">Settings</span></a></li>
            <li class="nav-item"><a href="logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i> <span class="text">Logout</span></a></li>
        </ul>
    </div>

    <!-- Overlay -->
    <div id="overlay"></div>

    <!-- CONTENT -->
    <div id="content">
        <h2 class="mb-4">User Settings</h2>

        <!-- Accessibility Options -->
        <div id="accessibility-options" class="mb-3">
            <label><b>Accessibility Options</b></label><br>
            <button class="btn btn-sm btn-outline-secondary" onclick="adjustFontSize('increase')">A+</button>
            <button class="btn btn-sm btn-outline-secondary" onclick="adjustFontSize('decrease')">A-</button>
            <button class="btn btn-sm btn-outline-secondary" onclick="toggleContrast()">Toggle Contrast</button>
        </div>

        <!-- Update Profile -->
        <div class="card p-4 mb-4">
            <h4>Profile Information</h4>
            <form action="update_profile.php" method="post">
                <div class="mb-3">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>" required>
                </div>
                <button class="btn btn-success">Update Profile</button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="card p-4 mb-4">
            <h4>Change Password</h4>
            <form action="change_password.php" method="post">
                <div class="mb-3 position-relative">
                    <label>New Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <span class="toggle-password" onclick="togglePassword()" style="position:absolute; right:15px; top:38px; cursor:pointer;">
                        <i class="bi bi-eye-fill" id="eyeIcon"></i>
                    </span>
                </div>
                <button class="btn btn-warning">Change Password</button>
            </form>
        </div>
    </div>

    <!-- JS -->
    <script>
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("overlay");
        const mobileMenuBtn = document.getElementById("mobileMenuBtn");

        // Mobile menu toggle
        mobileMenuBtn.addEventListener("click", () => {
            sidebar.classList.add("open");
            overlay.classList.add("show");
        });

        overlay.addEventListener("click", () => {
            sidebar.classList.remove("open");
            overlay.classList.remove("show");
        });

        // Clicking sidebar links closes menu on mobile
        sidebar.querySelectorAll(".nav-link").forEach(link => {
            link.addEventListener("click", () => {
                if (window.innerWidth <= 992) {
                    sidebar.classList.remove("open");
                    overlay.classList.remove("show");
                }
            });
        });

        // Desktop collapse
        document.getElementById("toggleSidebar").addEventListener("click", () => {
            if (window.innerWidth > 992) {
                sidebar.classList.toggle("collapsed");
                document.getElementById("content").classList.toggle("expanded");
            }
        });

        // Password toggle
        function togglePassword() {
            const password = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");
            if (password.type === "password") {
                password.type = "text";
                eyeIcon.classList.replace("bi-eye-fill", "bi-eye-slash-fill");
            } else {
                password.type = "password";
                eyeIcon.classList.replace("bi-eye-slash-fill", "bi-eye-fill");
            }
        }

        // Accessibility functions
        function setCookie(name, value, days) {
            let d = new Date();
            d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = name + "=" + value + ";expires=" + d.toUTCString() + ";path=/";
        }

        function getCookie(name) {
            let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            if (match) return match[2];
            return null;
        }

        function adjustFontSize(action) {
            let body = document.body;
            let currentSize = parseInt(window.getComputedStyle(body).fontSize);

            if (action === 'increase') currentSize += 2;
            else if (action === 'decrease') currentSize = Math.max(12, currentSize - 2);

            // Apply font size to everything
            const allElements = document.querySelectorAll('body, #content, #sidebar, #content * , #sidebar *');
            allElements.forEach(el => el.style.fontSize = currentSize + 'px');

            setCookie('font_size', currentSize + 'px', 30);
        }

        function toggleContrast() {
            document.body.classList.toggle("high-contrast");
            const isContrast = document.body.classList.contains("high-contrast") ? 1 : 0;
            setCookie('high_contrast', isContrast, 30);
        }

        // Apply persisted settings on page load
        window.addEventListener('DOMContentLoaded', () => {
            const fontSize = getCookie('font_size');
            const highContrast = getCookie('high_contrast');

            if (fontSize) {
                const allElements = document.querySelectorAll('body, #content, #sidebar, #content * , #sidebar *');
                allElements.forEach(el => el.style.fontSize = fontSize);
            }
            if (highContrast === '1') document.body.classList.add('high-contrast');
        });
    </script>

</body>
</html>
