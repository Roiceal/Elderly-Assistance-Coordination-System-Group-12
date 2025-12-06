<?php
session_start();
include 'db_connect.php';

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header("Location: login.php");
    exit();
}

// Fetch current user info
$stmt = $pdo->prepare("SELECT first_name, last_name, username, phone, profile_image FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $username = $_POST['username'] ?? '';
    $phone = $_POST['phone'] ?? '';

    $imageData = null;
    if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
    }

    // Update user info
    $stmt = $pdo->prepare("UPDATE users SET first_name=?, last_name=?, username=?, phone=?, profile_image=? WHERE id=?");
    $stmt->execute([$first_name, $last_name, $username, $phone, $imageData, $user_id]);

    // Redirect with success message
    header("Location: user_profile.php?success=Profile updated successfully");
    exit();
}
?>
<!DOCTYPE html>
<html lang="tl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I-edit ang Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Add SweetAlert2 CDN in the <head> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
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

        .profile-img {
            width: 220px;
            /* bigger width */
            height: 220px;
            /* bigger height */
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #c5e1dc;
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
    <div id="content" class="container">
        <div class="card p-4 shadow-sm">
            <h3 class="mb-4">Edit Your Profile</h3>
            <form method="POST" id="editProfileForm" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Profile Image</label>
                    <input type="file" name="image" class="form-control" id="profileImageInput">
                    <img src="data:image/jpeg;base64,<?= base64_encode($user['profile_image']) ?>"
                        alt="Profile"
                        class="img-thumbnail mt-2 profile-img"
                        id="profileImagePreview">

                </div>
                <button type="submit" class="btn btn-primary">I-save ang Profile</button>
                <a href="user_profile.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <script>
        // Preview selected image
        const profileInput = document.getElementById('profileImageInput');
        const profilePreview = document.getElementById('profileImagePreview');

        profileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profilePreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        document.getElementById("toggleSidebar").addEventListener("click", () => {
            const sidebar = document.getElementById("sidebar");
            const content = document.getElementById("content");
            sidebar.classList.toggle("collapsed");
            content.classList.toggle("expanded");
        });

        document.getElementById('editProfileForm').addEventListener('submit', function(e) {
            e.preventDefault(); // prevent form submission

            Swal.fire({
                title: 'Sigurado ka ba?',
                text: "Gusto mo bang i-update ang iyong profile?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oo, i-update!',
                cancelButtonText: 'Hindi'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, submit the form
                    e.target.submit();
                }
            });
        });
    </script>

</body>

</html>