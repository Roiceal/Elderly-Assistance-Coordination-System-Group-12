<?php
session_start();
if (!isset($_SESSION['volunteer_id'])) {
    header("Location: volunteer_login.php");
    exit();
}

include __DIR__ . '/../db_connect.php';

$volunteer_id = $_SESSION['volunteer_id'];

// Fetch volunteer info
$stmt = $pdo->prepare("SELECT * FROM volunteers WHERE id = ?");
$stmt->execute([$volunteer_id]);
$volunteer = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $phone = trim($_POST['phone']);
    $age = intval($_POST['age']);
    $gender = trim($_POST['gender']);

    $imageData = null;
    if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
    }

    if ($imageData) {
        $stmt = $pdo->prepare("UPDATE volunteers SET fname=?, lname=?, phone=?, age=?, gender=?, profile_image=? WHERE id=?");
        $stmt->execute([$fname, $lname, $phone, $age, $gender, $imageData, $volunteer_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE volunteers SET fname=?, lname=?, phone=?, age=?, gender=? WHERE id=?");
        $stmt->execute([$fname, $lname, $phone, $age, $gender, $volunteer_id]);
    }

    echo "<script>alert('Profile updated successfully!'); window.location.href='volunteer_profile.php';</script>";
    exit();
}
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f2f5;
        }

        #sidebar {
            width: 250px;
            position: fixed;
            height: 100vh;
            background: #343a40;
            color: #fff;
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
            gap: 10px;
            border-radius: 6px;
            transition: 0.2s;
        }

        #sidebar .nav-link:hover {
            background: #495057;
            padding-left: 25px;
        }

        #sidebar.collapsed .text {
            display: none;
        }

        #content {
            margin-left: 250px;
            transition: 0.3s;
            padding: 20px;
        }

        #content.expanded {
            margin-left: 70px;
        }

        .profile-card {
            max-width: 700px;
            margin: 50px auto;
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #4a90e2;
            margin-bottom: 20px;
        }

        .btn-primary {
            background: #4a90e2;
            border: none;
        }

        .btn-primary:hover {
            background: #357ABD;
        }

        @media(max-width:768px) {

            #sidebar,
            #content {
                margin-left: 0;
                width: 100% !important;
            }
        }
    </style>

</head>

<body>

    <!-- Sidebar -->

    <div id="sidebar">
        <button class="btn btn-sm btn-outline-light mb-3 ms-3" id="toggleSidebar"><i class="bi bi-list"></i></button>
        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a href="volunteer_dashboard.php" class="nav-link"><i class="bi bi-house"></i><span class="text">Dashboard</span></a></li>
            <li class="nav-item"><a href="volunteer_profile.php" class="nav-link active"><i class="bi bi-person"></i><span class="text">Profile</span></a></li>
            <li class="nav-item"><a href="../logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i><span class="text">Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->

    <div id="content">
        <h2 class="mb-3"></h2>
        <div class="profile-card text-center">
            <?php if ($volunteer['profile_image']): ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($volunteer['profile_image']) ?>" alt="Profile Image" class="profile-image">
            <?php else: ?>
                <img src="https://via.placeholder.com/150" alt="Profile Image" class="profile-image">
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="mt-3 text-start">
                <div class="row mb-3">
                    <div class="col-md-6"><input type="text" name="fname" class="form-control" placeholder="First Name" value="<?= htmlspecialchars($volunteer['fname']) ?>" required></div>
                    <div class="col-md-6"><input type="text" name="lname" class="form-control" placeholder="Last Name" value="<?= htmlspecialchars($volunteer['lname']) ?>" required></div>
                </div>
                <div class="mb-3"><input type="text" name="phone" class="form-control" placeholder="Phone" value="<?= htmlspecialchars($volunteer['phone']) ?>" required></div>
                <div class="row mb-3">
                    <div class="col-md-6"><input type="number" name="age" class="form-control" placeholder="Age" value="<?= htmlspecialchars($volunteer['age']) ?>" required></div>
                    <div class="col-md-6">
                        <select name="gender" class="form-select" required>
                            <option value="Male" <?= $volunteer['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $volunteer['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                            <option value="Other" <?= $volunteer['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <input type="file" name="image" class="form-control">
                    <small class="text-muted">Leave blank to keep current image.</small>
                </div>
                <button type="submit" class="btn btn-primary w-100">Update Profile</button>
            </form>
        </div>
        ```

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('content').classList.toggle('expanded');
        });
    </script>

</body>

</html>