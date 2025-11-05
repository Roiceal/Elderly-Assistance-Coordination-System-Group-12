<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Location Tracker</title>

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

  <style>
    html, body { height: 100%; margin: 0; }
    #map { width: 100%; height: 100vh; }
  </style>
</head>
<body>

<div id="map"></div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<script>
  // Initialize the map
  var map = L.map('map').setView([0, 0], 2); // Default world view

  // Add OpenStreetMap tiles
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  // Marker for user location
  var userMarker;

  // Check if Geolocation is available
  if (navigator.geolocation) {
    navigator.geolocation.watchPosition(
      function(position) {
        var lat = position.coords.latitude;
        var lng = position.coords.longitude;

        // Update marker
        if (!userMarker) {
          userMarker = L.marker([lat, lng]).addTo(map)
            .bindPopup("You are here").openPopup();
        } else {
          userMarker.setLatLng([lat, lng]);
        }

        // Center map on user
        map.setView([lat, lng], 16);
      },
      function(error) {
        alert("Error getting location: " + error.message);
      },
      {
        enableHighAccuracy: true,
        maximumAge: 0,
        timeout: 5000
      }
    );
  } else {
    alert("Geolocation is not supported by your browser.");
  }
</script>

</body>
</html>
