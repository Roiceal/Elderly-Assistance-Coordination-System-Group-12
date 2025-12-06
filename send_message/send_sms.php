<?php
// send_sms.php
session_start();
$config = require __DIR__ . '/config.php';
include __DIR__ . '/../db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'] ?? '';
    $message = trim($_POST['message'] ?? '');

    if (empty($category) || empty($message)) {
        die("Category and message are required.");
    }

    switch ($category) {
        case 'elders':
            $stmt = $pdo->query("SELECT phone FROM users WHERE phone IS NOT NULL");
            break;

        case 'volunteers':
            $stmt = $pdo->query("SELECT phone FROM volunteers WHERE phone IS NOT NULL");
            break;

        case 'admin':
            $stmt = $pdo->query("SELECT phone FROM admin WHERE phone IS NOT NULL");
            break;

        case 'all':
            $phones = [];

            $stmt1 = $pdo->query("SELECT phone FROM users WHERE phone IS NOT NULL");
            $stmt2 = $pdo->query("SELECT phone FROM volunteers WHERE phone IS NOT NULL");
            $stmt3 = $pdo->query("SELECT phone FROM admin WHERE phone IS NOT NULL");

            $phones1 = $stmt1->fetchAll(PDO::FETCH_COLUMN);
            $phones2 = $stmt2->fetchAll(PDO::FETCH_COLUMN);
            $phones3 = $stmt3->fetchAll(PDO::FETCH_COLUMN);

            $phones = array_unique(array_merge($phones1, $phones2, $phones3));
            break;

        default:
            die("Invalid category selected.");
    }

    if ($category !== 'all') {
        $phones = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }


    if (!$phones) {
        die("No recipients found in this category.");
    }

    // Loop through each phone and send SMS via iProg API
    foreach ($phones as $phoneNumber) {
        $phoneNumber = trim($phoneNumber);
        if ($phoneNumber === '') continue;

        $payload = [
            'api_token' => $config['iprog_api_token'],
            'phone_number' => $phoneNumber,
            'message' => $message
        ];

        $ch = curl_init("https://sms.iprogtech.com/api/v1/sms_messages");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo "Error sending to {$phoneNumber}: " . curl_error($ch) . "<br>";
        } else {
            // echo "Message sent to {$phoneNumber}: " . htmlspecialchars($response) . "<br>";
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
                                title: "Message Sent Successfully",
                                text: "The message has been sent to all recipients.",
                                timer: 3000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = "../admin_page/make_announcement.php";
                            });
                        </script>
                    </body>
                    </html>';
        }

        curl_close($ch);
    }

    echo "<br><a href='admin_sms.php' class='btn btn-primary'>Back to SMS Page</a>";
    exit();
}
