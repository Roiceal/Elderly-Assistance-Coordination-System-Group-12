<?php 
session_start();?>
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
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            overflow-x: hidden;
        }

        /* Sidebar */
        #sidebar {
            width: 250px;
            background: black;
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

        .card:hover {
            transform: translateY(-5px);
        }

        /* Responsive mobile adjustments */
        @media (max-width: 768px) {

            #sidebar,
            #content {
                margin-left: 0 !important;
                width: 100%;
            }
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
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-person"></i><span class="text">Profile</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-heart"></i><span class="text">Health & Wellness</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-bell"></i><span class="text">Alerts</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-calendar-event"></i><span class="text">Events</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-gear"></i><span class="text">Settings</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-box-arrow-right"></i><span class="text">Logout</span></a></li>
        </ul>
    </div>

    <!-- Mobile Top Navbar -->
    <nav class="navbar navbar-dark bg-dark d-md-none">
        <div class="container-fluid">
            <button class="btn btn-outline-light" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                <i class="bi bi-list"></i>
            </button>
            <span class="navbar-brand">Elderly Assistance</span>
        </div>
    </nav>

    <!-- Mobile Sidebar (Offcanvas) -->
    <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <a href="#" class="nav-link"><i class="bi bi-house"></i> Home</a>
            <a href="#" class="nav-link"><i class="bi bi-person"></i> Profile</a>
            <a href="#" class="nav-link"><i class="bi bi-heart"></i> Health & Wellness</a>
            <a href="#" class="nav-link"><i class="bi bi-bell"></i> Alerts</a>
            <a href="#" class="nav-link"><i class="bi bi-calendar-event"></i> Events</a>
            <a href="#" class="nav-link"><i class="bi bi-gear"></i> Settings</a>
            <a href="#" class="nav-link"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div id="content">
        <h1 class="mb-4">Welcome, John!</h1>

        <!-- Dashboard Cards -->
        <div class="row g-4">
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Desktop sidebar toggle
        document.getElementById("toggleSidebar").addEventListener("click", () => {
            const sidebar = document.getElementById("sidebar");
            const content = document.getElementById("content");
            sidebar.classList.toggle("collapsed");
            content.classList.toggle("expanded");
        });
    </script>

</body>

</html>