@extends ('layouts.app')
@section ('content')
<style>
    .gameCard {
        width: 200px;
    }
    img {
        width: 100%;
        height: 150px;
        border: #f2f2f2 5px solid;
    }
    .btn-danger {
        background-color: #BA3F3F;
        border-color: #BA3F3F;
    }
    .btn-primary, .bg-primary {
        background-color: #46769B !important;
        border-color: #46769B;
    }
    .btn-primary:hover {
        background-color: #2F5F8A !important;
        border-color: #2F5F8A;
    }
</style>
<div class="px-4 py-5 my-5 text-center">
    <h3 class="display-5 fw-bold text-body-emphasis">
        <span lang="en">Choose your challenge</span>
        <span lang="lv">Izvelies savu izaicinajumu</span>
    </h3>
    <div class="col-lg-6 mx-auto">
        <p class="lead mb-4">
            <span lang="en">Here you can pick game mode of your choice.</span>
            <span lang="lv">Šeit tu vari izveleties speles temu</span>
        </p>
        <button class="btn btn-primary rounded-0 shadow" onclick="showEasyMode()">
            <span lang="en">Easy</span>
            <span lang="lv">Viegli</span>
        </button>
        <button class="btn btn-warning rounded-0 shadow" onclick="showMediumMode()">
            <span lang="en">Medium</span>
            <span lang="lv">Videji</span>
        </button>
        <button class="btn btn-danger rounded-0 shadow" onclick="showHardMode()">
            <span lang="en">Hard</span>
            <span lang="lv">Gruti</span>
        </button>
        <br>
        <br>
        <div id="easymode" class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <!-- <a href="/game" type="button" class="btn btn-primary btn-lg px-4 gap-3">Start a single game</a> -->
            <!-- <a href="/gameStartSerie" type="button" class="btn btn-primary btn-lg px-4 gap-3">Start game serie of 3</a> -->

            @foreach($categories as $category)
                @if($category['easy'])
                    <button onclick="openBeginConfirmation('{{$category['category_id']}}','{{$category['name']}}','easy')" type="button" class="gameCard btn btn-primary btn-lg px-4 gap-3 rounded-0 shadow">
                        <span lang="en">Start game serie of 5 (<b>{{$category['name']}}</b>)</span>
                        <span lang="lv">Uzsakt speles seriju no 5 raundiem (<b>{{$category['name']}}</b>)</span>
                        <hr>
                        <img src="img/@if($category['image_name']){{$category['image_name']}}@else{{'placeholder.jpg'}}@endif" alt="image not found">
                    </button>
                @endif
            @endforeach
        </div>
        <div>
        <div id="mediummode" class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            @foreach($categories as $category)
                @if($category['medium'])
                    <button onclick="openBeginConfirmation('{{$category['category_id']}}','{{$category['name']}}','medium')" type="button" class="gameCard btn btn-warning btn-lg px-4 gap-3 rounded-0 shadow">
                        <span lang="en">Start game serie of 5 (<b>{{$category['name']}}</b>)</span>
                        <span lang="lv">Uzsakt speles seriju no 5 raundiem (<b>{{$category['name']}}</b>)</span>
                        <hr>
                        <img src="img/@if($category['image_name']){{$category['image_name']}}@else{{'placeholder.jpg'}}@endif" alt="image not found">
                    </button>
                @endif
            @endforeach
        </div>
        </div>
        <div id="hardmode" class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            @foreach($categories as $category)
                @if($category['hard'])
                    <button onclick="openBeginConfirmation('{{$category['category_id']}}','{{$category['name']}}','hard')" type="button" class="gameCard btn btn-danger btn-lg px-4 gap-3 rounded-0 shadow">
                        <span lang="en">Start game serie of 5 (<b>{{$category['name']}}</b>)</span>
                        <span lang="lv">Uzsakt speles seriju no 5 raundiem (<b>{{$category['name']}}</b>)</span>
                        <hr>
                        <img src="img/@if($category['image_name']){{$category['image_name']}}@else{{'placeholder.jpg'}}@endif" alt="image not found">
                    </button>
                @endif
            @endforeach
        </div>
    </div>
</div>

<div id="beginConfirmation" class="position-fixed top-50 start-50 translate-middle bg-light shadow-lg p-3 text-center">
    <h4 id="beginHeader">
        <span lang="en">You selected</span>
        <span lang="lv">Jus izvelejuši</span>
        (<b id="beginCategoryLabel">Category</b>)
    </h4>
    <div id="beginConfirmationDiff_easy" class="bg-primary text-light p-3">
        <h5>
            <span lang="en">You'll start game at (EASY) difficulty</span>
            <span lang="lv">Jūs sāksiet spēli ar (VIEGLI) grūtības pakāpi</span>
        </h5>
        <p class="mb-0">
            <span lang="en">Default difficulty. Zoom and location image are accessible with no restrictions.</span>
            <span lang="lv">Noklusējuma grūtības pakāpe. Tālummaiņa un atrašanās vietas attēls ir pieejams bez ierobežojumiem.</span>
        </p>
    </div>
    <div id="beginConfirmationDiff_medium" class="bg-warning p-3">
        <h5>
            <span lang="en">You'll start game at (MEDIUM) difficulty</span>
            <span lang="lv">Jūs sāksiet spēli ar (VIDEJI) grūtības pakāpi</span>
        </h5>
        <p class="mb-0">
            <span lang="en">This difficulty disables ability to zoom map.</span>
            <span lang="lv">Šī grūtības pakāpe atspējo kartes tālummaiņas iespēju.</span>
        </p>
    </div>
    <div id="beginConfirmationDiff_hard" class="bg-danger text-light p-3">
        <h5>
            <span lang="en">You'll start game at (HARD) difficulty</span>
            <span lang="lv">Jūs sāksiet spēli ar (GRUTI) grūtības pakāpi</span>
        </h5>
        <p class="mb-0">
            <span lang="en">This difficulty disables ability to zoom map. Location image will be visible only for few seconds.</span>
            <span lang="lv">Šī grūtības pakāpe atspējo kartes tālummaiņas iespēju. Atrašanās vietas attēls būs redzams tikai dažas sekundes.</span>
        </p>
    </div>
    <br>
    <a id="beginButtom" href="/VALUE/deletecategory" class="btn btn-success rounded-0">
        <span lang="en">Begin</span>
        <span lang="lv">Sakt</span>
    </a>
    <button onclick="hideBeginConfirmation()" class="btn btn-secondary rounded-0">
        <span lang="en">I changed my mind</span>
        <span lang="lv">Es pārdomāju</span>
    </button>
</div>

<script>
    showEasyMode();
    hideBeginConfirmation();
    
    function showEasyMode(){
        easymode.style.setProperty('display', 'none');
        mediummode.style.setProperty('display', 'none', 'important');
        hardmode.style.setProperty('display', 'none', 'important');
    }
    function showMediumMode(){
        easymode.style.setProperty('display', 'none', 'important');
        mediummode.style.setProperty('display', 'none');
        hardmode.style.setProperty('display', 'none', 'important');
    }
    function showHardMode(){
        easymode.style.setProperty('display', 'none', 'important');
        mediummode.style.setProperty('display', 'none', 'important');
        hardmode.style.setProperty('display', 'none');
    }
    
    function openBeginConfirmation(categoryid, categoryname, difficulty)
    {
        beginButtom.href = "/" + categoryid + "/" + difficulty + "/gameStartSerie";
        beginCategoryLabel.innerHTML = categoryname;
        beginConfirmation.style.display = "initial";

        selectBeginConfirmationDiff(difficulty);
    }
    function hideBeginConfirmation()
    {
        beginConfirmation.style.display = "none";
    }
    function selectBeginConfirmationDiff(difficulty)
    {
        beginConfirmationDiff_easy.style.display = "none";
        beginConfirmationDiff_medium.style.display = "none";
        beginConfirmationDiff_hard.style.display = "none";

        document.getElementById('beginConfirmationDiff_' + difficulty).style.display = "block";
    }
</script>

@endsection