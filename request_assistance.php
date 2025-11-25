<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Assistance</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
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

        /* Form Card */
        .form-card {
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            background: #fff;
            padding: 30px;
            max-width: 600px;
            margin: auto;
        }

        .form-card h2 {
            color: #0d6efd;
            font-weight: bold;
        }

        .form-card label {
            font-weight: 500;
        }

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


    <!-- Sidebar -->
    <div id="sidebar" class="d-none d-md-block">
        <button class="btn btn-sm btn-outline-light mb-3 ms-3" id="toggleSidebar">
            <i class="bi bi-list"></i>
        </button>
        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a href="dashboard2.php" class="nav-link"><i class="bi bi-house"></i><span class="text">Home</span></a></li>
            <li class="nav-item"><a href="profile.php" class="nav-link"><i class="bi bi-person"></i><span class="text">Profile</span></a></li>
            <li class="nav-item"><a href="assistance_request_form.php" class="nav-link"><i class="bi bi-bell"></i><span class="text">Request Assistance</span></a></li>
            <li class="nav-item"><a href="health.php" class="nav-link"><i class="bi bi-heart"></i><span class="text">Health & Wellness</span></a></li>
            <li class="nav-item"><a href="events.php" class="nav-link"><i class="bi bi-calendar-event"></i><span class="text">Events</span></a></li>
            <li class="nav-item"><a href="settings.php" class="nav-link"><i class="bi bi-gear"></i><span class="text">Settings</span></a></li>
            <li class="nav-item"><a href="logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i><span class="text">Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div id="content">
        <div class="form-card mt-5">
            <h2 class="mb-3"><i class="bi bi-bell"></i> Request Assistance</h2>
            <p class="text-muted mb-4">Submit your request and our caregivers will respond promptly.</p>

            <!-- Success Alert -->
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="alert alert-success alert-dismissible fade show" id="successAlert" role="alert">
                    <i class="bi bi-check-circle-fill"></i> Your assistance request has been submitted successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Assistance Request Form -->
            <form action="insert_request.php" method="post">
                <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?? '' ?>">

                <div class="mb-3">
                    <label for="request_type" class="form-label">Type of Assistance</label>
                    <select class="form-select" id="request_type" name="request_type" required>
                        <option value="" selected disabled>Choose type...</option>
                        <option value="Medical">Medical</option>
                        <option value="Mobility">Mobility</option>
                        <option value="Companion">Companion / Social</option>
                        <option value="Emergency">Emergency</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description / Details</label>
                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Describe your request..." required></textarea>
                </div>

                <div class="mb-3">
                    <label for="location" class="form-label">Location / Address</label>
                    <input type="text" class="form-control" id="location" name="location" placeholder="Your address or location" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Submit Request</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle
        document.getElementById("toggleSidebar").addEventListener("click", () => {
            const sidebar = document.getElementById("sidebar");
            const content = document.getElementById("content");
            sidebar.classList.toggle("collapsed");
            content.classList.toggle("expanded");
        });

        // Auto-dismiss success alert after 5 seconds
        const successAlert = document.getElementById("successAlert");
        if (successAlert) {
            setTimeout(() => {
                const alert = bootstrap.Alert.getOrCreateInstance(successAlert);
                alert.close();
            }, 5000);
        }
        //alert
        document.addEventListener("DOMContentLoaded", function() {
            const successAlert = document.getElementById("successAlert");
            if (successAlert) {
                setTimeout(() => {
                    const alert = bootstrap.Alert.getOrCreateInstance(successAlert);
                    alert.close();
                }, 5000);
            }
        });
    </script>

</body>

</html>