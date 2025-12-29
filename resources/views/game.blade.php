@extends ('layouts.app')

@section ('content')

<style>
    #mapHolder {
        border: 1px solid black;
        height: 350px;
        position: relative;
        cursor: pointer;
        pointer-events: auto;
    }
    #mapCanvas {
        /* border:1px solid #d3d3d3; */
        left: 12px;
        top: 0;
        /* width: calc(100% - 24px);
        height: 100%; */
        position: absolute;
        pointer-events: none;
    }
    #placeImage {
        border: 1px solid black;
    }
    #buttonHolder {
        height: 350px;
        width: calc(100% - 24px);
        position: absolute;
        top: 0;
        pointer-events: none;"
    }
    #leftScrollButton {
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 30px;
        padding: 5px;
        border-radius: 6px 0 0 6px;
        pointer-events: auto;
    }
    #rightScrollButton {
        position: absolute;
        right: 0;
        top: 0;
        height: 100%;
        width: 30px;
        padding: 5px;
        border-radius: 0 6px 6px 0;
        pointer-events: auto;
    }
    #upScrollButton {
        position: absolute;
        top: 0;
        left: 30px;
        height: 30px;
        width: calc(100% - 60px);
        padding: 0;
        border-radius: 0;
        pointer-events: auto;
    }
    #downScrollButton {
        position: absolute;
        bottom: 0;
        left: 30px;
        height: 30px;
        width: calc(100% - 60px);
        padding: 0;
        border-radius: 0;
        pointer-events: auto;
    }
    #zoomInButton {
        position: absolute;
        bottom: 65px;
        right: 35px;
        height: 25px;
        width: 100px;
        padding: 0;
        pointer-events: auto;
    }
    #zoomOutButton {
        position: absolute;
        bottom: 35px;
        right: 35px;
        height: 25px;
        width: 100px;
        padding: 0;
        pointer-events: auto;
    }
    #finishButton {
        display: none;
    }
</style>
<div class="px-4 py-3 my-2 text-center">
    <h1 class="display-5 fw-bold text-body-emphasis my-2">Geolocation guesser</h1>
    <hr class="mx-5">
    <div class="row align-items-start">
        <div class="col-3 p-3">
            <div class="border rounded-3 mx-5">
                <h5>Current place records:</h5>
                <ol id="recordList"></ol>
            </div>
        </div>
        <div class="col-6 border rounded-3">
            <br>
                <!-- <button onclick="nextGame()" class="btn btn-primary">Next game</button>
                <button onclick="selectNewRandomGame()" class="btn btn-primary">Random game</button> -->
            <h3 id="message">Choose the point on the map</h3>
            <div class="container">
                <div class="row align-items-start">
                    <div class="col-6" style="position: relative;">
                        <!-- <img id="mapImage" src="img/map.png" alt="image of a map" width="100%" class="rounded-3" style="height: 350px;"> -->
                        <div id="mapHolder" class="rounded-3"></div>
                        <canvas id="mapCanvas" width="200" height="100">
                            Your browser does not support the HTML canvas tag.
                        </canvas>
                        <div id="buttonHolder" class="rounded-3">
                            <button id="leftScrollButton" onmouseover="leftScrollActivate()" onmouseout="leftScrollDeactivate()" class="btn btn-outline-secondary">←</button>
                            <button id="rightScrollButton" onmouseover="rightScrollActivate()" onmouseout="rightScrollDeactivate()" class="btn btn-outline-secondary">→</button>
                            <button id="upScrollButton" onmouseover="upScrollActivate()" onmouseout="upScrollDeactivate()" class="btn btn-outline-secondary">↑</button>
                            <button id="downScrollButton" onmouseover="downScrollActivate()" onmouseout="downScrollDeactivate()" class="btn btn-outline-secondary">↓</button>
                            <button id="zoomInButton" onmousedown="zoomInActivate()" onmouseup="zoomInDeactivate()" onmouseout="zoomInDeactivate()" class="btn btn-outline-primary">Zoom in</button>
                            <button id="zoomOutButton" onmousedown="zoomOutActivate()" onmouseup="zoomOutDeactivate()" onmouseout="zoomOutDeactivate()" class="btn btn-outline-primary">Zoom out</button>
                        </div>
                    </div>
                    <div class="col-6">
                        <img id="placeImage" src="img/image_1.png" alt="place num.1" class="rounded-3" width="100%" style="height: 350px;">
                    </div>
                </div>
            </div>
            <br>
            <form action="/submitResult" method="post">
                @csrf
                <input type="hidden" id="result_place_id" name="place_id" value="1">
                <input type="hidden" id="result_user_id" name="user_id" value="1">
                <input type="hidden" id="result_result" name="result" value="0.4">
                <input type="hidden" id="result_wasted_time" name="wasted_time" value="00:01:11">
                <input type="hidden" id="result_created_date" name="created_date" value="2000-01-01">
                <input type="hidden" id="result_mapInputXPerc" name="mapInputXPerc">
                <input type="hidden" id="result_mapInputYPerc" name="mapInputYPerc">
                <button id="confirmButton" onclick="confirmInput()" class="btn btn-success">Confirm</button>
            </form>
            <a id="finishButton" href="/home" class="btn btn-success">Finish</a>
            <hr>
            <h4>You clicked on:</h4>
            <p id="coordinates">Coordinates</p>
            <h4>Result:</h4>
            <p id="result">Result</p>
        </div>
    </div>
</div>

<script>
    // DATA EXTRACTION
    const images = [
    @foreach ($data["places"] as $place)
        [{{ $place["pos_X_perc"] }}, {{ $place["pos_Y_perc"] }}, "{{ $place["image_name"] }}", "{{ $place["id"] }}"], 
    @endforeach
    ];
    const records = [
    @foreach ($data["results"] as $record)
        [{{ $record["place_id"] }}, "{{ $record["name"] }}", "{{ $record["result"] }}"], 
    @endforeach
    ];
    
    var startTime = new Date();
    var currentImageArrayId = 0;
    var inputReceived = false;
    var inputConfirmed = false;

    
    var rect = document.getElementById("mapHolder").getBoundingClientRect();
    var scrollAmount = 5;
    var zoomAmount = .04;
    var mapPosX = 0;
    var mapPosY = 0;
    var zoom = 1;
    var zoomIn = false;
    var zoomOut = false;
    var leftScroll = false;
    var rightScroll = false;
    var upScroll = false;
    var downScroll = false;

    addEventListener("resize", hangleResizing);
    document.getElementById("mapHolder").style.backgroundImage = "url('img/map.png')";
    calcMapHolderSize();
    resetMapSize();
    calcMapSize();
    updateRecordList();

    resizeMapCanvas()
    hideConfirmButton();

    @if ($resultView)
        mapInputXPerc = {{ $data["mapInputXPerc"] }};
        mapInputYPerc = {{ $data["mapInputYPerc"] }};
        inputReceived = true;
        
        
        restoreCanvas();
        selectGameByImageId({{ $data['place_id'] }});
        inputReceived = true;
        confirmInput();
        restoreCanvas();
    @else
        selectRandomGame();
    @endif

    requestAnimationFrame(Repeat);
    
    function hangleResizing() {
        calcMapHolderSize();
        calcMapSize();
        applyMapSize();
        correctMapPos();
        applyMapPos();

        restoreCanvas();
    }

    // TOGGLES
    function zoomInActivate() {
        zoomIn = true;
    }
    function zoomInDeactivate() {
        zoomIn = false;
    }
    function zoomOutActivate() {
        zoomOut = true;
    }
    function zoomOutDeactivate() {
        zoomOut = false;
    }
    function leftScrollActivate() {
        leftScroll = true;
    }
    function leftScrollDeactivate() {
        leftScroll = false;
    }
    function rightScrollActivate() {
        rightScroll = true;
    }
    function rightScrollDeactivate() {
        rightScroll = false;
    }
    function upScrollActivate() {
        upScroll = true;
    }
    function upScrollDeactivate() {
        upScroll = false;
    }
    function downScrollActivate() {
        downScroll = true;
    }
    function downScrollDeactivate() {
        downScroll = false;
    }

    // REPEATER
    function Repeat() {
        if (leftScroll){
            mapPosX += scrollAmount;
            correctMapPosLeft();
            applyMapPosX(mapPosX);
            restoreCanvas();
        }
        else if (rightScroll){
            mapPosX -= scrollAmount;
            correctMapPosRight();
            applyMapPosX(mapPosX);
            restoreCanvas();
        }
        else if (upScroll){
            mapPosY += scrollAmount;
            correctMapPosUp();
            applyMapPosY(mapPosY);
            restoreCanvas();
        }
        else if (downScroll){
            mapPosY -= scrollAmount;
            correctMapPosDown();
            applyMapPosY(mapPosY);
            restoreCanvas();
        }
        if (zoomIn || zoomOut){
            calcMapCenterPosPerc();
            if (zoomIn) {
                zoom += zoomAmount;
                if (zoom > 10) zoom = 10;
            }
            else if (zoomOut) {
                zoom -= zoomAmount;
                if (zoom < 1) zoom = 1;
            }
            calcMapSize();
            applyMapSize();

            mapPosX = -Math.abs((mapCenterPosXPerc * mapWidth) - (mapHolderWidth * .5));
            mapPosY = -Math.abs((mapCenterPosYPerc * mapHeight) - (mapHolderHeight * .5));

            if (zoomOut) {
                correctMapPos();
            }

            applyMapPos();
            restoreCanvas();

        }
        requestAnimationFrame(Repeat);
    }

    // ZOOM
    document.getElementById("mapHolder").addEventListener("wheel", event => {
        zoomPosXPerc = Math.floor((event.clientX - rect.left)) / mapHolderWidth;
        zoomPosYPerc = Math.floor((event.clientY - rect.top)) / mapHolderHeight;
        calcMapPosPercFrom(zoomPosXPerc, zoomPosYPerc);
        zoom -= event.deltaY / 500;
        if (zoom > 10) zoom = 10;
        else if (zoom < 1) zoom = 1;
        calcMapSize();
        applyMapSize();

        mapPosX = -Math.abs((mapCenterPosXPerc * mapWidth) - (mapHolderWidth * (zoomPosXPerc)));
        mapPosY = -Math.abs((mapCenterPosYPerc * mapHeight) - (mapHolderHeight * (zoomPosYPerc)));

        correctMapPos();

        applyMapPos();
        restoreCanvas();
    });

    function restoreCanvas() {
        resizeMapCanvas()
        if (inputReceived) {
            mapInputX = mapInputXPerc * mapWidth;
            mapInputY = mapInputYPerc * mapHeight;

            mapHolderInputX = mapInputX + mapPosX;
            mapHolderInputY = mapInputY + mapPosY;

            drawCircle(mapHolderInputX, mapHolderInputY);
        }
        if (inputConfirmed) {
            calcCorrectCoordinates();
            drawLine(correctMapHolderX, correctMapHolderY, mapHolderInputX, mapHolderInputY);
            drawCircle(correctMapHolderX, correctMapHolderY, "green");
        }
    }

    // GAME SELECTOR
    function nextGame() {
        currentImageArrayId++;
        if (currentImageArrayId == images.length) currentImageArrayId = 0;
        selectGame(currentImageArrayId);
    }
    function selectNewRandomGame() {
        selectGame(newRandomImageArrayId());
    }
    function selectRandomGame() {
        selectGame(randomImageArrayId());
    }
    function selectGameByImageId(id) {
        selectGame(getImageArrayIdFromImageId(id));
    }
    function selectGame(imageArrayId){
        currentImageArrayId = imageArrayId;
        document.getElementById("placeImage").src = "img/" + images[imageArrayId][2];
        inputReceived = false;
        inputConfirmed = false;

        clearCanvas();
        hideConfirmButton();
        resetMapSize();
        resetMapPos();
        enableMap();

        updateRecordList();
        document.getElementById('coordinates').innerHTML = "Coordinates";
        document.getElementById('result').innerHTML = "Result";
        document.getElementById('message').innerHTML = "Choose the point on the map";
    }

    // INPUT
    document.getElementById('mapHolder').onclick = function(e) {
        // e = Mouse click event.
        mapHolderInputX = e.clientX - rect.left; //x position within the element.
        mapHolderInputY = e.clientY - rect.top;  //y position within the element.
        mapHolderInputXPerc = Math.floor(mapHolderInputX / mapHolderWidth);
        mapHolderInputYPerc = Math.floor(mapHolderInputY / mapHolderHeight);
        mapInputX = Math.abs(mapPosX) + mapHolderInputX;
        mapInputY = Math.abs(mapPosY) + mapHolderInputY;
        mapInputXPerc = mapInputX / mapWidth;
        mapInputYPerc = mapInputY / mapHeight;
        inputReceived = true;
        
        showConfirmButton();

        resizeMapCanvas()
        clearCanvas();
        drawCircle(mapHolderInputX, mapHolderInputY);

        console.log(mapInputXPerc + " - " + mapInputYPerc);
    }

    function confirmInput() {
        calcCorrectCoordinates();

        // confirmed
        inputConfirmed = true;

        // show input data
        document.getElementById('coordinates').innerHTML = 
            "Left: " + Math.trunc(mapInputXPerc * 10000) / 100 + "%" +
            " ; Top: " + Math.trunc(mapInputYPerc * 10000) / 100 + "%";
        // show results
        document.getElementById('result').innerHTML = "You were " + Math.trunc(calcHypotenuse(Math.abs(correctXPerc - mapInputXPerc), Math.abs(correctYPerc - mapInputYPerc)) * 10000) / 100 + "% close";
        // show message
        document.getElementById('message').innerHTML = "You can view your results and go to next game";

        currentDate = new Date();
        finishTime = new Date();
        document.getElementById('result_place_id').value = images[currentImageArrayId][3];
        document.getElementById('result_user_id').value = {{ Auth::user()->id }};
        document.getElementById('result_result').value = Math.trunc(calcHypotenuse(Math.abs(correctXPerc - mapInputXPerc), Math.abs(correctYPerc - mapInputYPerc)) * 10000) / 100;
        document.getElementById('result_wasted_time').value = secondsToTime((finishTime - startTime) / 1000);
        document.getElementById('result_created_date').value = currentDate.getFullYear() + "-" + (currentDate.getMonth() + 1) + "-" + currentDate.getDate();
        document.getElementById('result_mapInputXPerc').value = mapInputXPerc;
        document.getElementById('result_mapInputYPerc').value = mapInputYPerc;

        drawLine(correctMapHolderX, correctMapHolderY, mapHolderInputX, mapHolderInputY);
        drawCircle(correctMapHolderX, correctMapHolderY, "green");
        
        hideConfirmButton();
        disableMap();

        document.getElementById('finishButton').style.display = "initial";
    }

    // RECORDS
    function updateRecordList() {
        recordListHtml = "";
        let recordArray = [];
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
                    if (j > 4) break;
                }
            }
        }
        for (let i = 0; i < recordArray.length; i++) {
            recordListHtml += "<li>" + recordArray[i][0] + " - " + recordArray[i][1] + "% </li>";
        }
        document.getElementById('recordList').innerHTML = recordListHtml;
    }

    // MAP POSITION AND SIZE
    function resetMapPos() {
        mapPosX = 0;
        mapPosY = 0;
        applyMapPos();
    }
    function applyMapPos() {
        applyMapPosX();
        applyMapPosY();
    }
    function applyMapPosX() {
        document.getElementById("mapHolder").style.backgroundPositionX = mapPosX + "px";
    }
    function applyMapPosY() {
        document.getElementById("mapHolder").style.backgroundPositionY = mapPosY + "px";
    }
    function correctMapPos() {
        correctMapPosLeft();
        correctMapPosRight();
        correctMapPosUp();
        correctMapPosDown();
    }
    function correctMapPosLeft() {
        if (mapPosX > 0) mapPosX = 0;
    }
    function correctMapPosRight() {
        if (mapPosX < (mapWidth - mapHolderWidth) * -1) mapPosX = (mapWidth - mapHolderWidth) * -1;
    }
    function correctMapPosUp() {
        if (mapPosY > 0) mapPosY = 0;
    }
    function correctMapPosDown() {
        if (mapPosY < (mapHeight - mapHolderHeight) * -1) mapPosY = (mapHeight - mapHolderHeight) * -1;
    }
    function resetMapSize() {
        zoom = 1;
        calcMapSize()
        document.getElementById("mapHolder").style.backgroundSize = "100% 100%";
    }
    function applyMapSize() {
        document.getElementById("mapHolder").style.backgroundSize = mapWidth + "px " + mapHeight + "px";
    }
    function enableMap() {
        document.getElementById("mapHolder").style.pointerEvents = "auto";
    }
    function disableMap() {
        document.getElementById("mapHolder").style.pointerEvents = "none";
    }

    // CANVAS
    function resizeMapCanvas() {
        calcMapHolderSize();
        mapCanvas = document.getElementById("mapCanvas");
        mapCanvas.width = mapHolderWidth;
        mapCanvas.height = mapHolderHeight;
    }
    function drawLine(startX, startY, endX, endY) {
        var c = document.getElementById("mapCanvas");
        var ctx = c.getContext("2d");
        ctx.moveTo(startX,startY);
        ctx.lineTo(endX,endY);
        ctx.lineWidth = 2;
        ctx.stroke();    
    }
    function drawCircle(centerX, centerY, color="red") {
        var c = document.getElementById("mapCanvas");
        var ctx = c.getContext("2d");
        ctx.beginPath();
        ctx.arc(centerX, centerY, 5, 0, 2 * Math.PI);
        ctx.fillStyle = color;
        ctx.lineWidth = 1;
        ctx.fill();
        ctx.stroke();
    }
    function clearCanvas() {
        canvas = document.getElementById("mapCanvas");
        const context = canvas.getContext('2d');
        context.clearRect(0, 0, canvas.width, canvas.height);
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
        while (newRandomImage == currentImageArrayId);
        return newRandomImage;
    }
    function calcMapSize() {
        mapWidth = mapHolderWidth * zoom;
        mapHeight = mapHolderHeight * zoom;
    }
    function calcMapPosPercFrom(inputXPerc, inputYPerc) {
        mapCenterPosXPerc = (Math.abs(mapPosX) + (mapHolderWidth * inputXPerc)) / mapWidth;
        mapCenterPosYPerc = (Math.abs(mapPosY) + (mapHolderHeight * inputYPerc)) / mapHeight;
    }
    function calcMapCenterPosPerc() {
        calcMapPosPercFrom(.5, .5);
    }
    function calcMapHolderSize() {
        mapHolderWidth = document.getElementById("mapHolder").clientWidth;
        mapHolderHeight = document.getElementById("mapHolder").clientHeight;
    }
    function calcCorrectCoordinates() {
        // correct input data
        correctXPerc = images[currentImageArrayId][0] / 100;
        correctYPerc = images[currentImageArrayId][1] / 100;

        // percentage to px
        correctX = correctXPerc * mapWidth;
        correctY = correctYPerc * mapHeight;

        // percentage to mapHolder px
        correctMapHolderX = mapWidth * correctXPerc + mapPosX;
        correctMapHolderY = mapHeight * correctYPerc + mapPosY;
    }
    function calcHypotenuse(a, b) {
        return Math.sqrt(a * a + b * b);
    }
    function secondsToTime(seconds) {
        if (seconds < 60)
            return seconds;
        else if (seconds < 3600)
            return "00:" + Math.floor(seconds / 60) + ":" + (seconds % 60);
        else
            return Math.floor(Math.floor(seconds / 60) / 60) + ":" + (Math.floor(seconds / 60) % 60) + ":" + (seconds % 60);
    }
</script>

@endsection