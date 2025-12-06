<?php
session_start();

include 'db_connect.php';

if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $activity = "Admin logged out";

    $stmt = $pdo->prepare("INSERT INTO activity_logs (admin_id, activity, ip_address, user_agent) 
                       VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $admin_id,
        $activity,
        $_SERVER['REMOTE_ADDR'],
        $_SERVER['HTTP_USER_AGENT']
    ]);
}

session_unset();
// Destroy the session
session_destroy();
// Redirect to the login page (or home page)
header("Location:login.php");
exit;
