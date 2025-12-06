<?php
session_start();
$config = require __DIR__ . '/config.php';

// Connect to database
try {
    $pdo = new PDO($config['db']['dsn'], $config['db']['user'], $config['db']['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

// New Requests (Pending)
$stmt = $pdo->query("SELECT COUNT(*) as pending_count FROM assistance_requests WHERE status='Pending'");
$pendingRequests = $stmt->fetch(PDO::FETCH_ASSOC)['pending_count'] ?? 0;

// Total Users
$stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users");
$totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'] ?? 0;

// Fetch assistance requests with user info
$stmt = $pdo->query("
    SELECT ar.id, u.username AS user_name, ar.request_type, ar.description, ar.location, ar.status, ar.requested_at
    FROM assistance_requests ar
    LEFT JOIN users u ON ar.user_id = u.id
    ORDER BY ar.requested_at DESC
");
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: whitesmoke;
            color: black;
        }

        #sidebar {
            width: 250px;
            background-color: #1c1c1c;
            position: fixed;
            height: 100vh;
            padding-top: 20px;
            transition: width 0.3s;
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
            background-color: #333;
            padding-left: 25px;
        }

        #sidebar.collapsed .text {
            display: none;
        }

        #content {
            margin-left: 250px;
            transition: margin-left 0.3s;
            padding: 20px;
        }

        #content.expanded {
            margin-left: 70px;
        }

        .card {
            border-radius: 12px;
            background-color: white;
            color: black;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        #topbar {
            background-color: white;
            color: black;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #333;
        }

        @media(max-width: 768px) {

            #sidebar,
            #content {
                margin-left: 0 !important;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div id="sidebar">
        <button class="btn btn-sm btn-outline-light mb-3 ms-3" id="toggleSidebar">
            <i class="bi bi-list"></i>
        </button>
        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a href="admin.php" class="nav-link"><i class="bi bi-speedometer2"></i><span class="text">Dashboard</span></a></li>
            <li class="nav-item"><a href="userslist.php" class="nav-link"><i class="bi bi-people-fill"></i><span class="text">Users</span></a></li>
            <li class="nav-item"><a href="map/location_map.php" class="nav-link"><i class="bi bi-geo-alt-fill"></i><span class="text">Locate Elder</span></a></li>
            <li class="nav-item"><a href="assign_volunteer.php" class="nav-link"><i class="bi bi-list-check"></i><span class="text">Volunteer Assignment</span></a></li>
            <li class="nav-item"><a href="make_announcement.php" class="nav-link"><i class="bi bi-megaphone-fill"></i><span class="text">Announcement</span></a></li>
            <li class="nav-item"><a href="../rfid/" class="nav-link"><i class="bi bi-person-badge-fill"></i><span class="text">Attendance</span></a></li>
            <li class="nav-item"><a href="../logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i><span class="text">Logout</span></a></li>
        </ul>
    </div>

    <!-- Content -->
    <div id="content">
        <div id="topbar">
            <h4>Annoucement</h4>
            <div>
                <i class="bi bi-person-circle fs-4"></i> Admin
            </div>
        </div>

        <div class="container">
            <h5 class="mb-3 fw-bold"></h5>

            <!-- TABS -->
            <ul class="nav nav-tabs mb-3" id="announcementTabs" role="tablist">

                <!-- ANNOUNCEMENT BY TEXT -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link active"
                        id="text-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#announcement_text"
                        type="button" role="tab">
                        Announcement by Text
                    </button>
                </li>

                <!-- ANNOUNCEMENT BY WEBSITE -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                        id="website-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#announcement_website"
                        type="button" role="tab">
                        Announcement by Website
                    </button>
                </li>
            </ul>

            <!-- TAB CONTENT -->
            <div class="tab-content" id="announcementTabsContent">

                <!-- TAB CONTENT: ANNOUNCEMENT BY TEXT -->
                <div class="tab-pane fade show active p-3 bg-white border rounded"
                    id="announcement_text" role="tabpanel">

                    <h6 class="fw-bold mb-3">Send SMS Announcement</h6>
                    <form action="../send_message/send_sms.php" method="POST">
                        <label class="form-label">Select Category:</label>
                        <select name="category" class="form-select mb-3" required>
                            <option disabled selected>Select Category</option>
                            <option value="elders">Elders</option>
                            <option value="volunteers">Volunteers</option>
                            <option value="admin">Admin</option>
                            <option value="all">All (Admins, Elders, Volunteers)</option>
                        </select>

                        <label class="form-label">Message:</label>
                        <textarea name="message" class="form-control mb-3" rows="4" required></textarea>

                        <button type="submit" class="btn btn-success w-100">Send SMS</button>
                    </form>
                </div>

                <!-- TAB CONTENT: ANNOUNCEMENT BY WEBSITE -->
                <div class="tab-pane fade p-3 bg-white border rounded"
                    id="announcement_website" role="tabpanel">

                    <h6 class="fw-bold mb-3">Post Announcement on Website</h6>

                    <form action="post_announcement.php" method="POST">

                        <label class="form-label">Announcement Title:</label>
                        <input type="text" name="title" class="form-control mb-3" required>

                        <label class="form-label">Content:</label>
                        <textarea name="content" class="form-control mb-3" rows="5" required></textarea>

                        <label class="form-label">Date of Event:</label>
                        <input type="date" name="date_of_event" class="form-control mb-3">

                        <button type="submit" class="btn btn-primary w-100">Post Announcement</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        // Sidebar toggle
        document.getElementById("toggleSidebar").addEventListener("click", () => {
            const sidebar = document.getElementById("sidebar");
            const content = document.getElementById("content");
            sidebar.classList.toggle("collapsed");
            content.classList.toggle("expanded");
        });
    </script>
</body>

</html>