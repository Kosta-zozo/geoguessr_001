@extends('layouts.app')

@section('content')

<style>
    #map {
        border: 1px solid black;
        width: 450px;
        height: 350px;
        position: relative;
    }
    .highlight:hover {
        filter: brightness(110%); 
    }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}" />

@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

@if(session('message'))
<div class="alert alert-info">
    {{session('message')}}
</div>
@endif

<div class="row justify-content-center">
    @if(Auth::user()->admin)
    <div class="col-3">
        @if(Auth::user()->admin)
            <a href="/addNewCategory" class="btn btn-primary border-dark rounded-0 m-1">
                <span lang="en">Create a new category</span>
                <span lang="lv">Izveidot jaunu temu</span>
            </a>
        @endif
        <div class="overflow-auto" style="height: 680px;">
        @foreach ($categories as $category)
            <div class="row align-content-start border border-2 border-dark m-2 p-2">
                <div class="col">
                    <div class="m-0 d-flex justify-content-between">
                        @if(Auth::user()->admin)
                            <div class="col-4 row m-0">
                                <button onclick="openDeleteWindow({{ $category['id'] }})" class="col-6 btn btn-danger border-dark rounded-0">
                                    <span lang="en">Delete</span>
                                    <span lang="lv">Dzest</span>
                                </button>
                                <a href="/{{ $category['id'] }}/editcategory" class="col-6 btn btn-primary border-dark rounded-0">
                                    <span lang="en">Edit</span>
                                    <span lang="lv">Rediģet</span>
                                </a>
                                <!-- <a href="/{{ $category['id'] }}/deletecategory" class="btn btn-danger border-dark rounded-0 justify-content-end">Delete</a> -->
                            </div>
                        @endif
                        <button id='categoryButton-{{ $category["name"] }}' categoryname='{{ $category["name"] }}' onclick='filterPlaces("{{ $category["name"] }}", "keep")' class="categoryButton col-8 m-0 btn btn-secondary border-dark rounded-0 text-start text-nowrap">
                            <span lang="en">Category name: </span>
                            <span lang="lv">Temas nosaukums: </span>
                            <b>{{ $category['name'] }}</b>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </div>
    <div class="col-2 border border-2 border-dark position-relative">
        <hr class="mt-5 mb-0">
        <h3 id="placeListLabel" class="text-center m-2">Category name</h3>
        <a href="/addNewPlace" class="btn btn-primary border-dark rounded-0 m-1 position-absolute top-0 end-0">
            <span lang="en">Create new place</span>
            <span lang="lv">Izveidot jaunu lokaciju</span>
        </a>
        <p class="border border-dark rounded-0 m-1 py-1 px-2 position-absolute top-0 start-0">
            <span lang="en">Count: </span>
            <span lang="lv">Skaits: </span>
            <span id="placeCount"></span>
        </p>
        <div class="row">
            <button id="placeFilterButton_current" onclick="filterPlaces(placeListLabel.innerHTML, 'current')" class="placeFilterButton col btn btn-primary border-dark rounded-0">
                <span lang="en">Current</span>
                <span lang="lv">Tekošie</span>
            </button>
            <button id="placeFilterButton_free" onclick="filterPlaces(placeListLabel.innerHTML, 'free')" class="placeFilterButton col btn btn-primary border-dark rounded-0">
                <span lang="en">Free</span>
                <span lang="lv">Pieejamie</span>
            </button>
            <button id="placeFilterButton_all" onclick="filterPlaces(placeListLabel.innerHTML, 'all')" class="placeFilterButton col btn btn-primary border-dark rounded-0">
                <span lang="en">All</span>
                <span lang="lv">Visi</span>
            </button>
        </div>
        <div class="overflow-auto" style="height: 680px;">
            
            @foreach ($places as $place)
            <div id="placeCard_{{ $place['id'] }}" placeid="{{ $place['id'] }}" class="placeCard position-relative">
                <input type="hidden" value="{{ $place['name'] }}"> <!-- for filtering -->
                <div id="placeLoadingScreen_{{ $place['id'] }}" class="position-absolute w-100 h-100 start-0" style="background-color: rgba(255, 255, 255, 0.5); z-index: -1;"></div>
                <div class="border border-2 border-dark m-2 p-2 ">
                    <!-- <div class="col-3">
                        {{ $place['name'] }}
                    </div> -->
                    <div class="w-100">
                        <div class="row">
                        <div class="col-6 pe-0">
                            <img onclick="openImagePreview('{{ $place['image_name'] }}');" src="/public/img/{{ $place['image_name'] }}" alt="image not found" class="img-fluid border border-2 border-dark h-100 highlight" style="cursor: pointer;">
                        </div>
                        <div class="col-6 ps-0 position-relative">
                            <div class="position-relative h-100 bg-secondary border border-dark text-light text-truncate">
                                <p class="m-1">
                                    <span lang="en">Lat: </span>
                                    <span lang="lv">Plat: </span>
                                    <b>{{ $place['lat'] }}</b>
                                </p>
                                <p class="m-1">
                                    <span lang="en">Lng: </span>
                                    <span lang="lv">Gar: </span>
                                    <b>{{ $place['lng'] }}</b>
                                </p>
                                <button onclick="openMapPreview({{ $place['lat'] }}, {{ $place['lng'] }}, '{{ $place['image_name'] }}')" class="btn btn-info border-dark rounded-0 w-100">
                                    <span lang="en">Map</span>
                                    <span lang="lv">Mape</span>
                                </button>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="detachButton_{{ $place['id'] }}" class="col-12">
                            <a href="javascript:void(0)" onclick="activatePlaceLoadingScreen({{ $place['id'] }}); detachPlace('{{ $place['id'] }}')" class="btn btn-warning border-dark rounded-0 w-100">
                                >>>
                                <span lang="en">Detach</span>
                                <span lang="lv">Izņemt</span>
                                <span id="detachButtonCategory_{{ $place['id'] }}" style="font-size: 14px;">({{ $place['name'] }})</span>
                            </a>
                        </div>
                        <div id="attachButton_{{ $place['id'] }}" class="col-12">
                            <a href="javascript:void(0)" onclick="activatePlaceLoadingScreen({{ $place['id'] }}); attachPlace('{{ $place['id'] }}', selectedCategory)" class="btn btn-success border-dark rounded-0 w-100">
                                <<<
                                <span lang="en">Attach</span>
                                <span lang="lv">Pievienot</span>
                            </a>
                        </div>
                        <div class="col-6 pe-0">
                            <a href="/{{ $place['id'] }}/editplace" class="btn btn-primary border-dark rounded-0 w-100">
                                <span lang="en">Edit</span>
                                <span lang="lv">Rediģet</span>
                            </a>
                        </div>
                        <div class="col-6 ps-0">
                            <button onclick="openDeletePlaceWindow({{ $place['id'] }})" class="btn btn-danger border-dark rounded-0 w-100">
                                <span lang="en">Delete</span>
                                <span lang="lv">Dzest</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="col-6 text-center">
        <br>
        <br>
        <br>
        <h3>
            <span lang="en">Your account doesn't have permission to access admin panel.</span>
            <span lang="lv">Jums nav tiesibu, lai piekļut administracijas lapai.</span>
        </h3>
    </div>
    @endif
</div>

<div id="deleteConfirmation" class="position-fixed top-50 start-50 translate-middle border border-2 border-dark bg-light shadow-lg p-3">
    <h4 id="deleteHeader">
        <span lang="en">Are you sure you want to delete this category?</span>
        <span lang="lv">Vai tiešām vēlaties dzēst to temu?</span>
    </h4>
    <p>
        <span lang="en">(it will delete all connected places and records)</span>
        <span lang="lv">(tas izdzēsīs visas pievienotās vietas un rezultatus)</span>
    </p>
    <a id="deleteButton" href="/VALUE/deletecategory" class="btn btn-danger border-dark rounded-0">
        <span lang="en">Delete</span>
        <span lang="lv">Dzest</span>
    </a>
    <button onclick="hideDeleteWindow()" class="btn btn-primary border-dark rounded-0">
        <span lang="en">Cancel</span>
        <span lang="lv">Atcelt</span>
    </button>
</div>

<div id="deletePlaceConfirmation" class="position-fixed top-50 start-50 translate-middle border border-2 border-dark bg-light shadow-lg p-3">
    <h4 id="deletePlaceHeader">
        <span lang="en">Are you sure you want to delete this place?</span>
        <span lang="lv">Vai tiešām vēlaties dzest to lokaciju?</span>
    </h4>
    <p>
        <span lang="en">(it will delete all connected records)</span>
        <span lang="lv">(tas izdzēsīs visus pievienotus rezultatus)</span>
    </p>
    <buttom id="deletePlaceButton" class="btn btn-danger border-dark rounded-0">
        <span lang="en">Delete</span>
        <span lang="lv">Dzest</span>
    </buttom>
    <button onclick="hideDeletePlaceWindow()" class="btn btn-primary border-dark rounded-0">
        <span lang="en">Cancel</span>
        <span lang="lv">Atcelt</span>
    </button>
</div>
<div id="mapPreview" class="position-fixed top-50 start-50 translate-middle border border-2 border-dark bg-light shadow-lg p-3">
    <div class="row">
        <h4 class="col-6">
            <span lang="en">Location preview</span>
            <span lang="lv">Lokacijas priekšskats</span>
        </h4>
        <div class="col-6" style="min-height:50px">
            <button onclick="hideMapPreview()" class="btn btn-secondary border-dark rounded-0 position-absolute end-0 me-3">
                <span lang="en">Close</span>
                <span lang="lv">Aizvert</span>
            </button>
        </div>
    </div>
    <div id="map"></div>
    <img id="mapPreviewImage" src="/public/img/map.png" alt="image not found" class="img-fluid border border-1 border-dark position-absolute h-25" style="z-index:400; transform:translateY(-100%); pointer-events: none; border-radius: 0px 25px 0px 0px;">
</div>
<div id="imagePreview" class="position-fixed top-50 start-50 translate-middle border border-2 border-dark bg-light shadow-lg p-3">
    <div class="row">
        <h4 class="col-6">
            <span lang="en">Location preview</span>
            <span lang="lv">Lokacijas priekšskats</span>
        </h4>
        <div class="col-6" style="min-height:50px">
            <button onclick="hideImagePreview()" class="btn btn-secondary border-dark rounded-0 position-absolute end-0 me-3">
                <span lang="en">Close</span>
                <span lang="lv">Aizvert</span>
            </button>
        </div>
    </div>
    <img id="imagePreviewImage" alt="image not found" class="border border-1 border-dark">
</div>

<script>
    // ///<<< MAP VERSION 2 >>>\\\
    
    var map = L.map('map').setView([51.505, -0.09], 2);
    var marker;

    var mapEnabled = true;

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

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

    // \\\<<< MAP VERSION 2 >>>///

    let placeCards = document.getElementsByClassName('placeCard');
    let categoryButtons = document.getElementsByClassName('categoryButton');
    let placeFilterButtons = document.getElementsByClassName('placeFilterButton');

    let selectedCategory;
    let lastFilterMode;

    hideDeleteWindow();
    hideDeletePlaceWindow();
    hideMapPreview();
    hideImagePreview();
    filterPlaces(categoryButtons[0].attributes.categoryname.value, "current");
    handleAttachmentButtons();

    function openDeleteWindow(id)
    {
        deleteButton.href = "/" + id + "/deletecategory";
        deleteConfirmation.style.display = "initial";
    }
    function hideDeleteWindow()
    {
        deleteConfirmation.style.display = "none";
    }
    function openDeletePlaceWindow(id)
    {
        deletePlaceButton.addEventListener("click", deletePlace);
        deletePlaceButton.idForDelete = id;
        deletePlaceConfirmation.style.display = "initial";
    }
    function hideDeletePlaceWindow()
    {
        deletePlaceConfirmation.style.display = "none";
    }
    function openMapPreview(lat, lng, imagename)
    {
        mapPreviewImage.src = "/public/img/" + imagename;
        drawMarker(lat, lng);
        map.setView(marker.getLatLng(),5);
        mapPreview.style.display = "initial";
    }
    function hideMapPreview()
    {
        mapPreview.style.display = "none";
    }
    function openImagePreview(imagename)
    {
        imagePreviewImage.src = "/img/" + imagename;
        imagePreview.style.display = "initial";
    }
    function hideImagePreview()
    {
        imagePreview.style.display = "none";
    }

    function filterPlaces(name, mode) // mode: all/current/free | keep
    {
        if (mode == "keep")
            mode = lastFilterMode;
        if (mode == "current")
        {
            for (let i = 0; i < placeCards.length; i++) {
                if (placeCards[i].firstElementChild.value == name)
                    placeCards[i].style.display = 'initial';
                else
                    placeCards[i].style.display = 'none';
            }
        }
        else if (mode == "all")
        {
            for (let i = 0; i < placeCards.length; i++) {
                placeCards[i].style.display = 'initial';
            }
        }
        else
        {
            for (let i = 0; i < placeCards.length; i++) {
                if (placeCards[i].firstElementChild.value == '')
                    placeCards[i].style.display = 'initial';
                else
                    placeCards[i].style.display = 'none';
            }
        }
        setPlaceListLabel(name);
        updatePlaceCount();
        highlightCategoryButton(name);
        highlightPlaceFilterButton(mode);

        selectedCategory = name;
        lastFilterMode = mode;
    }
    function updatePlaceCount()
    {
        let count = 0;
        for (let i = 0; i < placeCards.length; i++) {
            if (placeCards[i].style.display != "none")
                count++;
        }
        placeCount.innerHTML = count;
    }
    function setPlaceListLabel(text)
    {
        placeListLabel.innerHTML = text;
    }
    function highlightCategoryButton(categoryName)
    {
        for (let i = 0; i < categoryButtons.length; i++) {
            if (categoryButtons[i].id == "categoryButton-"+categoryName)
            {
                categoryButtons[i].classList.remove('btn-secondary');
                categoryButtons[i].classList.add('btn-primary');
            }
            else
            {
                categoryButtons[i].classList.remove('btn-primary');
                categoryButtons[i].classList.add('btn-secondary');
            }
        }
    }
    function highlightPlaceFilterButton(mode)
    {
        for (let i = 0; i < placeFilterButtons.length; i++) {
            if (placeFilterButtons[i].id == "placeFilterButton_" + mode)
            {
                placeFilterButtons[i].classList.remove('btn-secondary');
                placeFilterButtons[i].classList.add('btn-primary');
            }
            else
            {
                placeFilterButtons[i].classList.remove('btn-primary');
                placeFilterButtons[i].classList.add('btn-secondary');
            }
        }
    }

    function handleAttachmentButtons()
    {
        for (let i = 0; i < placeCards.length; i++) {
                if (placeCards[i].firstElementChild.value == '')
                {
                    document.getElementById('detachButton_' + placeCards[i].attributes.placeid.value).style.display = 'none';
                    document.getElementById('attachButton_' + placeCards[i].attributes.placeid.value).style.display = 'initial';
                }
                else
                {
                    document.getElementById('detachButton_' + placeCards[i].attributes.placeid.value).style.display = 'initial';
                    document.getElementById('attachButton_' + placeCards[i].attributes.placeid.value).style.display = 'none';
                }
            }
    }

    function detachPlace(id)
    {
        $.ajaxSetup({
		    headers:
		    {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});
        $.ajax({
            url:'/detachPlace/' + id,
            type:'DELETE',
            
            success:function(result)
            {
                let temp = document.getElementById('placeCard_' + id).firstElementChild.value;
                document.getElementById('placeCard_' + id).firstElementChild.value = '';
                handleAttachmentButtons();
                filterPlaces(temp, 'current');
                deactivatePlaceLoadingScreen(id);
            }
        })
    }
    function attachPlace(id, category)
    {
        $.ajaxSetup({
		    headers:
		    {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});
        $.ajax({
            url:'/attachPlace/' + id + '/' + category,
            type:'DELETE',
            
            success:function(result)
            {
                document.getElementById('placeCard_' + id).firstElementChild.value = category;
                handleAttachmentButtons();
                document.getElementById('detachButtonCategory_' + id).innerHTML = '(' + category + ')';
                filterPlaces(category, 'free'); //result['category']
                deactivatePlaceLoadingScreen(id);
            }
        })
    }
    function deletePlace()
    {
        $.ajaxSetup({
		    headers:
		    {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});
        $.ajax({
            url:'/deletePlaceFromCard/' + deletePlaceButton.idForDelete,
            type:'DELETE',
            
            success:function(result)
            {
                document.getElementById('placeCard_' + deletePlaceButton.idForDelete).remove();
                hideDeletePlaceWindow();
                updatePlaceCount();
            }
        })
    }
    function activatePlaceLoadingScreen(id)
    {
        document.getElementById('placeLoadingScreen_' + id).style.zIndex = "1";
    }
    function deactivatePlaceLoadingScreen(id)
    {
        document.getElementById('placeLoadingScreen_' + id).style.zIndex = "-1";
    }
</script>
@endsection