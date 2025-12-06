<?php
session_start();
include __DIR__ . '/../db_connect.php';

// Handle form submission
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if username already exists
    $check = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $check->execute([$username]);

    if ($check->rowCount() > 0) {
        $message = "<div class='alert alert-danger'>Username already exists!</div>";
    } else {
        // Insert new admin
        $stmt = $pdo->prepare("
            INSERT INTO admin (username, password, fullname, phone)
            VALUES (?, ?, ?, ?)
        ");

        if ($stmt->execute([$username, $password, $fullname, $phone])) {
            $message = "<div class='alert alert-success'>New admin added successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error adding admin.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">
    <h2 class="mb-4">Add New Admin</h2>

    <?= $message ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="fullname" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone Number (optional)</label>
                    <input type="text" name="phone" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                </div>

                <button type="submit" class="btn btn-primary">Add Admin</button>
                <a href="admin_list.php" class="btn btn-secondary">Back</a>

            </form>

        </div>
    </div>
</div>

</body>
</html>
