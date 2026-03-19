@extends('layouts.app')

@section('content')

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
    <div class="col-3">
        @if(Auth::user()->admin)
            <a href="/addNewCategory" class="btn btn-primary border-dark rounded-0 m-1">
                <span lang="en">Create a new category</span>
                <span lang="lv">Izveidot jaunu temu</span>
            </a>
        @endif
        @foreach ($categories as $category)
            <div class="row align-content-start border border-2 border-dark m-2 p-2">
                <div class="col">
                    <div class="m-0 d-flex justify-content-between">
                        @if(Auth::user()->admin)
                            <div>
                                <button onclick="openDeleteWindow({{ $category['id'] }})" class="btn btn-danger border-dark rounded-0">
                                    <span lang="en">Delete</span>
                                    <span lang="lv">Dzest</span>
                                </button>
                                <a href="/{{ $category['id'] }}/editcategory" class="btn btn-primary border-dark rounded-0">
                                    <span lang="en">Edit</span>
                                    <span lang="lv">Rediģet</span>
                                </a>
                                <!-- <a href="/{{ $category['id'] }}/deletecategory" class="btn btn-danger border-dark rounded-0 justify-content-end">Delete</a> -->
                            </div>
                        @endif
                        <button id='categoryButton-{{ $category["name"] }}' categoryname='{{ $category["name"] }}' onclick='filterPlaces("{{ $category["name"] }}", "current")' class="categoryButton m-0 btn btn-secondary border-dark rounded-0 text-start col-8">
                            <span lang="en">Category name: </span>
                            <span lang="lv">Temas nosaukums: </span>
                            <b>{{ $category['name'] }}</b>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
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
        @foreach ($places as $place)
        <div id="placeCard_{{ $place['id'] }}" placeid="{{ $place['id'] }}" class="placeCard">
            <input type="hidden" value="{{ $place['name'] }}"> <!-- for filtering -->
            <div class="border border-2 border-dark m-2 p-2 row ">
                <!-- <div class="col-3">
                    {{ $place['name'] }}
                </div> -->
                <div class="col-6">
                    <img src="/img/{{ $place['image_name'] }}" alt="image not found" class="img-fluid border border-2 border-dark">
                </div>
                <div id="detachButton_{{ $place['id'] }}" class="col-6">
                    <a href="javascript:void(0)" onclick="detachPlace('{{ $place['id'] }}')" class="btn btn-danger border-dark rounded-0">
                        <span lang="en">Detach</span>
                        <span lang="lv">Izņemt</span>
                        <span style="font-size: 14px;">({{ $place['name'] }})</span>
                    </a>
                </div>
                <div id="attachButton_{{ $place['id'] }}" class="col-6">
                    <a href="javascript:void(0)" onclick="attachPlace('{{ $place['id'] }}', selectedCategory)" class="btn btn-primary border-dark rounded-0">
                        <span lang="en">Attach</span>
                        <span lang="lv">Pievienot</span>    
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div id="deleteConfirmation" class="position-fixed top-50 start-50 translate-middle border border-2 border-dark bg-light shadow-lg p-3">
    <h4 id="deleteHeader">
        <span lang="en">Are you sure you want to delete that?</span>
        <span lang="lv">Vai tiešām vēlaties to dzēst?</span>
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

<script>
    let placeCards = document.getElementsByClassName('placeCard');
    let categoryButtons = document.getElementsByClassName('categoryButton');
    let placeFilterButtons = document.getElementsByClassName('placeFilterButton');

    let selectedCategory;

    hideDeleteWindow();
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

    function filterPlaces(name, mode) // all/current/free
    {
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
                let temp = document.getElementById('placeCard_' + id).firstElementChild.value;
                document.getElementById('placeCard_' + id).firstElementChild.value = category;
                handleAttachmentButtons();
                filterPlaces('Historical places', 'free');
            }
        })
    }
</script>
@endsection