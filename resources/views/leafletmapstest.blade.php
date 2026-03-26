<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>

</head>
<body>

<h1>My Second not Google Map</h1>

<div id="map" style="width:100%;height:400px;"></div>
<h3>Lat: <span id="latDisplay">0</span></h3>
<h3>Lng: <span id="lngDisplay">0</span></h3>

<script>
    var map = L.map('map').setView([51.505, -0.09], 2);
    var marker;

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    map.on('click', onMapClick);

    function onMapClick(e) {
        drawMarker(e.latlng.lat, e.latlng.lng);
        displayCoordinates(e.latlng);
    }

    function drawMarker(lat, lng, changedMarker = false, clearLast = true){
        var myIcon = L.icon({
            iconUrl: 'greenMarker.png',
            iconSize: [50, 50],
            iconAnchor: [25, 50]
        });

        if (marker && clearLast) marker.remove();
        if (!changedMarker)
            marker = L.marker([lat, lng]).addTo(map);
        else
            marker = L.marker([lat, lng], {icon: myIcon}).addTo(map);
    }

    function drawLine(startLatLng, finishLatLng, focus = false){
        var latlngs = [
            startLatLng,
            finishLatLng
        ];

        var polyline = L.polyline(latlngs, {color: 'red'}).addTo(map);

        // zoom the map to the polyline
        if (focus) map.fitBounds(polyline.getBounds());
    }

    function displayCoordinates(latLng){
        latDisplay.innerHTML = latLng.lat;
        lngDisplay.innerHTML = latLng.lng;
    }
</script>

</body>
</html>