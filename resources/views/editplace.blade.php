@extends ('layouts.app')
@section ('content')
<style>
    #map {
        border: 1px solid black;
        height: 350px;
        position: relative;
        cursor: pointer;
        pointer-events: auto;
    }
    #placeImage {
        border: 1px solid black;
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
            <span lang="en">Edit place</span>
            <span lang="lv">Rediģet lokaciju</span>
        </h3>
        <div class="row align-items-start">
            <div class="col" style="position: relative;">
                <div id="map" class="rounded-3"></div>
                </canvas>
                <form action="/editPlace" method="post" enctype="multipart/form-data">@csrf
                <input type="hidden" name="id" value="{{ $place['id'] }}">
                <div class="row align-items-start">
                    <div class="col">
                        <label for="posx">
                            <span lang="en">Enter latitude</span>
                            <span lang="lv">Ievadi ģeogrāfisko platumu</span>
                        </label>
                        <input id="posx" name="posx" type="number" class="form-control" step="any" min="-90" max="90" onchange="synchronizeInput()" value="{{ $place['lat'] }}"><br>
                    </div>
                    <div class="col">
                        <label for="posy">
                            <span lang="en">Enter longitude </span>
                            <span lang="lv">Ievadi ģeogrāfisko garumu</span>
                        </label>
                        <input id="posy" name="posy" type="number" class="form-control" step="any" min="-180" max="180" onchange="synchronizeInput()" value="{{ $place['lng'] }}"><br>
                    </div>
                </div>
            </div>
            <div class="col">
                <img id="placeImage" src="/public/img/{{ $place['image_name'] }}" alt="place num.1" class="rounded-3" width="100%" style="height: 350px;">
                <br>
                <br>
                <label for="imageFile" class="btn btn-secondary">
                    <span lang="en">Upload your image</span>
                    <span lang="lv">Augšupielādējiet savu attēlu</span>
                </label>
                <input id="imageFile" name="image" type="file" style="display:none;"><br>
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
            <label for="form-category" style="display:none;">
                <span lang="en">Choose category</span>
                <span lang="lv">Izvelies temu</span>
            </label>
            <select name="category" id="form-category" class="form-control" style="display:none;">
                <option value=NULL>-</option>
                @foreach ($categories as $category)
                <option value="{{ $category['id'] }}" @if($category['id'] == $place['category_id']) selected @endif>{{ $category['name'] }}</option>
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
                <span lang="en">Save</span>
                <span lang="lv">Saglabat</span>
        </button>
        <a href="/categorylist" type="buttton" class="btn btn-secondary">
                <span lang="en">Return to category list</span>
                <span lang="lv">Atgriezties pie temas saraksta</span>
        </a>
        </form>
    </div>
</div>

<script>
    // ///<<< MAP VERSION 2 >>>\\\
    
    var map = L.map('map').setView([51.505, -0.09], 2);
    var marker;

    var mapEnabled = true;

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    map.on('click', onMapClick);

    function onMapClick(e) {
        if (!mapEnabled) return;

        drawMarker(e.latlng.lat, e.latlng.lng);

        // DATA PROCESSING
        posx.value = e.latlng.lat;
        posy.value = e.latlng.lng;
    }

    function drawMarker(lat, lng, changedMarker = false, clearLast = true){
        var myIcon = L.icon({
            iconUrl: '/greenMarker.png',
            iconSize: [50, 50],
            iconAnchor: [25, 50]
        });

        if (marker && clearLast) marker.remove();
        if (!changedMarker)
            marker = L.marker([lat, lng]).addTo(map);
        else
            marker = L.marker([lat, lng], {icon: myIcon}).addTo(map);
    }
    // \\\<<< MAP VERSION 2 >>>///

    synchronizeInput();

    // INPUT
    function synchronizeInput() {
        drawMarker(posx.value, posy.value);
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
