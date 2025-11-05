<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Elder Care</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

  <style>
    body, html {
      height: 100%;
      margin: 0;
    }
    .sidebar {
      width: 250px;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      background-color: #0d6efd;
      color: white;
      padding-top: 20px;
    }
    .sidebar h2 {
      text-align: center;
      margin-bottom: 30px;
    }
    .sidebar a {
      display: block;
      padding: 12px 20px;
      color: white;
      text-decoration: none;
      transition: 0.3s;
    }
    .sidebar a:hover {
      background-color: white;
      color: black;
    }
    .main-content {
      margin-left: 250px;
      padding: 20px;
      height: 100vh;
      display: flex;
      flex-direction: column;
    }
    #map {
      flex: 1;
      width: 100%;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.3);
      margin-top: 15px;
    }
    h1 {
      text-align: center;
      margin-bottom: 20px;
      color: #0d6efd;
      font-weight: bold;
    }
    .route-form {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      margin-bottom: 10px;
      display: flex;
      gap: 10px;
    }
    .route-form input {
      flex: 1;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Menu</h2>
    <a href="#">Dashboard</a>
    <a href="#">Elderly List</a>
    <a href="#">Caregivers</a>
    <a href="#">Reports</a>
    <a href="#">Settings</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <h1>Elder Care</h1>

    <!-- Route Input Form -->
    <div class="route-form container">
      <input type="text" class="form-control" id="startCoords" placeholder="Start: lat,lng">
      <input type="text" class="form-control" id="endCoords" placeholder="End: lat,lng">
      <button id="drawRouteBtn" class="btn btn-primary">Draw Route</button>
    </div>

    <!-- Map -->
    <div id="map"></div>
  </div>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>

  <script>
    var map = L.map('map').setView([13.7565, 121.0583], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var routingControl;

    // Function to get address from lat,lng using Nominatim
    async function getAddress(lat, lng) {
      try {
        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`);
        const data = await response.json();
        return data.display_name || "Address not found";
      } catch (err) {
        return "Address lookup failed";
      }
    }

    document.getElementById('drawRouteBtn').addEventListener('click', async function() {
      var startInput = document.getElementById('startCoords').value.split(',');
      var endInput = document.getElementById('endCoords').value.split(',');

      if (startInput.length !== 2 || endInput.length !== 2) {
        alert("Please enter coordinates in the format: lat,lng");
        return;
      }

      var startLat = parseFloat(startInput[0].trim());
      var startLng = parseFloat(startInput[1].trim());
      var endLat = parseFloat(endInput[0].trim());
      var endLng = parseFloat(endInput[1].trim());

      if (isNaN(startLat) || isNaN(startLng) || isNaN(endLat) || isNaN(endLng)) {
        alert("Please enter valid numbers for coordinates.");
        return;
      }

      var start = L.latLng(startLat, startLng);
      var end = L.latLng(endLat, endLng);

      // Remove previous route if exists
      if (routingControl) {
        map.removeControl(routingControl);
      }

      // Get addresses
      const startAddress = await getAddress(startLat, startLng);
      const endAddress = await getAddress(endLat, endLng);

      // Draw route with address popups
      routingControl = L.Routing.control({
        waypoints: [start, end],
        routeWhileDragging: false,
        showAlternatives: false,
        createMarker: function(i, wp, nWps) {
          return L.marker(wp.latLng)
            .bindPopup(i === 0 ? `Start:<br>${startAddress}` : `End:<br>${endAddress}`)
            .openPopup();
        }
      }).addTo(map);
    });
  </script>
</body>
</html>
