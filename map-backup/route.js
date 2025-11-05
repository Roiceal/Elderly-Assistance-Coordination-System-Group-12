// const tileApiKey = "b7e5e6167d1643dfaf4f31e8ebd72599";

// // Initialize map
// const map = L.map('map', {
//     scrollWheelZoom: true,
//     doubleClickZoom: true,
//     touchZoom: true,
//     boxZoom: true,
//     keyboard: true
// }).setView([0, 0], 2);

// // High-detail tile layer (street-level)
// L.tileLayer(`https://maps.geoapify.com/v1/tile/osm-carto/{z}/{x}/{y}.png?apiKey=${tileApiKey}`, {
//     attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, © Geoapify',
//     maxZoom: 20
// }).addTo(map);

// let startCoords = null;
// let routeLayer = null;
// let markerCluster = L.markerClusterGroup(); // For multiple markers
// map.addLayer(markerCluster);

// // Get user's current location
// navigator.geolocation.getCurrentPosition(
//     pos => {
//         startCoords = [pos.coords.latitude, pos.coords.longitude];
//         map.setView(startCoords, 15);
//         const startMarker = L.marker(startCoords).bindPopup("📍 You are here").openPopup();
//         markerCluster.addLayer(startMarker);
//     },
//     err => {
//         alert("Unable to get your location. Please enable GPS.");
//         console.error(err);
//     }
// );

// // Click map to get precise coordinates (no marker)
// map.on('click', function(e) {
//     const { lat, lng } = e.latlng;
//     alert(`Clicked Coordinates:\nLatitude: ${lat.toFixed(6)}, Longitude: ${lng.toFixed(6)}`);
// });

// document.getElementById('getRoute').addEventListener('click', async () => {
//     const endInput = document.getElementById('end').value.trim();
//     if (!endInput) return alert("Please enter destination coordinates!");
//     if (!startCoords) return alert("Waiting for your location...");

//     // Clear previous route
//     if (routeLayer) map.removeLayer(routeLayer);

//     // Clear markers except start
//     markerCluster.clearLayers();
//     const startMarker = L.marker(startCoords).bindPopup("📍 You are here").openPopup();
//     markerCluster.addLayer(startMarker);

//     // Parse multiple destinations
//     const destinations = endInput.split(';').map(d => d.trim());
//     const allCoords = [startCoords];

//     destinations.forEach(dest => {
//         const [lat, lng] = dest.split(',').map(Number);
//         if (!isNaN(lat) && !isNaN(lng)) {
//             const marker = L.marker([lat, lng]).bindPopup(`📍 Destination: ${lat},${lng}`);
//             markerCluster.addLayer(marker);
//             allCoords.push([lat, lng]);
//         }
//     });

//     // Draw route to first destination
//     if (allCoords.length > 1) {
//         const firstDest = allCoords[1];
//         try {
//             const response = await fetch(`route.php?start=${startCoords[0]},${startCoords[1]}&end=${firstDest[0]},${firstDest[1]}`);
//             if (!response.ok) throw new Error(`Server error: ${response.status}`);
//             const data = await response.json();

//             if (data.features && data.features.length > 0) {
//                 const coords = data.features[0].geometry.coordinates[0].map(c => [c[1], c[0]]);
//                 routeLayer = L.polyline(coords, { color: 'blue', weight: 5 }).addTo(map);
//                 map.fitBounds(routeLayer.getBounds());
//             } else {
//                 alert("No route found!");
//             }
//         } catch (err) {
//             console.error(err);
//             alert("Failed to fetch route. Check console for details.");
//         }
//     }
// });


const tileApiKey = "b7e5e6167d1643dfaf4f31e8ebd72599";

// Initialize map
const map = L.map('map', {
    scrollWheelZoom: true,
    doubleClickZoom: true,
    touchZoom: true,
    boxZoom: true,
    keyboard: true
}).setView([0, 0], 2);

// High-detail tile layer (street-level)
L.tileLayer(`https://maps.geoapify.com/v1/tile/osm-carto/{z}/{x}/{y}.png?apiKey=${tileApiKey}`, {
    attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, © Geoapify',
    maxZoom: 20
}).addTo(map);

let routeLayer = null;
let markerCluster = L.markerClusterGroup();
map.addLayer(markerCluster);

// Click map to get precise coordinates (no marker)
map.on('click', function(e) {
    const { lat, lng } = e.latlng;
    alert(`Clicked Coordinates:\nLatitude: ${lat.toFixed(6)}, Longitude: ${lng.toFixed(6)}`);
});

document.getElementById('getRoute').addEventListener('click', async () => {
    const startInput = document.getElementById('start').value.trim();
    const endInput = document.getElementById('end').value.trim();

    if (!startInput || !endInput) return alert("Please enter both start and destination coordinates!");

    const [startLat, startLng] = startInput.split(',').map(Number);
    if (isNaN(startLat) || isNaN(startLng)) return alert("Invalid start coordinates!");

    // Clear previous route
    if (routeLayer) map.removeLayer(routeLayer);
    markerCluster.clearLayers();

    // Add start marker
    const startMarker = L.marker([startLat, startLng]).bindPopup("📍 Start").openPopup();
    markerCluster.addLayer(startMarker);

    // Parse multiple destinations
    const destinations = endInput.split(';').map(d => d.trim());
    const allCoords = [[startLat, startLng]];

    destinations.forEach(dest => {
        const [lat, lng] = dest.split(',').map(Number);
        if (!isNaN(lat) && !isNaN(lng)) {
            const marker = L.marker([lat, lng]).bindPopup(`📍 Destination: ${lat},${lng}`);
            markerCluster.addLayer(marker);
            allCoords.push([lat, lng]);
        }
    });

    // Draw route to first destination
    if (allCoords.length > 1) {
        const firstDest = allCoords[1];
        try {
            const response = await fetch(`route.php?start=${startLat},${startLng}&end=${firstDest[0]},${firstDest[1]}`);
            if (!response.ok) throw new Error(`Server error: ${response.status}`);
            const data = await response.json();

            if (data.features && data.features.length > 0) {
                const coords = data.features[0].geometry.coordinates[0].map(c => [c[1], c[0]]);
                routeLayer = L.polyline(coords, { color: 'blue', weight: 5 }).addTo(map);
                map.fitBounds(routeLayer.getBounds());
            } else {
                alert("No route found!");
            }
        } catch (err) {
            console.error(err);
            alert("Failed to fetch route. Check console for details.");
        }
    }
});
