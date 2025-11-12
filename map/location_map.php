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

// Fetch all locations
$stmt = $pdo->query("SELECT * FROM user_locations ORDER BY created_at DESC");
$locations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Elder Care</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<style>
body, html { height: 100%; margin: 0; }
.sidebar { width: 250px; height: 100vh; position: fixed; top: 0; left: 0; background-color: #0d6efd; color: white; padding-top: 20px; }
.sidebar h2 { text-align: center; margin-bottom: 30px; }
.sidebar a { display: block; padding: 12px 20px; color: white; text-decoration: none; transition: 0.3s; }
.sidebar a:hover { background-color: white; color: black; }
.main-content { margin-left: 250px; padding: 20px; height: 100%; display: flex; flex-direction: column; }
#map { flex: 1; width: 100%; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.3); margin-top: 15px; }
h1 { text-align: center; margin-bottom: 20px; color: #0d6efd; font-weight: bold; }
tr.clickable:hover { cursor: pointer; background-color: #f0f8ff; }
</style>
</head>
<body>

<div class="sidebar">
  <h2>Menu</h2>
  <a href="#">Dashboard</a>
  <a href="#">Elderly List</a>
  <a href="#">Caregivers</a>
  <a href="#">Reports</a>
  <a href="#">Settings</a>
</div>

<div class="main-content">
  <h1>Elder Care</h1>

  <div id="status" class="mb-2">Click the button to share your location.</div>
  <button id="getLoc" class="btn btn-primary mb-3">Share my location</button>

  <div id="map"></div>

  <div class="container mt-4">
    <h2 class="mb-3">Saved Locations</h2>
    <table class="table table-bordered table-striped" id="locationsTable">
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
          <tr class="clickable" data-id="<?= $loc['id'] ?>" data-lat="<?= htmlspecialchars($loc['latitude']) ?>" data-lng="<?= htmlspecialchars($loc['longitude']) ?>" data-address="<?= htmlspecialchars($loc['address'] ?: '', ENT_QUOTES) ?>">
            <td><?= htmlspecialchars($loc['id']) ?></td>
            <td><?= htmlspecialchars($loc['ip_address']) ?></td>
            <td><?= htmlspecialchars($loc['latitude']) ?></td>
            <td><?= htmlspecialchars($loc['longitude']) ?></td>
            <td><?= htmlspecialchars($loc['accuracy']) ?></td>
            <td class="address-cell"><?= htmlspecialchars($loc['address'] ?: 'Fetching...') ?></td>
            <td><?= htmlspecialchars($loc['created_at']) ?></td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="7" class="text-center">No locations saved yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
// Share location and save
document.getElementById('getLoc').addEventListener('click', () => {
    const status = document.getElementById('status');
    if (!navigator.geolocation) { status.textContent = 'Geolocation not supported.'; return; }
    status.textContent = 'Requesting permission...';
    navigator.geolocation.getCurrentPosition(pos => {
        fetch('save_location.php', {
            method: 'POST',
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify({latitude: pos.coords.latitude, longitude: pos.coords.longitude, accuracy: pos.coords.accuracy})
        }).then(r => r.json()).then(res => {
            status.textContent = res.success ? 'Location saved!' : 'Error: '+res.message;
            if(res.success) location.reload();
        });
    }, err => { status.textContent = 'Error: ' + err.message; }, { enableHighAccuracy: true });
});

// Initialize map
var map = L.map('map').setView([13.7565, 121.0583], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap contributors' }).addTo(map);

// Add markers
var markers = [];
document.querySelectorAll('tr.clickable').forEach(row => {
    const lat = parseFloat(row.dataset.lat);
    const lng = parseFloat(row.dataset.lng);
    const address = row.dataset.address || '';
    const marker = L.marker([lat,lng]).addTo(map).bindPopup(`Address: ${address}<br>Lat: ${lat}, Lng: ${lng}`);
    markers.push({marker,row});
});

// Click table row -> move map
document.querySelectorAll('tr.clickable').forEach(row => {
    row.addEventListener('click', () => {
        const lat = parseFloat(row.dataset.lat);
        const lng = parseFloat(row.dataset.lng);
        map.setView([lat,lng],16);
        const m = markers.find(m => m.row===row);
        if(m) m.marker.openPopup();
    });
});

// AJAX to update missing addresses
document.querySelectorAll('tr.clickable').forEach(row => {
    const addrCell = row.querySelector('.address-cell');
    if(!row.dataset.address) {
        const id = row.dataset.id;
        const lat = row.dataset.lat;
        const lng = row.dataset.lng;
        fetch(`update_address.php?id=${id}&lat=${lat}&lng=${lng}`)
            .then(r => r.json())
            .then(data => {
                if(data.success){
                    addrCell.textContent = data.address;
                    row.dataset.address = data.address;
                } else {
                    addrCell.textContent = 'Address not found';
                }
            });
    }
});
</script>
</body>
</html>
