<?php
header("Content-Type: application/json");
include "db_connect.php";

$identifier = trim($_POST['identifier'] ?? '');

if (empty($identifier)) {
    echo json_encode([
        "status" => "error",
        "title" => "Missing Input",
        "message" => "Please enter your username or phone number."
    ]);
    exit;
}

// Try to find account (Elder, Volunteer, Admin)
$query = "
    SELECT id, username, phone, 'Elder' AS role FROM users WHERE username = ? OR phone = ?
    UNION
    SELECT id, username, phone, 'Volunteer' AS role FROM volunteers WHERE username = ? OR phone = ?
    UNION
    SELECT id, username, phone, 'Admin' AS role FROM admin WHERE username = ?";

$stmt = $pdo->prepare($query);
$stmt->execute([$identifier, $identifier, $identifier, $identifier, $identifier]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode([
        "status" => "error",
        "title" => "Account Not Found",
        "message" => "No user found with that username or phone number."
    ]);
    exit;
}

// Create reset token
$token = bin2hex(random_bytes(16));
$expire = date("Y-m-d H:i:s", time() + 900); // 15 minutes

// Save token in DB
$pdo->prepare("INSERT INTO password_reset (user_id, role, token, expire) VALUES (?, ?, ?, ?)")
    ->execute([$user['id'], $user['role'], $token, $expire]);

$reset_link = "http://yourdomain.com/reset_password.php?token=$token";

echo json_encode([
    "status" => "success",
    "title" => "Reset Link Sent",
    "message" => "A password reset link has been generated.\nLink: $reset_link\n(You can send this via SMS or email)"
]);
exit;
