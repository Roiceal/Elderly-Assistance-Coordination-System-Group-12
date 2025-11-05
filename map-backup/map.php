<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Geoapify Route Demo (Manual Start)</title>

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css">
  <!-- Marker Cluster CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css"/>
  
  <style>
    #map {
      height: 500px;
      width: 100%;
      margin-top: 10px;
    }
    label { display:block; margin-top:10px; }
  </style>
</head>
<body>
  <h2>Geoapify Route Example (Manual Start Location)</h2>
  
  <label>Start Coordinates (lat,lng):</label>
  <input type="text" id="start" placeholder="14.6091,121.0223">

  <label>Destination(s) (lat,lng;...):</label>
  <input type="text" id="end" placeholder="14.6760,121.0437;14.5995,120.9842">

  <button id="getRoute">Show Route</button>

  <div id="map"></div>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
  <!-- Marker Cluster JS -->
  <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>

  <script src="route.js"></script>
</body>
</html>
