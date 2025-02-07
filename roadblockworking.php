<?php
require 'header.php';
require 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roadblock Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #map { height: 500px; width: 100%; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2>Mark Roadblocks on Map</h2>
        <div id="map"></div>
    </div>
    <div class="spacer" style="height: 100px;"></div>
    
    <script>
        var map = L.map('map').setView([10.8505, 76.2711], 10); // Default view

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        function loadRoadblocks() {
            $.get('get_roadblocks.php', function(data) {
                data.forEach(block => {
                    L.marker([block.latitude, block.longitude]).addTo(map)
                        .bindPopup('Roadblock reported here');
                });
            }, 'json');
        }
        
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            $.post('save_roadblock.php', { latitude: lat, longitude: lng }, function(response) {
                alert(response);
                location.reload();
            });
        });
        
        loadRoadblocks();
    </script>
</body>
</html>

<?php require 'footer.php'; ?>
