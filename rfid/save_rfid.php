<?php
session_start();
include __DIR__ . '/../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user_id = $_POST['user_id'] ?? null;
    $card_id = $_POST['card_id'] ?? null;

    if (!$user_id || !$card_id) {
        die("Missing required fields.");
    }

    // Fetch user info
    $stmt = $pdo->prepare("SELECT first_name, last_name, address, age, profile_image FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("User not found");
    }

    $name = $user['first_name'] . " " . $user['last_name'];
    $address = $user['address'];
    $age = $user['age'];
    $imageData = $user['profile_image']; // LONGBLOB

    // Save image to folder
    $imagesDir = __DIR__ . '/images/'; // <-- fixed path
    if (!is_dir($imagesDir)) {
        mkdir($imagesDir, 0755, true);
    }

    $imageName = time() . '_' . $user_id . '.jpg'; // unique name
    $imagePath = $imagesDir . $imageName;

    if (file_put_contents($imagePath, $imageData) === false) {
        die("Failed to save image to folder.");
    }

    // Store relative path in DB
    $relativePath = "images/" . $imageName;

    // Insert into user_rfid
    $stmt = $pdo->prepare("
        INSERT INTO user_rfid (user_id, card_id, name, address, age, image)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
    $stmt->bindParam(2, $card_id, PDO::PARAM_INT);
    $stmt->bindParam(3, $name, PDO::PARAM_STR);
    $stmt->bindParam(4, $address, PDO::PARAM_STR);
    $stmt->bindParam(5, $age, PDO::PARAM_INT);
    $stmt->bindParam(6, $relativePath, PDO::PARAM_STR);

    $success = $stmt->execute();

    if ($success) {
        echo "<script>
                alert('RFID and image successfully registered!');
                window.location='register_rfid.php';
              </script>";
    } else {
        echo "Failed to save RFID.";
    }
}
