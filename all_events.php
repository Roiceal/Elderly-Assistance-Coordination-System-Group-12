<?php
session_start();
include "db_connect.php"; // adjust path if needed

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header("Location: login.php");
    exit();
}

// Fetch user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch announcements
$stmt = $pdo->query("SELECT * FROM announcements ORDER BY created_at DESC");
$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elderly Assistance Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- SweetAlert -->
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

        #content {
            margin-left: 250px;
            transition: margin-left 0.3s;
            padding: 20px;
        }

        #content.expanded {
            margin-left: 70px;
        }


        .announcement-card {
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            margin-bottom: 25px;
            background: #fff;
            transition: transform 0.2s;
            padding: 25px;
            /* more padding */
        }

        .announcement-card:hover {
            transform: scale(1.02);
        }

        .card-header {
            font-weight: 700;
            font-size: 1.3rem;
            /* bigger title */
            margin-bottom: 10px;
        }

        .card-body {
            font-size: 1.1rem;
            /* bigger content */
            color: #333;
            line-height: 1.5;
            /* more readable spacing */
        }

        .card-footer {
            font-size: 0.95rem;
            /* bigger footer text */
            color: #555;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 15px;
        }

        .card-footer div.d-flex {
            gap: 5px;
        }

        .btn {
            font-size: 0.95rem;
            /* bigger buttons */
            padding: 7px 12px;
            background-color: #2a7f62;
            border: none;
        }

        .btn:hover {
            background-color: #1f4f3c;
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
    </style>

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

    <div id="content">
        <hr class="my-4">
        <h5 class="fw-bold mb-4">📢 Posted Announcements</h5>

        <div class="row">
            <?php foreach ($announcements as $row): ?>
                <div class="col-12 col-md-6 col-lg-6"> <!-- bigger cards: 2 per row -->
                    <div class="announcement-card">
                        <div class="card-header"><?= htmlspecialchars($row['title']) ?></div>
                        <div class="card-body"><?= nl2br(htmlspecialchars($row['content'])) ?></div>
                        <div class="card-footer">
                            <div>
                                Event: <?= !empty($row['date_of_event']) ? date("F d, Y", strtotime($row['date_of_event'])) : '<span class="text-muted">No Date</span>' ?><br>
                                Posted: <?= date("F d, Y h:i A", strtotime($row['created_at'])) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
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