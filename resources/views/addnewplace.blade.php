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
</style>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(session('message'))
<div class="alert alert-info">
    {{session('message')}}
</div>
@endif
<div class="px-4 py-5 my-5 text-center">
    <div class="col-lg-6 mx-auto">
        <h3>
            <span lang="en">Add a new place</span>
            <span lang="lv">Izveidot jaunu lokaciju</span>
        </h3>
        <div class="row align-items-start">
            <div class="col" style="position: relative;">
                <div id="mapHolder" class="rounded-3"></div>
                <canvas id="mapCanvas" width="200" height="100">
                    <span lang="en">Your browser does not support the HTML canvas tag.</span>
                    <span lang="lv">Jusu parlukprogramma ir slikta, izmanto citu.</span>
                    
                </canvas>
                <form action="/addPlace" method="post" enctype="multipart/form-data">@csrf
                <div class="row align-items-start">
                    <div class="col">
                        <label for="posx">
                            <span lang="en">Enter X position in percentages</span>
                            <span lang="lv">Ievadi poziciju X procentos</span>
                        </label>
                        <input id="posx" name="posx" type="number" class="form-control" step=".01" min="0" max="100" onchange="synchronizeInput()"><br>
                    </div>
                    <div class="col">
                        <label for="posy">
                            <span lang="en">Enter Y position in percentages</span>
                            <span lang="lv">Ievadi poziciju Y procentos</span>
                        </label>
                        <input id="posy" name="posy" type="number" class="form-control" step=".01" min="0" max="100" onchange="synchronizeInput()"><br>
                    </div>
                </div>
            </div>
            <div class="col">
                <img id="placeImage" src="img/placeholder.jpg" alt="place num.1" class="rounded-3" width="100%" style="height: 350px;">
                <label for="imageFile">
                    <span lang="en">Upload your image</span>
                    <span lang="lv">Augšupielādējiet savu attēlu</span>
                </label>
                <input id="imageFile" name="image" type="file" class="form-control"><br>
            </div>
        </div>
        <div class="col-lg-6 mx-auto">
            <!-- <label for="form-country">
                    <span lang="en">Choose country</span>
                    <span lang="lv">Izvelies valsti</span>
            </label> -->
            <select name="country" id="form-country" class="form-control" style="display:none;">
                @foreach ($countries as $country)
                <option value="{{ $country['id'] }}">{{ $country['name'] }}</option>
                @endforeach
            </select><br>
            <label for="form-category">Choose category</label>
            <select name="category" id="form-category" class="form-control">
                <option value=NULL>-</option>
                @foreach ($categories as $category)
                <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                @endforeach
            </select><br>
            <!-- <label for="form-difficulty">
                <span lang="en">Choose difficulty</span>
                <span lang="lv">Izvelies grutibu</span>
            </label> -->
            <select name="difficulty" id="form-difficulty" class="form-control" style="display:none;">
                <option value="easy">
                    <span lang="en">easy</span>
                    <span lang="lv">viegli</span>
                </option>
                <option value="medium">
                    <span lang="en">medium</span>
                    <span lang="lv">videji</span>
                </option>
                <option value="hard">
                    <span lang="en">hard</span>
                    <span lang="lv">gruti</span>
                </option>
            </select><br>
        </div>
        <button type="submit" class="btn btn-primary">
                <span lang="en">Submit new place</span>
                <span lang="lv">Izveidot jaunu lokaciju</span>
        </button>
        </form>
    </div>
</div>

<script>
    // MAP HOLDER
    var inputReceived = false;
    var rect = document.getElementById("mapHolder").getBoundingClientRect();
    var inputX = 0;
    var inputY = 0;
    onmousemove = function(e){inputX = e.clientX; inputY =  e.clientY;}
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
    var dragging = false;

    addEventListener("resize", hangleResizing);
    document.getElementById("mapHolder").style.backgroundImage = "url('img/map.png')";
    calcMapHolderSize();
    resetMapSize();
    calcMapSize();

    resizeMapCanvas()

    requestAnimationFrame(Repeat);
    
    function hangleResizing() {
        rect = document.getElementById("mapHolder").getBoundingClientRect();
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
        // SCROLL
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
        // ZOOM
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
        // DRAG
        if (dragging) {
            dragAmountX = savedDragAmountX - (dragStartX - Math.floor(inputX - rect.left));
            mapPosX += dragAmountX;
            savedDragAmountX = dragStartX - Math.floor(inputX - rect.left);
            dragAmountX = 0;

            dragAmountY = savedDragAmountY - (dragStartY - Math.floor(inputY - rect.left));
            mapPosY += dragAmountY;
            savedDragAmountY = dragStartY - Math.floor(inputY - rect.left);
            dragAmountY = 0;

            correctMapPos();
            applyMapPosX(mapPosX);
            applyMapPosY(mapPosY);
            restoreCanvas();
        }

        requestAnimationFrame(Repeat);
    }

    // ZOOM
    document.getElementById("mapHolder").addEventListener("wheel", event => {
        zoomPosXPerc = Math.floor(event.clientX - rect.left) / mapHolderWidth;
        zoomPosYPerc = Math.floor(event.clientY - rect.top) / mapHolderHeight;
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

        return false;
    });

    // SCROLL DISABLE
    document.getElementById("mapHolder").addEventListener("mouseenter", event => {
        document.getElementById("body").style = "overflow: hidden;";
    });

    document.getElementById("mapHolder").addEventListener("mouseleave", event => {
        document.getElementById("body").style = "overflow: auto;";
    });

    // DRAG
    document.getElementById("mapHolder").addEventListener("mousedown", event => {
        dragging = true;
        dragStartX = Math.floor(event.clientX - rect.left);
        dragStartY = Math.floor(event.clientY - rect.left);
        savedDragAmountX = dragStartX - Math.floor(inputX - rect.left);
        savedDragAmountY = dragStartY - Math.floor(inputY - rect.left);
    });
    document.getElementById("mapHolder").addEventListener("mouseup", event => {
        dragging = false;
    });
    document.getElementById("mapHolder").addEventListener("mouseleave", event => {
        dragging = false;
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
    }

    // INPUT
    document.getElementById('mapHolder').onclick = function(e) {
        // e = Mouse click event.
        mapHolderInputX = e.offsetX; //x position within the element.
        mapHolderInputY = e.offsetY;  //y position within the element.
        mapHolderInputXPerc = Math.floor(mapHolderInputX / mapHolderWidth);
        mapHolderInputYPerc = Math.floor(mapHolderInputY / mapHolderHeight);
        mapInputX = Math.abs(mapPosX) + mapHolderInputX;
        mapInputY = Math.abs(mapPosY) + mapHolderInputY;
        mapInputXPerc = mapInputX / mapWidth;
        mapInputYPerc = mapInputY / mapHeight;
        inputReceived = true;

        resizeMapCanvas()
        clearCanvas();
        drawCircle(mapHolderInputX, mapHolderInputY);

        posx.value = Math.trunc(mapInputXPerc * 10000) / 100;
        posy.value = Math.trunc(mapInputYPerc * 10000) / 100;
    }
    function synchronizeInput() {
        mapInputXPerc = posx.value / 100;
        mapInputYPerc = posy.value / 100;
        mapInputX = mapInputXPerc * mapWidth;
        mapInputY = mapInputYPerc * mapHeight;
        mapHolderInputX = mapInputX - Math.abs(mapPosX);
        mapHolderInputY = mapInputY - Math.abs(mapPosY);
        mapHolderInputXPerc = Math.floor(mapHolderInputX / mapHolderWidth);
        mapHolderInputYPerc = Math.floor(mapHolderInputY / mapHolderHeight);
        inputReceived = true;

        resizeMapCanvas()
        clearCanvas();
        drawCircle(mapHolderInputX, mapHolderInputY);

        posx.value = Math.trunc(mapInputXPerc * 10000) / 100;
        posy.value = Math.trunc(mapInputYPerc * 10000) / 100;
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

    // CALCULATIONS
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

    // IMAGE PREVIEW
    imageFile.onchange = evt => {
    const [file] = imageFile.files
    if (file) {
        placeImage.src = URL.createObjectURL(file)
    }
    }
</script>
@endsection