<?php
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$db   = 'location';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo json_encode(['success'=>false,'message'=>'Database connection failed']);
    exit;
}

// Get parameters
$id = $_GET['id'] ?? null;
$lat = $_GET['lat'] ?? null;
$lng = $_GET['lng'] ?? null;

if(!$id || !$lat || !$lng){
    echo json_encode(['success'=>false,'message'=>'Missing parameters']);
    exit;
}

// Function to get address from latitude/longitude
function getAddress($lat, $lng){
    $url = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=$lat&lon=$lng&zoom=18&addressdetails=1";
    $opts = ["http"=>["header"=>"User-Agent: MyElderCareApp/1.0\r\n"]];
    $context = stream_context_create($opts);
    $json = @file_get_contents($url,false,$context);
    if(!$json) return null;
    $data = json_decode($json,true);
    return $data['display_name'] ?? null;
}

// Fetch address
$address = getAddress($lat,$lng);

if($address){
    // Update DB
    $stmt = $pdo->prepare("UPDATE user_locations SET address=:address WHERE id=:id");
    $stmt->execute(['address'=>$address,'id'=>$id]);

    echo json_encode(['success'=>true,'address'=>$address]);
} else {
    echo json_encode(['success'=>false,'address'=>'Address not found']);
}
?>