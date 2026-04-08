@extends('layouts.app')

@section('content')

<style>
    #map {
        /* border: 1px solid black; */
        width: 450px;
        height: 350px;
        position: relative;
    }
    .highlight:hover {
        filter: brightness(110%); 
    }

    .color-collection {
        background-color: #f2f2f2;
        background-color: #d6e5f0;
        background-color: #75A2BF;
        background-color: #5E8CAD;
        background-color: #46769B;
        background-color: #2F5F8A;
        background-color: #174978;
        background-color: #003366;
    }
    .ll-bg {
        background-color: #d6e5f0;
    }
    .ll-bg-double {
        /* background-color: #d0dee9; */
        background: linear-gradient(90deg,#d0dee9 0%, #d0dee9 50%, #d6e5f0 50%, #d6e5f0 100%);
    }
    .ll-bg-selected-cat {
        background-color: #46769B;
        border-width: 0 4px 0 0;
        border-color: #003366;
    }
    .ll-bg-selected-fil {
        background-color: #46769B;
        border-width: 0 0 6px 0;
        border-color: #003366;
    }
    .ll-bg-edit {
        background-color: #46769B;
        border-width: 0;
    }
    .ll-bg-light {
        border-width: 0;
        background-color: #f2f2f2;
    }
    .ll-bg-delete {
        border-width: 0;
        background-color: #BA3F3F;
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
<div class="col-6 row justify-content-center shadow p-0">
    @if(Auth::user()->admin)
    <div class="col-4 ll-bg" style="position: relative;">
        @if(Auth::user()->admin)
            <a href="/addNewCategory" class="btn btn-primary rounded-0 m-1 ll-bg-edit">
                <span lang="en">Create a new category +</span>
                <span lang="lv">Izveidot jaunu temu +</span>
            </a>
            <h4>
                <span lang="en">Category List: </span>
                <span lang="lv">Temas saraksts: </span>
                <span onclick="alert(getDiffMessage())" style="cursor: pointer;">&#8505;</span>
            </h4>
            <img id="categoryImagePreview" src="img/placeholder.jpg" alt="image not found" style="position: absolute; top:5px; right:12px; width: 70px; height:70px;">
        @endif
        <div class="overflow-auto" style="height: 680px;">
        @foreach ($categories as $category)
            <div class="row align-content-start">
                <div class="col">
                    <div class="m-0 d-flex justify-content-between">
                        <div class="dropdown col-4 m-0">
                            <button type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-primary rounded-0 dropdown-toggle ll-bg-edit w-100 h-100">
                                <span lang="en">Actions</span>
                                <span lang="lv">Darbibas</span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="/{{ $category['id'] }}/editcategory" class="dropdown-item">
                                    <span lang="en">Edit</span>
                                    <span lang="lv">Rediģet</span>
                                    &#9998;
                                </a>
                                <hr class="dropdown-divider">
                                <button onclick="openDeleteWindow({{ $category['id'] }})" class="dropdown-item">
                                    <span lang="en">Delete</span>
                                    <span lang="lv">Dzest</span>
                                    &#10005;
                                </button>
                            </div>
                        </div>
                        <!-- <div class="col-6 row m-0">
                            <button onclick="openDeleteWindow({{ $category['id'] }})" class="col-6 btn btn-danger rounded-0 ll-bg-delete">
                                <span lang="en">Delete</span>
                                <span lang="lv">Dzest</span>
                            </button>
                            <a href="/{{ $category['id'] }}/editcategory" class="col-6 btn btn-primary rounded-0 ll-bg-edit">
                                <span lang="en">Edit</span>
                                <span lang="lv">Rediģet</span>
                            </a>
                        </div> -->
                        <button id='categoryButton-{{ $category["name"] }}' categoryname='{{ $category["name"] }}' onclick='filterPlaces("{{ $category["name"] }}", "keep"); changeCategoryImagePreview("{{ $category["image_name"] }}")' class="categoryButton col-8 m-0 btn btn-secondary rounded-0 text-start text-nowrap">
                            <!-- <span lang="en">Category name: </span>
                            <span lang="lv">Temas nosaukums: </span> -->
                            <input id="easyCategoryDiff_{{ $category['id'] }}" type="checkbox" onclick="editCategoryDifficulty({{ $category['id'] }}, 'easy')" class="rounded-0" style="accent-color: green;" @if($category['easy']) checked @endif>
                            <input id="mediumCategoryDiff_{{ $category['id'] }}" type="checkbox" onclick="editCategoryDifficulty({{ $category['id'] }}, 'medium')" class="rounded-0" style="accent-color: orange;" @if($category['medium']) checked @endif>
                            <input id="hardCategoryDiff_{{ $category['id'] }}" type="checkbox" onclick="editCategoryDifficulty({{ $category['id'] }}, 'hard')" class="rounded-0" style="accent-color: #BA3F3F;" @if($category['hard']) checked @endif>
                            <b>{{ $category['name'] }}</b>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </div>
    <div class="col-8 position-relative ll-bg-double">
        <hr class="mt-5 mb-0">
        <h3 id="placeListLabel" class="text-center m-2">Category name</h3>
        <a href="/addNewPlace" class="btn btn-primary rounded-0 m-1 position-absolute top-0 end-0 ll-bg-edit">
            <span lang="en">Create new place</span>
            <span lang="lv">Izveidot jaunu lokaciju</span>
        </a>
        <p class="m-1 py-1 px-2 position-absolute top-0 start-0">
            <span lang="en">Count: </span>
            <span lang="lv">Skaits: </span>
            <span id="placeCount"></span>
            <span onclick="alert(getCountMessage())" style="cursor: pointer;">&#8505;</span>
        </p>
        <div class="row">
            <button id="placeFilterButton_current" onclick="filterPlaces(placeListLabel.innerHTML, 'current')" class="placeFilterButton col btn btn-secondary rounded-0">
                <span lang="en">Connected</span>
                <span lang="lv">Izmantotie</span>
            </button>
            <button id="placeFilterButton_free" onclick="filterPlaces(placeListLabel.innerHTML, 'free')" class="placeFilterButton col btn btn-secondary rounded-0">
                <span lang="en">Available</span>
                <span lang="lv">Neizmantotie</span>
            </button>
            <button id="placeFilterButton_all" onclick="filterPlaces(placeListLabel.innerHTML, 'all')" class="placeFilterButton col btn btn-secondary rounded-0">
                <span lang="en">All</span>
                <span lang="lv">Visi</span>
            </button>
        </div>
        <div class="overflow-auto row" style="height: 680px;">
            @foreach ($places as $place)
            <div id="placeCard_{{ $place['id'] }}" placeid="{{ $place['id'] }}" class="placeCard col-6 position-relative">
                <!-- Connections -->
                @foreach ($connections as $connection)
                    @if ($connection['place_id'] == $place['id'])
                        <input type="hidden" class="placeCardConnection_{{ $place['id'] }}" placeid="{{ $place['id'] }}"  categoryname="{{ $connection['name'] }}">
                    @endif
                @endforeach
                <!-- <input type="hidden" value=""> for filtering -->
                <div id="placeLoadingScreen_{{ $place['id'] }}" class="position-absolute w-100 h-100 start-0" style="background-color: rgba(255, 255, 255, 0.5); z-index: -1;"></div>
                <div class="m-2 p-2">
                    <!-- <div class="col-3">
                        {{ $place['name'] }}
                    </div> -->
                    <div class="w-100">
                        <div class="row">
                        <div class="col-6 pe-0">
                            <img onclick="openImagePreview('{{ $place['image_name'] }}');" src="/public/img/{{ $place['image_name'] }}" alt="image not found" class="img-fluid h-100 highlight" style="cursor: pointer;">
                        </div>
                        <div class="col-6 ps-0 position-relative">
                            <div class="position-relative h-100 bg-secondary text-light text-truncate">
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
                                <button onclick="openMapPreview({{ $place['lat'] }}, {{ $place['lng'] }}, '{{ $place['image_name'] }}')" class="btn btn-info rounded-0 w-100">
                                    <span lang="en">Map</span>
                                    <span lang="lv">Mape</span>
                                </button>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="detachButton_{{ $place['id'] }}" class="col-12">
                            <a href="javascript:void(0)" onclick="activatePlaceLoadingScreen({{ $place['id'] }}); detachPlace('{{ $place['id'] }}')" class="btn btn-warning rounded-0 w-100">
                                >>>
                                <span lang="en">Detach</span>
                                <span lang="lv">Izņemt</span>
                            </a>
                        </div>
                        <div id="attachButton_{{ $place['id'] }}" class="col-12">
                            <a href="javascript:void(0)" onclick="activatePlaceLoadingScreen({{ $place['id'] }}); attachPlace('{{ $place['id'] }}', selectedCategory)" class="btn btn-success rounded-0 w-100">
                                <<<
                                <span lang="en">Attach</span>
                                <span lang="lv">Pievienot</span>
                            </a>
                        </div>
                        <div class="col-6 pe-0">
                            <a href="/{{ $place['id'] }}/editplace" class="btn btn-primary rounded-0 w-100 ll-bg-edit">
                                <span lang="en">Edit</span>
                                <span lang="lv">Rediģet</span>
                            </a>
                        </div>
                        <div class="col-6 ps-0">
                            <button onclick="openDeletePlaceWindow({{ $place['id'] }})" class="btn btn-danger rounded-0 w-100 ll-bg-delete">
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
</div>

<div id="deleteConfirmation" class="position-fixed top-50 start-50 translate-middle bg-light shadow-lg p-3">
    <h4 id="deleteHeader">
        <span lang="en">Are you sure you want to delete this category?</span>
        <span lang="lv">Vai tiešām vēlaties dzēst to temu?</span>
    </h4>
    <p>
        <span lang="en">(it will delete all connected places and records)</span>
        <span lang="lv">(tas izdzēsīs visas pievienotās vietas un rezultatus)</span>
    </p>
    <a id="deleteButton" href="/VALUE/deletecategory" class="btn btn-danger rounded-0">
        <span lang="en">Delete</span>
        <span lang="lv">Dzest</span>
    </a>
    <button onclick="hideDeleteWindow()" class="btn btn-primary rounded-0">
        <span lang="en">Cancel</span>
        <span lang="lv">Atcelt</span>
    </button>
</div>

<div id="deletePlaceConfirmation" class="position-fixed top-50 start-50 translate-middle bg-light shadow-lg p-3">
    <h4 id="deletePlaceHeader">
        <span lang="en">Are you sure you want to delete this place?</span>
        <span lang="lv">Vai tiešām vēlaties dzest to lokaciju?</span>
    </h4>
    <p>
        <span lang="en">(it will delete all connected records)</span>
        <span lang="lv">(tas izdzēsīs visus pievienotus rezultatus)</span>
    </p>
    <buttom id="deletePlaceButton" class="btn btn-danger rounded-0">
        <span lang="en">Delete</span>
        <span lang="lv">Dzest</span>
    </buttom>
    <button onclick="hideDeletePlaceWindow()" class="btn btn-primary rounded-0">
        <span lang="en">Cancel</span>
        <span lang="lv">Atcelt</span>
    </button>
</div>
<div id="mapPreview" class="position-fixed top-50 start-50 translate-middle bg-light shadow-lg p-3">
    <div class="row">
        <h4 class="col-6">
            <span lang="en">Location preview</span>
            <span lang="lv">Lokacijas priekšskats</span>
        </h4>
        <div class="col-6" style="min-height:50px">
            <button onclick="hideMapPreview()" class="btn btn-secondary rounded-0 position-absolute end-0 me-3">
                <span lang="en">Close</span>
                <span lang="lv">Aizvert</span>
            </button>
        </div>
    </div>
    <div id="map"></div>
    <img id="mapPreviewImage" src="/public/img/map.png" alt="image not found" class="img-fluid position-absolute h-25" style="z-index:400; transform:translateY(-100%); pointer-events: none; border-radius: 0px 25px 0px 0px;">
</div>
<div id="imagePreview" class="position-fixed top-50 start-50 translate-middle bg-light shadow-lg p-3">
    <div class="row">
        <h4 class="col-6">
            <span lang="en">Location preview</span>
            <span lang="lv">Lokacijas priekšskats</span>
        </h4>
        <div class="col-6" style="min-height:50px">
            <button onclick="hideImagePreview()" class="btn btn-secondary rounded-0 position-absolute end-0 me-3">
                <span lang="en">Close</span>
                <span lang="lv">Aizvert</span>
            </button>
        </div>
    </div>
    <img id="imagePreviewImage" alt="image not found" class="">
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
    changeCategoryImagePreview("{{ $categories[0]["image_name"] }}");
    // handleAttachmentButtons();

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
        imagePreviewImage.src = "/public/img/" + imagename;
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
        if (mode == "current" || mode == "free")
        {
            for (let i = 0; i < placeCards.length; i++) {
                let placeCardConnections = document.getElementsByClassName('placeCardConnection_' + placeCards[i].attributes.placeid.value);
                placeCards[i].style.display = mode == "current" ? 'none' : 'initial';
                document.getElementById('detachButton_' + placeCards[i].attributes.placeid.value).style.display = 'none';
                document.getElementById('attachButton_' + placeCards[i].attributes.placeid.value).style.display = 'initial';

                if (placeCardConnections.length == 0) continue;
                
                for (let j = 0; j < placeCardConnections.length; j++)
                {
                    if (placeCardConnections[j].attributes.categoryname.value == name)
                    {
                        placeCards[i].style.display = mode == "current" ? 'initial' : 'none';

                        document.getElementById('detachButton_' + placeCards[i].attributes.placeid.value).style.display = 'initial';
                        document.getElementById('attachButton_' + placeCards[i].attributes.placeid.value).style.display = 'none';

                        break;
                    }
                }
            }
        }
        else if (mode == "all")
        {
            for (let i = 0; i < placeCards.length; i++) {
                let placeCardConnections = document.getElementsByClassName('placeCardConnection_' + placeCards[i].attributes.placeid.value);
                placeCards[i].style.display = 'initial';
                document.getElementById('detachButton_' + placeCards[i].attributes.placeid.value).style.display = 'none';
                document.getElementById('attachButton_' + placeCards[i].attributes.placeid.value).style.display = 'initial';

                if (placeCardConnections.length == 0) continue;
                
                for (let j = 0; j < placeCardConnections.length; j++)
                {
                    if (placeCardConnections[j].attributes.categoryname.value == name)
                    {
                        document.getElementById('detachButton_' + placeCards[i].attributes.placeid.value).style.display = 'initial';
                        document.getElementById('attachButton_' + placeCards[i].attributes.placeid.value).style.display = 'none';

                        break;
                    }
                }
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
                categoryButtons[i].classList.add('ll-bg-selected-cat');
            }
            else
            {
                categoryButtons[i].classList.remove('btn-primary');
                categoryButtons[i].classList.remove('ll-bg-selected-cat');
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
                placeFilterButtons[i].classList.add('ll-bg-selected-fil');
            }
            else
            {
                placeFilterButtons[i].classList.remove('btn-primary');
                placeFilterButtons[i].classList.remove('ll-bg-selected-fil');
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
            url:'/detachPlace/' + id + '/' + selectedCategory,
            type:'DELETE',
            
            success:function(result)
            {
                let placeCardConnections = document.getElementsByClassName('placeCardConnection_' + id);
                for (let j = 0; j < placeCardConnections.length; j++)
                {
                    if (placeCardConnections[j].attributes.categoryname.value == selectedCategory)
                    {
                        placeCardConnections[j].remove();
                        break;
                    }
                }
                filterPlaces(selectedCategory, 'keep');
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
                const newConnection = document.createElement("input");
                newConnection.classList.add("placeCardConnection_" + id);
                newConnection.setAttribute("placeid", id);
                newConnection.setAttribute("categoryname", selectedCategory);
                newConnection.type = "hidden";
                document.getElementById('placeCard_' + id).appendChild(newConnection);
                filterPlaces(category, 'keep'); //result['category']
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
    function editCategoryDifficulty(id, diff)
    {
        console.log(id);
        console.log(diff);
        console.log(document.getElementById(diff + "CategoryDiff_" + id).checked);
        $.ajaxSetup({
		    headers:
		    {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});
        $.ajax({
            url:'/editCategoryDifficulty/' + id + '/' + diff + '/' + document.getElementById(diff + "CategoryDiff_" + id).checked,
            type:'DELETE',
            
            success:function(result)
            {
                console.log("success");
            }
        })
    }
    function changeCategoryImagePreview(imagename)
    {
        categoryImagePreview.src = "/img/" + (imagename ? imagename : "placeholder.jpg");
    }

    function activatePlaceLoadingScreen(id)
    {
        document.getElementById('placeLoadingScreen_' + id).style.zIndex = "1";
    }
    function deactivatePlaceLoadingScreen(id)
    {
        document.getElementById('placeLoadingScreen_' + id).style.zIndex = "-1";
    }

    function getCountMessage()
    {
        if(sessionStorage.getItem("language") == "lv")
            return "Lai ši tema butu pieejama spelei, tajā jabut vismaz 5 lokacijas.";
        else
            return "For this category to be playable, it should hold atleast 5 locations.";
    }
    function getDiffMessage()
    {
        if(sessionStorage.getItem("language") == "lv")
            return "3 izvēles rūtiņas norāda, kādās grūtības pakāpēs šī tema būs pieejama. (viegli/videji/gruti)";
        else
            return "3 checkboxes are showing in which difficulties this category will be available. (easy/medium/hard)";
    }
</script>
@endsection
