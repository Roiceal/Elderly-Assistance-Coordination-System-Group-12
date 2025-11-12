<?php
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
    die("Database connection failed: " . $e->getMessage());
}

// Function to fetch address from latitude/longitude (server-side)
function getAddress($lat, $lng) {
    $url = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=$lat&lon=$lng&zoom=18&addressdetails=1";
    $opts = [
        "http" => [
            "header" => "User-Agent: MyLocationApp/1.0\r\n"
        ]
    ];
    $context = stream_context_create($opts);
    $json = @file_get_contents($url, false, $context);
    if (!$json) return "Address not found";
    $data = json_decode($json, true);
    return $data['display_name'] ?? "Address not found";
}

// Fetch all locations
$stmt = $pdo->query("SELECT * FROM user_locations ORDER BY created_at DESC");
$locations = $stmt->fetchAll();

// Add server-side addresses if not already in DB
foreach ($locations as &$loc) {
    if (empty($loc['address'])) {
        $loc['address'] = getAddress($loc['latitude'], $loc['longitude']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Locations Map & Table</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<style>
  #map { height: 500px; margin-bottom: 20px; }
  tr.clickable:hover { cursor: pointer; background-color: #f0f8ff; }
</style>
</head>
<body>
<div class="container mt-4">
  <h2 class="mb-3">Saved Locations</h2>

  <div id="map"></div>

  <table class="table table-bordered table-striped">
    <thead class="table-primary">
      <tr>
        <th>#</th>
        <th>IP Address</th>
        <th>Latitude</th>
        <th>Longitude</th>
        <th>Accuracy (m)</th>
        <th>Address</th>
        <th>Timestamp</th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($locations)): ?>
        <?php foreach($locations as $loc): ?>
        <tr class="clickable" data-lat="<?= $loc['latitude'] ?>" data-lng="<?= $loc['longitude'] ?>" data-address="<?= htmlspecialchars($loc['address'], ENT_QUOTES) ?>">
          <td><?= htmlspecialchars($loc['id']) ?></td>
          <td><?= htmlspecialchars($loc['ip_address']) ?></td>
          <td><?= htmlspecialchars($loc['latitude']) ?></td>
          <td><?= htmlspecialchars($loc['longitude']) ?></td>
          <td><?= htmlspecialchars($loc['accuracy']) ?></td>
          <td><?= htmlspecialchars($loc['address']) ?></td>
          <td><?= htmlspecialchars($loc['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="7" class="text-center">No locations saved yet.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
// Initialize map
var map = L.map('map').setView([13.7565, 121.0583], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Add markers for all locations
var markers = [];
document.querySelectorAll('tr.clickable').forEach(row => {
    const lat = parseFloat(row.dataset.lat);
    const lng = parseFloat(row.dataset.lng);
    const address = row.dataset.address;

    const marker = L.marker([lat, lng]).addTo(map)
        .bindPopup(`Address: ${address}<br>Lat: ${lat}, Lng: ${lng}`);
    markers.push({marker, row});
});

// Click table row -> move map to location
document.querySelectorAll('tr.clickable').forEach(row => {
    row.addEventListener('click', () => {
        const lat = parseFloat(row.dataset.lat);
        const lng = parseFloat(row.dataset.lng);
        map.setView([lat, lng], 16); // Zoom in
        const m = markers.find(m => m.row === row);
        if (m) m.marker.openPopup();
    });
});
</script>
</body>
</html>
