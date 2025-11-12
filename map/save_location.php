<?php
header('Content-Type: application/json; charset=utf-8');

// Database connection
$host = 'localhost';
$db   = 'location';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo json_encode(['success'=>false, 'message'=>'DB connection failed: '.$e->getMessage()]);
    exit;
}

// Read JSON POST body
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if(!$data || !isset($data['latitude'],$data['longitude'])){
    echo json_encode(['success'=>false,'message'=>'Missing coordinates']);
    exit;
}

$lat = $data['latitude'];
$lon = $data['longitude'];
$accuracy = $data['accuracy'] ?? null;
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

// Insert into DB securely
$stmt = $pdo->prepare("INSERT INTO user_locations (ip_address, latitude, longitude, accuracy) VALUES (?, ?, ?, ?)");
$inserted = $stmt->execute([$ip, $lat, $lon, $accuracy]);

if($inserted){
    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['success'=>false,'message'=>'Failed to save location']);
}


?>