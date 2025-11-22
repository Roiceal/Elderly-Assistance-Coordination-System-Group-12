<?php
// header('Content-Type: application/json; charset=utf-8');
// session_start();

// // Database connection
// $host = 'localhost';
// $db   = 'location';
// $user = 'root';
// $pass = '';
// $charset = 'utf8mb4';

// $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
// $options = [
//     PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
//     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
// ];

// try {
//     $pdo = new PDO($dsn, $user, $pass, $options);
// } catch (PDOException $e) {
//     echo json_encode(['success'=>false, 'message'=>'DB connection failed: '.$e->getMessage()]);
//     exit;
// }

// // Read JSON POST body
// $raw = file_get_contents('php://input');
// $data = json_decode($raw, true);
// if(!$data || !isset($data['latitude'],$data['longitude'])){
//     echo json_encode(['success'=>false,'message'=>'Missing coordinates']);
//     exit;
// }

// $lat = $data['latitude'];
// $lon = $data['longitude'];
// $accuracy = $data['accuracy'] ?? null;
// $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
// $username = $_SESSION['username'] ?? 'anonymous';

// // Insert into DB securely
// $stmt = $pdo->prepare("
//     INSERT INTO user_locations (ip_address, username, latitude, longitude, accuracy)
//     VALUES (?, ?, ?, ?, ?)
// ");
// $inserted = $stmt->execute([$ip, $username, $lat, $lon, $accuracy]);

// if($inserted){
//     echo json_encode(['success'=>true]);
// } else {
//     echo json_encode(['success'=>false,'message'=>'Failed to save location']);
// }





header('Content-Type: application/json; charset=utf-8');
session_start();

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

if (!$data || !isset($data['latitude'], $data['longitude'])) {
    echo json_encode(['success'=>false,'message'=>'Missing coordinates']);
    exit;
}

$lat      = $data['latitude'];
$lon      = $data['longitude'];
$accuracy = $data['accuracy'] ?? null;
$ip       = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$username = $_SESSION['username'] ?? 'anonymous';

/* ---------------------------------------------------------
   (1) FETCH ADDRESS USING NOMINATIM
----------------------------------------------------------*/
function getAddress($lat, $lng){
    $url = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=$lat&lon=$lng&zoom=18&addressdetails=1";
    $opts = ["http"=>["header"=>"User-Agent: ElderCareApp/1.0\r\n"]];
    $context = stream_context_create($opts);
    $json = @file_get_contents($url, false, $context);
    if (!$json) return null;

    $data = json_decode($json, true);
    return $data['display_name'] ?? null;
}

$address = getAddress($lat, $lon);

/* ---------------------------------------------------------
   (2) CHECK IF USERNAME ALREADY EXISTS
----------------------------------------------------------*/
$check = $pdo->prepare("SELECT id FROM user_locations WHERE username = ? LIMIT 1");
$check->execute([$username]);
$existing = $check->fetch();

/* ---------------------------------------------------------
   (3) UPDATE IF EXISTS, ELSE INSERT NEW
----------------------------------------------------------*/
if ($existing) {

    // UPDATE existing location
    $stmt = $pdo->prepare("
        UPDATE user_locations 
        SET latitude = ?, 
            longitude = ?, 
            accuracy = ?, 
            address = ?, 
            ip_address = ?, 
            created_at = NOW()
        WHERE id = ?
    ");

    $ok = $stmt->execute([
        $lat, 
        $lon, 
        $accuracy, 
        $address, 
        $ip,
        $existing['id']
    ]);

    echo json_encode([
        'success' => $ok,
        'message' => 'Location updated',
        'action'  => 'updated',
        'address' => $address
    ]);
}
else {
    // INSERT new location
    $stmt = $pdo->prepare("
        INSERT INTO user_locations (ip_address, username, latitude, longitude, accuracy, address, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");

    $ok = $stmt->execute([
        $ip, 
        $username,
        $lat, 
        $lon, 
        $accuracy, 
        $address
    ]);

    echo json_encode([
        'success' => $ok,
        'message' => 'New location saved',
        'action'  => 'inserted',
        'address' => $address
    ]);
}




?>