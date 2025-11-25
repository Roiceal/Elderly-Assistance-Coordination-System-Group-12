<?php
session_start();
include 'db_connect.php';

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$role = trim($_POST['role'] ?? '');

if (empty($username) || empty($password) || empty($role)) {
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Failed</title>
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
        font-family: "Poppins", sans-serif;
      background-color: whitesmoke;
      color: #fff;
    }</style>
</head>
<body>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "error",
            title: "Oops...",
            timer: 3000,
            text: "All fields are required!",
        }).then(() => {
            window.history.back();
        });
    </script>
</body>
</html>';
    exit;
}

// Query user/admin/volunteer
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt2 = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
$stmt2->execute([$username]);
$admin = $stmt2->fetch(PDO::FETCH_ASSOC);

$stmt3 = $pdo->prepare("SELECT * FROM volunteers WHERE username = ?");
$stmt3->execute([$username]);
$volunteers = $stmt3->fetch(PDO::FETCH_ASSOC);

// Admin login
if ($admin && $role === 'Admin' && $admin['password'] === $password) {
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];

    echo '<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Success</title>
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
        font-family: "Poppins", sans-serif;
      background-color: whitesmoke;
      color: #fff;
    }</style>
</head>
<body>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "success",
            title: "Welcome Admin!",
            text: "Login successful",
            timer: 3000,
            showConfirmButton: false
        }).then(() => {
            // Redirect after alert
            window.location.href = "admin_page/admin.php";
        });
    </script>
</body>
</html>
';
    exit;

    // Elder login
} else if ($user && $role === 'Elder' && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['phone'] = $user['phone'];
    $_SESSION['username'] = $user['username'];

    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Success</title>
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
        font-family: "Poppins", sans-serif;
      background-color: whitesmoke;
      color: #fff;
    }</style>
</head>
<body>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "success",
            title: "Welcome!",
            text: "Login successful",
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            window.location.href = "dashboard_elders.php";
        });
    </script>
</body>
</html>';
    exit;

    // Volunteer login
} else if ($volunteers && $role === 'Volunteer' && password_verify($password, $volunteers['password'])) {
    $_SESSION['volunteer_id'] = $volunteers['id'];
    $_SESSION['volunteer_phone'] = $volunteers['phone'];
    $_SESSION['volunteer_username'] = $volunteers['username'];

    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Success</title>
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
        font-family: "Poppins", sans-serif;
      background-color: whitesmoke;
      color: #fff;
    }</style>
</head>
<body>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "success",
            title: "Welcome Volunteer!",
            text: "Login successful",
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            window.location.href = "volunteer/volunteer_dashboard.php";
        });
    </script>
</body>
</html>';
    exit;

    // Invalid login
} else {
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Failed</title>
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
        font-family: "Poppins", sans-serif;
      background-color: whitesmoke;
      color: #fff;
    }</style>
</head>
<body>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "error",
            title: "Login Failed",
            text: "Invalid username or password",
        }).then(() => {
            window.history.back();
        });
    </script>
</body>
</html>';
    exit;
}
