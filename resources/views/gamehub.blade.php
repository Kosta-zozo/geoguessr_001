@extends ('layouts.app')
@section ('content')    
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
        <button class="btn btn-primary border border-2 border-dark rounded-0" onclick="showEasyMode()">
            <span lang="en">Easy</span>
            <span lang="lv">Viegli</span>
        </button>
        <button class="btn btn-warning border border-2 border-dark rounded-0" onclick="showMediumMode()">
            <span lang="en">Medium</span>
            <span lang="lv">Videji</span>
        </button>
        <button class="btn btn-danger border border-2 border-dark rounded-0" onclick="showHardMode()">
            <span lang="en">Hard</span>
            <span lang="lv">Gruti</span>
        </button>
        <br>
        <br>
        <div id="easymode" class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <!-- <a href="/game" type="button" class="btn btn-primary btn-lg px-4 gap-3">Start a single game</a> -->
            <!-- <a href="/gameStartSerie" type="button" class="btn btn-primary btn-lg px-4 gap-3">Start game serie of 3</a> -->

            @foreach($categories as $category)
                <button onclick="openBeginConfirmation('{{$category['category_id']}}','{{$category['name']}}','easy')" type="button" class="btn btn-primary btn-lg px-4 gap-3 border border-2 border-dark rounded-0">
                    <span lang="en">Start game serie of 5 ({{$category['name']}})</span>
                    <span lang="lv">Uzsakt speles seriju no 5 raundiem ({{$category['name']}})</span>
                </button>
            @endforeach
        </div>
        <div>
        <div id="mediummode" class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            @foreach($categories as $category)
                <button onclick="openBeginConfirmation('{{$category['category_id']}}','{{$category['name']}}','medium')" type="button" class="btn btn-warning btn-lg px-4 gap-3 border border-2 border-dark rounded-0">
                    <span lang="en">Start game serie of 5 ({{$category['name']}})</span>
                    <span lang="lv">Uzsakt speles seriju no 5 raundiem ({{$category['name']}})</span>
                </button>
            @endforeach
        </div>
        </div>
        <div id="hardmode" class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            @foreach($categories as $category)
                <button onclick="openBeginConfirmation('{{$category['category_id']}}','{{$category['name']}}','hard')" type="button" class="btn btn-danger btn-lg px-4 gap-3 border border-2 border-dark rounded-0">
                    <span lang="en">Start game serie of 5 ({{$category['name']}})</span>
                    <span lang="lv">Uzsakt speles seriju no 5 raundiem ({{$category['name']}})</span>
                </button>
            @endforeach
        </div>
    </div>
</div>

<div id="beginConfirmation" class="position-fixed top-50 start-50 translate-middle border border-2 border-dark bg-light shadow-lg p-3 text-center">
    <h4 id="beginHeader">
        <span lang="en">You selected</span>
        <span lang="lv">Vai tiešām vēlaties dzēst to temu?</span>
        (<b id="beginCategoryLabel">Category</b>)
    </h4>
    <div id="beginConfirmationDiff_easy" class="border border-2 border-dark bg-primary text-light p-3">
        <h5>You'll start game at (EASY) difficulty</h5>
        <p class="mb-0">Default difficulty. Zoom and location image are accessible with no restrictions.</p>
    </div>
    <div id="beginConfirmationDiff_medium" class="border border-2 border-dark bg-warning p-3">
        <h5>You'll start game at (MEDIUM) difficulty</h5>
        <p class="mb-0">This difficulty disables ability to zoom map.</p>
    </div>
    <div id="beginConfirmationDiff_hard" class="border border-2 border-dark bg-danger text-light p-3">
        <h5>You'll start game at (HARD) difficulty</h5>
        <p class="mb-0">This difficulty disables ability to zoom map. Location image will be visible only for few seconds.</p>
    </div>
    <br>
    <a id="beginButtom" href="/VALUE/deletecategory" class="btn btn-success border-dark rounded-0">
        <span lang="en">Begin</span>
        <span lang="lv">Sakt</span>
    </a>
    <button onclick="hideBeginConfirmation()" class="btn btn-secondary border-dark rounded-0">
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