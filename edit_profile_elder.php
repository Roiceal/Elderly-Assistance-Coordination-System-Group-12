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

    $stmt = $pdo->prepare("UPDATE users SET first_name=?, last_name=?, username=?, phone=?, profile_image=? WHERE id=?");
    $stmt->execute([$first_name, $last_name, $username, $phone, $imageData, $user_id]);

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="edit_profile_elder.css">
</head>

<body class="<?= $high_contrast ? 'high-contrast' : '' ?>" style="font-size: <?= $font_size ?>;">

    <!-- MOBILE HAMBURGER -->
    <button id="mobileMenuBtn" class="btn btn-light d-md-none"
        style="z-index: 3000;margin-left: 10px; margin-top: 10px;;">
        <i class="bi bi-list fs-3"></i>
    </button>

    <!-- SIDEBAR -->
    <div id="sidebar">

        <!-- Desktop collapse button -->
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

    <!-- OVERLAY -->
    <div id="overlay"></div>

    <!-- Content -->
    <div id="content">
        <div class="container">
            <div class="card p-4 shadow-sm mx-auto">
                <h3 class="mb-4 text-center">Edit Your Profile</h3>
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
                        <img src="data:image/jpeg;base64,<?= base64_encode($user['profile_image']) ?>" alt="Profile" class="img-thumbnail mt-2 profile-img" id="profileImagePreview">
                    </div>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="submit" class="btn btn-primary">I-save ang Profile</button>
                        <a href="user_profile.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
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

        // Mobile sidebar toggle
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("overlay");
        const mobileMenuBtn = document.getElementById("mobileMenuBtn");

        mobileMenuBtn.addEventListener("click", () => {
            sidebar.classList.toggle("open");
            overlay.classList.toggle("show");
        });
        overlay.addEventListener("click", () => {
            sidebar.classList.remove("open");
            overlay.classList.remove("show");
        });

        // SweetAlert confirm
        document.getElementById('editProfileForm').addEventListener('submit', function(e) {
            e.preventDefault();
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
                    e.target.submit();
                }
            });
        });
    </script>
    <script src="cookie.js"></script>

</body>

</html>