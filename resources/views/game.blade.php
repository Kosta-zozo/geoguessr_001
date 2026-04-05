@extends ('layouts.app')

@section ('content')

<style>
    #map {
        /* border: 1px solid black;
        height: 350px;
        position: relative; */
        height: calc(100% - 57px);
        cursor: pointer;
        pointer-events: auto;
    }
    #placeImage {
        /* border: 1px solid black; */
    }
    #countdownline {
        position: absolute;
        background-color: #BA3F3F;
        width: calc(100% - 24px);
        height: 10px;
        top: 0;
    }
    #leaflet-menu {
        position: fixed;
        height: 800px;
        width: 300px;
        left: 0;
        top: 50%;
        z-index: 400;
        translate: 0 -50%;
        /* background-color: #d6e5f0; */
        /* background-image: linear-gradient(to bottom, #75A2BF, #d6e5f0); */
        background: linear-gradient(180deg, #75A2BF 0%, #d6e5f0 30%, #d6e5f0 95%, #46769B 100%);
    }
    #timer-holder {
        position: fixed;
        width: 100px;
        padding: 10px;
        text-align: center;
        left: 50%;
        top: 10%;
        z-index: 400;
        translate: -50% 0;
        background-color: #d6e5f0;
    }
    .ll-bg-confirm {
        background-color: #46769B;
        border-width: 0;
        width: 100%;
        height: 50px;
        position: absolute;
        bottom: 0;
        left: 50%;
        translate: -50% 0;
    }
</style>
<!-- <div class="px-4 py-3 my-2 text-center">
    <h1 class="display-5 fw-bold text-body-emphasis my-2">
        <span lang="en">Geolocation guesser</span>
        <span lang="lv">Geolokacijas minetajs</span>
    </h1>
    <hr class="mx-5">
    <div class="row align-items-start">
        <div class="col-3 p-3">
            <div class="border rounded-3 mx-5">
                <button onclick="switchRecordListMode()" class="btn btn-outline-dark"><b id="recordListLabel">
                    <span lang="en">Global current place records:</span>
                    <span lang="lv">Globalie rezultati:</span>
                </b></button>
                <ol id="recordList"></ol>
            </div>
        </div>
        <div class="col-6 border rounded-3">
            <h5 id="timer">Timer</h5>
            <h3 id="message">
                <span lang="en">Choose the point on the map</span>
                <span lang="lv">Izvelies punktu mapē</span>
            </h3>
            <div class="container">
                <div class="row align-items-start">
                    <div class="col-6" style="position: relative;">
                        <div id="map" class="rounded-3"></div>
                    </div>
                    <div class="col-6"  style="position: relative;">
                        <img id="placeImage" src="/public/img/placeholder.jpg" alt="place num.1" class="rounded-3" width="100%" style="height: 350px;">
                        <div id="countdownline"></div>
                    </div>
                </div>
            </div>
            <br>
            <form id="result_form" action="/submitResult" method="post">
                @csrf
                <input type="hidden" id="result_place_id" name="place_id" value="1">
                <input type="hidden" id="result_user_id" name="user_id" value="1">
                <input type="hidden" id="result_result" name="result" value="0.4">
                <input type="hidden" id="result_distance" name="distance" value="0.4">
                <input type="hidden" id="result_wasted_time" name="wasted_time" value="00:01:11">
                <input type="hidden" id="result_created_date" name="created_date" value="2000-01-01">
                <input type="hidden" id="result_serieCount" name="serieCount" value="0">
                <input type="hidden" id="result_usedIdArray" name="usedIdArray">
                <input type="hidden" id="result_resultArray" name="resultArray">
                <input type="hidden" id="result_category" name="category" value="{{ $category }}">
                <input type="hidden" id="result_difficulty" name="difficulty" value="{{ $difficulty }}">
                <button type="submit" id="continueButton" class="btn btn-success">
                    <span lang="en">Continue</span>
                    <span lang="lv">Turpinat</span>
                </button>
                <button type="button" id="confirmButton" onclick="confirmInput()" class="btn btn-success">
                    <span lang="en">Confirm</span>
                    <span lang="lv">Apstiprinat</span>
                </button>
            </form>
            <hr>
            <h4>
                <span lang="en">You clicked on:</span>
                <span lang="lv">Jus piespiedat:</span>
            </h4>
            <p id="coordinates">
                Latitude:
                <span id="latDisplay"> - </span>
                <br>
                Longitude:
                <span id="lngDisplay"> - </span>
            </p>
            <h4>
                <span lang="en">Result:</span>
                <span lang="lv">Rezultats:</span>
            </h4>
            <p id="result">
                <span lang="en">Result</span>
                <span lang="lv">Rezultats</span>
            </p>
        </div>
    </div>
</div> -->
<div id="map"></div>
<div id="leaflet-menu" class="p-3 shadow">
    <h3 id="message" style="color:white;">
        <span lang="en">Choose the point on the map</span>
        <span lang="lv">Izvelies punktu mapē</span>
    </h3>
    <div class="position-relative"  style="">
        <img id="placeImage" src="/img/placeholder.jpg" alt="place num.1" class="" width="100%" style="height: 300px;">
        <div id="countdownline"></div>
    </div>
    <form id="result_form" action="/submitResult" method="post">
        @csrf
        <input type="hidden" id="result_place_id" name="place_id" value="1">
        <input type="hidden" id="result_user_id" name="user_id" value="1">
        <input type="hidden" id="result_result" name="result" value="0.4">
        <input type="hidden" id="result_distance" name="distance" value="0.4">
        <input type="hidden" id="result_wasted_time" name="wasted_time" value="00:01:11">
        <input type="hidden" id="result_created_date" name="created_date" value="2000-01-01">
        <input type="hidden" id="result_serieCount" name="serieCount" value="0">
        <input type="hidden" id="result_usedIdArray" name="usedIdArray">
        <input type="hidden" id="result_resultArray" name="resultArray">
        <input type="hidden" id="result_category" name="category" value="{{ $category }}">
        <input type="hidden" id="result_difficulty" name="difficulty" value="{{ $difficulty }}">
        <button type="submit" id="continueButton" class="ll-bg-confirm btn btn-primary rounded-0">
            <span lang="en">Continue</span>
            <span lang="lv">Turpinat</span>
        </button>
        <button type="button" id="confirmButton" onclick="confirmInput()" class="ll-bg-confirm btn btn-primary rounded-0">
            <span lang="en">Confirm</span>
            <span lang="lv">Apstiprinat</span>
        </button>
    </form>
    <br>
    <h4>
        <span lang="en">Current position:</span>
        <span lang="lv">Tekoša pozicija:</span>
    </h4>
    <p id="coordinates">
        Latitude:
        <span id="latDisplay"> - </span>
        <br>
        Longitude:
        <span id="lngDisplay"> - </span>
    </p>
    <h4>
        <span lang="en">Result:</span>
        <span lang="lv">Rezultats:</span>
    </h4>
    <p id="result">
        <span lang="en">Result</span>
        <span lang="lv">Rezultats</span>
    </p>
</div>
<div id="timer-holder" class="shadow opacity-75">
    <h5 id="timer" class="mb-0">Timer</h5>
</div>

<script>
    // ///<<< MAP VERSION 2 >>>\\\
    
    var map = L.map('map').setView([51.505, -0.09], 3);
    var marker;

    var mapEnabled = true;

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    map.on('click', onMapClick);

    function onMapClick(e) {
        if (!mapEnabled) return;

        drawMarker(e.latlng.lat, e.latlng.lng);
        displayCoordinates(e.latlng);

        // DATA PROCESSING
        inputLat = e.latlng.lat;
        inputLng = e.latlng.lng;
        inputReceived = true;
        showConfirmButton();
    }

    function drawMarker(lat, lng, changedMarker = false, clearLast = true){
        var myIcon = L.icon({
            iconUrl: '/public/greenMarker.png',
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

        var polyline = L.polyline(latlngs, {color: 'black'}).addTo(map);

        if (focus) map.fitBounds(polyline.getBounds());
    }

    function displayCoordinates(latLng){
        latDisplay.innerHTML = latLng.lat;
        lngDisplay.innerHTML = latLng.lng;
    }

    function disableZoom(){
        // map.zoomControl.disable()
        map.scrollWheelZoom.disable()

    }

    // \\\<<< MAP VERSION 2 >>>///

    // DATA EXTRACTION
    const images = [
    @foreach ($data["places"] as $place)
        [{{ $place["lat"] }}, {{ $place["lng"] }}, "{{ $place["image_name"] }}", "{{ $place["id"] }}"], 
    @endforeach
    ];
    const records = [
    @foreach ($data["results"] as $record)
        [{{ $record["place_id"] }}, "{{ $record["name"] }}", "{{ $record["result"] }}"], 
    @endforeach
    ];
    const usedIdArray = [
    @foreach ($usedIdArray as $id)
        "{{ $id }}",
    @endforeach
    ];
    const resultArray = [
    @foreach ($resultArray as $result)
        [
        @foreach ($result as $resultPart)
            "{{ $resultPart }}",
        @endforeach
        ],
    @endforeach
    ];
    
    var startTime = new Date();
    var currentImageArrayId = 0;
    var inputReceived = false;
    var inputConfirmed = false;
    var resultsShownGlobal = true;

    var pageIsLoaded = false;

    // updateRecordList();

    hideConfirmButton();
    continueButton.style.display = "none";

    addEventListener("load", function() {
        startTime = new Date();
        pageIsLoaded= true;
    });

    map.zoomControl.remove();
    @if($difficulty != 'hard')
        countdownline.remove();
    @endif
    @if($difficulty == 'hard' || $difficulty == 'medium')
        disableZoom()
    @endif

    selectNewRandomGame();

    // REPEATER
    requestAnimationFrame(Repeat);
    function Repeat() {
        // TIMER
        if (!inputConfirmed)
        {
            timer.innerHTML = secondsToTime(60 - Math.trunc((new Date() - startTime) / 100) / 10);
                
            @if($difficulty == 'hard')
                if (document.getElementById('countdownline') !== null && pageIsLoaded)
                {
                    if (timer.innerHTML <= 55)
                    {
                        placeImage.src = "/public/img/restricted.png";
                        countdownline.remove();
                    }
                    else
                    {
                        countdownline.style.width = "calc(" + ((5 - (new Date() - startTime) / 100 / 10).toFixed(2) / 5 * 100) + "% - 24px)";
                    } 
                }
            @endif
        }
        requestAnimationFrame(Repeat);
    }

    // GAME SELECTOR
    function selectNewRandomGame() {
        selectGame(newRandomImageArrayId());
    }
    function selectRandomGame() {
        selectGame(randomImageArrayId());
    }
    // function selectGameByImageId(id) {
    //     selectGame(getImageArrayIdFromImageId(id));
    // }
    function selectGame(imageArrayId){
        currentImageArrayId = imageArrayId;
        document.getElementById("placeImage").src = "/public/img/" + images[imageArrayId][2];
        inputReceived = false;
        inputConfirmed = false;

        hideConfirmButton();

        // updateRecordList();
    }

    function confirmInput() {

        // confirmed
        inputConfirmed = true;
        mapEnabled = false;

        correctLat = images[currentImageArrayId][0];
        correctLng = images[currentImageArrayId][1];

        // show results
        document.getElementById('result').innerHTML =
            '<span lang="en">You got </span>'+
            '<span lang="lv">Jus dabojat </span>'+
            Math.round(distanceToPoints(map.distance(L.latLng(inputLat, inputLng), L.latLng(correctLat, correctLng)))) +
            '<span lang="en"> points</span>'+
            '<span lang="lv"> punktu</span>'+
            '<br>'+
            // '<span lang="en">You were </span>'+
            // '<span lang="lv">Jus bijat </span>'+
            '<span lang="en">To the destination is </span>'+
            '<span lang="lv">Lidz galapunktai ir </span>'+
            Math.round(map.distance(L.latLng(inputLat, inputLng), L.latLng(correctLat, correctLng))) / 1000 +
            // '<span lang="en">km close</span>'+
            // '<span lang="lv">km tuvu</span>';
            '<span lang="en">km</span>'+
            '<span lang="lv">km</span>';
        // // show message
        document.getElementById('message').innerHTML = 
            '<span lang="en">You can view your results and go to next game</span>'+
            '<span lang="lv">Jus varat parskatit savus rezultatus un turpinat</span>';


        currentDate = new Date();
        finishTime = new Date();
        document.getElementById('result_place_id').value = images[currentImageArrayId][3];
        document.getElementById('result_user_id').value = {{ Auth::user()->id }};
        document.getElementById('result_result').value = distanceToPoints(map.distance(L.latLng(inputLat, inputLng), L.latLng(correctLat, correctLng)));
        document.getElementById('result_distance').value = map.distance(L.latLng(inputLat, inputLng), L.latLng(correctLat, correctLng));
        document.getElementById('result_wasted_time').value = secondsToTime((finishTime - startTime) / 1000);
        document.getElementById('result_created_date').value = currentDate.getFullYear() + "-" + (currentDate.getMonth() + 1) + "-" + currentDate.getDate();
        result_serieCount.value = {{ $serieCount }};
        result_usedIdArray.value = usedIdArray.length != 0 ? usedIdArray : "none";
        result_resultArray.value = resultArray.length != 0 ? resultArray : "none";
        result_form.action = '/gameContinueSerie';

        drawMarker(correctLat, correctLng, true, false);
        drawLine(L.latLng(inputLat, inputLng), L.latLng(correctLat, correctLng), true);
        
        hideConfirmButton();
        continueButton.style.display = "initial";
    }

    // RECORDS
    function switchRecordListMode() {
        if (resultsShownGlobal)
            
            document.getElementById("recordListLabel").innerHTML = 
            '<span lang="en">Your current place records:</span>'+
            '<span lang="lv">Jusu rezultati:</span>';
        else
            document.getElementById("recordListLabel").innerHTML = 
            '<span lang="en">Global current place records:</span>'+
            '<span lang="lv">Globalie rezultati:</span>';
        resultsShownGlobal = !resultsShownGlobal;
        updateRecordList();
    }
    function updateRecordList() {
        recordListHtml = "";
        let recordArray = [];
        if (resultsShownGlobal) {
            j = 0;
            for (let i = 0; i < records.length; i++) {
                if (records[i][0] == images[currentImageArrayId][3]) {
                    let exists = false;
                    for (let k = 0; k < recordArray.length; k++) {
                        if (recordArray[k][0] == records[i][1]) {
                            exists = true;
                            break;
                        }
                    }
                    if (!exists) {
                        recordArray[j] = [];
                        recordArray[j][0] = records[i][1];
                        recordArray[j][1] = records[i][2];
                        j++;
                        if (j >= 10) break;
                    }
                }
            }
            for (let i = 0; i < recordArray.length; i++) {
                recordListHtml += "<li>" + recordArray[i][0] + " - " + Math.round(recordArray[i][1]) + " points </li>";
            }
            for (let i = 0; i < (10 - recordArray.length); i++) {
                recordListHtml += "<li> - </li>";
            }
        }
        else {
            j = 0;
            for (let i = 0; i < records.length; i++) {
                if (records[i][0] == images[currentImageArrayId][3] && records[i][1] == "{{ Auth::user()->name }}") {
                    recordArray[j] = [];
                    recordArray[j][0] = records[i][1];
                    recordArray[j][1] = records[i][2];
                    j++;
                    if (j >= 10) break;
                }
            }
            for (let i = 0; i < recordArray.length; i++) {
                recordListHtml += "<li>" + recordArray[i][0] + " - " + Math.round(recordArray[i][1]) + " points </li>";
            }
            for (let i = 0; i < (10 - recordArray.length); i++) {
                recordListHtml += "<li> - </li>";
            }
        }
        document.getElementById('recordList').innerHTML = recordListHtml;
    }

    // CONFIRM BUTTON
    function showConfirmButton() {
        document.getElementById("confirmButton").style.display = "initial";
    }
    function hideConfirmButton() {
        document.getElementById("confirmButton").style.display = "none";
    }

    // CALCULATIONS
    function getImageArrayIdFromImageId(id) {
        for (let i = 0; i < images.length; i++) {
            if (images[i][3] == id) {
                return i;
            }
        }
    }
    function randomImageArrayId() {
        return Math.floor(Math.random() * images.length);
    }
    function newRandomImageArrayId() {
        do {
            newRandomImage = randomImageArrayId();
        }
        while (!isImageBeenUsed(newRandomImage));
        return newRandomImage;
    }
    function isImageBeenUsed(imageId) {
        valid = true;
        usedIdArray.forEach(id => {
            if (imageId == getImageArrayIdFromImageId(id)){
                valid = false;
                return false;
            }
        });
        return valid;
    }
    function secondsToTime(seconds) {
        if (seconds < 60)
            return seconds;
        else if (seconds < 3600)
            return "00:" + Math.floor(seconds / 60) + ":" + ((seconds % 60).toFixed(1));
        else
            return Math.floor(Math.floor(seconds / 60) / 60) + ":" + (Math.floor(seconds / 60) % 60) + ":" + ((seconds % 60).toFised(1));
    }

    function distanceToPoints(distance){
        return (1 - (distance / 8500000)) * 2000;
    }
</script>

@endsection