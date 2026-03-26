<!DOCTYPE html>
<html>
<body>

<h1>My First Google Map</h1>

<div id="googleMap" style="width:100%;height:400px;"></div>
<h3>Lat: <span id="latDisplay">0</span></h3>
<h3>Lng: <span id="lngDisplay">0</span></h3>

<script>
    var map;
    var marker;
    var lastLatLng;
    var secondLastLatLng;
    function myMap()
    {
        var mapProp= {
          center:new google.maps.LatLng(51.508742,-0.120850),
          zoom:5,
        };
        map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
        enableMarkerPlacement();
    }

    function enableMarkerPlacement(){
        google.maps.event.addListener(map,'click',function(event) {
            drawMarker(event.latLng);

            lastLatLng = event.latLng;
            
            displayCoordinates(event.latLng);
        });
    }

    function drawMarker(latLng, changedMarker = false, clearLast = true){
        const greenMarker = {
            url: "greenMarker.png",
            scaledSize: new google.maps.Size(50, 50)
        };

        if (marker != null && clearLast) 
            marker.setMap(null);

        marker = new google.maps.Marker({position: latLng, icon: changedMarker ? greenMarker : null});
        marker.setMap(map);
    }

    function drawLine(startLatLng, finishLatLng){
        var myTrip = [startLatLng,finishLatLng];
        var flightPath = new google.maps.Polyline({
            path:myTrip,
            strokeColor:"#e40000",
            strokeOpacity:0.8,
            strokeWeight:4
        });
        flightPath.setMap(map);
    }

    function displayCoordinates(latLng){
        latDisplay.innerHTML = latLng.lat();
        lngDisplay.innerHTML = latLng.lng();
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=API_KEY&callback=myMap&loading=async"></script>

</body>
</html>