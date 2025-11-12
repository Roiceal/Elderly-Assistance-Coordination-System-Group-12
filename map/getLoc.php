<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Save User Location to DB</title>
</head>
<body>
  <h1>Share your location</h1>
  <div id="status">Click the button to share your location.</div>
  <button id="getLoc">Share my location</button>

  <script>
  const btn = document.getElementById('getLoc');
  const status = document.getElementById('status');

  btn.addEventListener('click', () => {
    if (!navigator.geolocation) {
      status.textContent = 'Geolocation not supported.';
      return;
    }

    status.textContent = 'Requesting permission...';
    navigator.geolocation.getCurrentPosition(success, error, { enableHighAccuracy: true });

    function success(position) {
      const data = {
        latitude: position.coords.latitude,
        longitude: position.coords.longitude,
        accuracy: position.coords.accuracy
      };

      fetch('save_location.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
      })
      .then(res => res.json())
      .then(res => {
        if(res.success){
          status.textContent = 'Location saved successfully!';
        } else {
          status.textContent = 'Error: ' + res.message;
        }
      })
      .catch(err => status.textContent = 'Fetch error: ' + err);
    }

    function error(err) {
      status.textContent = 'Error getting location: ' + err.message;
    }
  });
  </script>
</body>
</html>
