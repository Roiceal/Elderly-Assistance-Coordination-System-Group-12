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
        }

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
    </style>

    </style>
</head>

<body>

    <!-- Sidebar -->
    <div id="sidebar" class="d-none d-md-block">
        <button class="btn btn-sm btn-outline-light mb-3 ms-3" id="toggleSidebar">
            <i class="bi bi-list"></i>
        </button>
        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a href="dashboard_elders.php" class="nav-link"><i class="bi bi-house"></i><span class="text">Home</span></a></li>
            <li class="nav-item"><a href="user_profile.php" class="nav-link"><i class="bi bi-person"></i><span class="text">Profile</span></a></li>
            <li class="nav-item"><a href="all_events.php" class="nav-link"><i class="bi bi-calendar-event"></i><span class="text">Events</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-gear"></i><span class="text">Settings</span></a></li>
            <li class="nav-item"><a href="logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i><span class="text">Logout</span></a></li>
        </ul>
    </div>

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
        $(document).ready(function() {
            // SweetAlert Delete
            $('.delete-btn').click(function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This announcement will be permanently deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `delete_announcement.php?id=${id}`;
                    }
                });
            });

            // Sidebar toggle
            $('#toggleSidebar').click(function() {
                $('#sidebar').toggleClass('collapsed');
                $('#content').toggleClass('expanded');
            });
        });
    </script>

</body>

</html>