<?php
require 'db_connect.php';
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roadblock Marker</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        #map {
            height: 500px;
            width: 100%;
            margin-bottom: 20px;
        }
        .roadblock-table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Mark Roadblocks</h2>
        <div id="map"></div>
        <form id="roadblockForm" class="mt-3">
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <input type="text" id="description" name="description" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Roadblock</button>
        </form>

        <div class="roadblock-table">
            <h3>Existing Roadblocks</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Description</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                    </tr>
                </thead>
                <tbody id="roadblockTableBody">
                </tbody>
            </table>
        </div>
    </div>

    <script>
        var map = L.map('map').setView([20.5937, 78.9629], 5);
        var markers = [];

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Update the loadRoadblocks function to handle errors properly
        function loadRoadblocks() {
            fetch('get_roadblocks.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'error') {
                        throw new Error(data.message);
                    }
                    
                    // Clear existing markers
                    markers.forEach(marker => map.removeLayer(marker));
                    markers = [];
                    
                    // Clear existing table rows
                    $('#roadblockTableBody').empty();
                    
                    // Add new markers and table rows
                    data.forEach(roadblock => {
                        // Add marker to map
                        const marker = L.marker([roadblock.latitude, roadblock.longitude])
                            .addTo(map)
                            .bindPopup(roadblock.description);
                        markers.push(marker);
                        
                        // Add row to table
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${roadblock.id}</td>
                            <td>${roadblock.description}</td>
                            <td>${roadblock.latitude}</td>
                            <td>${roadblock.longitude}</td>
                        `;
                        document.getElementById('roadblockTableBody').appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error loading roadblocks:', error);
                    alert('Error loading roadblocks: ' + error.message);
                });
        }

        // Get user's current location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var userLat = position.coords.latitude;
                var userLng = position.coords.longitude;
                map.setView([userLat, userLng], 13);
                L.marker([userLat, userLng])
                    .addTo(map)
                    .bindPopup("Your Location")
                    .openPopup();
            }, function(error) {
                console.warn("Error getting location: ", error.message);
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }

        // Load initial roadblocks
        loadRoadblocks();

        var marker;
        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(e.latlng).addTo(map);
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });

        $(document).ready(function() {
            $('#roadblockForm').on('submit', function(e) {
                e.preventDefault();
                
                // Validate form data before sending
                const latitude = $('#latitude').val();
                const longitude = $('#longitude').val();
                const description = $('#description').val().trim();

                if (!latitude || !longitude) {
                    alert('Please select a location on the map first');
                    return;
                }

                if (!description) {
                    alert('Please enter a description');
                    return;
                }

                var formData = $(this).serialize();
                
                // Debug log
                console.log('Sending data:', formData);

                $.ajax({
                    url: 'save_roadblock.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            // Clear the form
                            $('#description').val('');
                            if (marker) {
                                map.removeLayer(marker);
                            }
                            // Reload roadblocks
                            loadRoadblocks();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('XHR Status:', status);
                        console.error('Error:', error);
                        console.error('Response:', xhr.responseText);
                        alert('Error saving roadblock. Please check the console for details.');
                    }
                });
            });
        });
    </script>
</body>
</html>

<?php
// Move all API handling code to separate files (get_roadblocks.php and save_roadblock.php)
?>


