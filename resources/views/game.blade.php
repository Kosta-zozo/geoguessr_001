<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        #mapImage {
            border: 1px solid black;
            cursor: pointer;
            pointer-events: auto;
        }
        #mapCanvas {
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
    </style>
</head>

<body>
    @include('includes.header')
    
    <div class="px-4 py-3 my-2 text-center">
        <h1 class="display-5 fw-bold text-body-emphasis my-2">Geolocation guesser</h1>
        <hr class="mx-5">
        <div class="row align-items-start">
            <div class="col-3 p-3">
                <div class="border rounded-3 mx-5">
                    Records:
                </div>
            </div>
            <div class="col-6 border rounded-3">
                <br>
                <button onclick="nextGame()" class="btn btn-primary">Next game</button>
                <h3 id="message">Choose the point on the map</h3>
                <div class="container">
                    <div class="row align-items-start">
                        <div class="col-6" style="position: relative;">
                            <img id="mapImage" src="public/img/map.png" alt="image of a map" width="100%" class="rounded-3" style="height: 350px;">
                            <canvas id="mapCanvas" width="200" height="100" style="border:1px solid #d3d3d3;">
                                Your browser does not support the HTML canvas tag.
                            </canvas>
                        </div>
                        <div class="col-6">
                            <img id="placeImage" src="public/img/image_1.png" alt="place num.1" class="rounded-3" width="100%" style="height: 350px;">
                        </div>
                    </div>
                </div>
                <br>
                <button id="confirmButton" onclick="confirmInput()" class="btn btn-success">Confirm</button>
                <hr>
                <h4>You clicked on:</h4>
                <p id="coordinates">Coordinates</p>
                <h4>Result:</h4>
                <p id="result">Result</p>
            </div>
        </div>
    </div>
    
</body>

</html>

<script>
    const images = [[68, 26, "image_1.png"], [71, 52, "image_2.png"], [0, 0, "image_3.png"]];
    var currentImage = 0;
    var inputReceived = false;
    var inputConfirmed = false;

    resizeMapCanvas()
    hideConfirmButton();

    addEventListener("resize", restoreCanvas);
    function restoreCanvas() {
        resizeMapCanvas()
        if (inputReceived) {
            inputX = mapWidth * inputXPerc / 100;
            inputY = mapHeight * inputYPerc / 100;
            drawCircle(inputX, inputY);
        }
        if (inputConfirmed) {
            calcCorrectCoordinates();
            drawLine(correctX, correctY, inputX, inputY);
            drawCircle(correctX, correctY, "green");
        }
        console.log("Yolo");
    }

    function nextGame() {
        currentImage++;
        if (currentImage == images.length) currentImage = 0;
        document.getElementById("placeImage").src = "public/img/" + images[currentImage][2];
        inputReceived = false;
        inputConfirmed = false;

        clearCanvas();
        hideConfirmButton();
        enableMap();

        document.getElementById('coordinates').innerHTML = "Coordinates";
        document.getElementById('result').innerHTML = "Result";
        document.getElementById('message').innerHTML = "Choose the point on the map";
    }

    document.getElementById('mapImage').onclick = function(e) {
        // e = Mouse click event.
        var rect = e.target.getBoundingClientRect();
        inputX = e.clientX - rect.left; //x position within the element.
        inputY = e.clientY - rect.top;  //y position within the element.
        inputXPerc = Math.floor(inputX/mapWidth*100);
        inputYPerc = Math.floor(inputY/mapHeight*100);
        inputReceived = true;
        
        showConfirmButton();

        resizeMapCanvas(mapWidth, mapHeight)
        clearCanvas();
        drawCircle(inputX, inputY);

        // console.log(Math.sqrt(Math.pow(56.97240179727096-56.519071402589, 2) + Math.pow(24.20977863696495-27.329895642802956, 2)));
    }

    function confirmInput() {
        calcCorrectCoordinates();

        // confirmed
        inputConfirmed = true;

        // show input data
        document.getElementById('coordinates').innerHTML = 
            "Left: " + inputX + "|" + inputXPerc + "%" +
            " ; Top: " + inputY + "|" + inputYPerc + "%" +
            " ; Width: "+ document.getElementById('mapImage').width;
        // show results
        document.getElementById('result').innerHTML = calcHypotenuse(Math.abs(correctX - inputX), Math.abs(correctY - inputY));
        // show message
        document.getElementById('message').innerHTML = "You can view your results and go to next game";

        drawLine(correctX, correctY, inputX, inputY);
        drawCircle(correctX, correctY, "green");
        
        hideConfirmButton();
        disableMap();
    }

    function calcCorrectCoordinates() {
        // correct input data
        correctXPerc = images[currentImage][0];
        correctYPerc = images[currentImage][1];
        // var correctXPerc = 68; // 70
        // var correctYPerc = 26; // 52

        // percentage to px
        correctX = correctXPerc/100 * mapWidth;
        correctY = correctYPerc/100 * mapHeight;
    }

    function calcMapSize() {
        mapWidth = document.getElementById('mapImage').width;
        mapHeight = document.getElementById('mapImage').height;
    }
    function enableMap() {
        document.getElementById("mapImage").style.pointerEvents = "auto";
    }
    function disableMap() {
        document.getElementById("mapImage").style.pointerEvents = "none";
    }

    function resizeMapCanvas() {
        calcMapSize();
        mapCanvas = document.getElementById("mapCanvas");
        mapCanvas.width = mapWidth;
        mapCanvas.height = mapHeight;
    }
    function drawLine(startX, startY, endX, endY) {
        var c = document.getElementById("mapCanvas");
        var ctx = c.getContext("2d");
        ctx.moveTo(startX,startY);
        ctx.lineTo(endX,endY);
        ctx.lineWidth = 2;
        ctx.stroke();    
    }
    // function drawCircle(x, y) {
    //     drawCircle(x, y, "red");
    // }
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

    function showConfirmButton() {
        document.getElementById("confirmButton").style.display = "initial";
    }
    function hideConfirmButton() {
        document.getElementById("confirmButton").style.display = "none";
    }

    function calcHypotenuse(a, b) {
        return Math.sqrt(a * a + b * b);
    }
</script>