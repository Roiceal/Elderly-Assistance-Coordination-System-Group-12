<?php
session_start();
include __DIR__ . '/../db_connect.php';

$volunteer_id = $_SESSION['volunteer_id'];

if (!isset($_FILES['image'])) {
    die("No image uploaded.");
}

// Read the raw image data
$imageData = file_get_contents($_FILES['image']['tmp_name']);

// Insert/Update the BLOB in your table
$stmt = $pdo->prepare("UPDATE volunteers SET profile_image = ? WHERE id = ?");
$stmt->bindParam(1, $imageData, PDO::PARAM_LOB);
$stmt->bindParam(2, $volunteer_id);
$stmt->execute();

echo "Image saved successfully!";
?>
