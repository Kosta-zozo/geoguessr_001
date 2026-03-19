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
        <button class="btn btn-primary" onclick="showEasyMode()">
            <span lang="en">Easy</span>
            <span lang="lv">Viegli</span>
        </button>
        <button class="btn btn-warning" onclick="showMediumMode()">
            <span lang="en">Medium</span>
            <span lang="lv">Videji</span>
        </button>
        <button class="btn btn-danger" onclick="showHardMode()">
            <span lang="en">Hard</span>
            <span lang="lv">Gruti</span>
        </button>
        <br>
        <br>
        <div id="easymode" class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <!-- <a href="/game" type="button" class="btn btn-primary btn-lg px-4 gap-3">Start a single game</a> -->
            <!-- <a href="/gameStartSerie" type="button" class="btn btn-primary btn-lg px-4 gap-3">Start game serie of 3</a> -->

            @foreach($categories as $category)
                <a href="/{{$category['category_id']}}/easy/gameStartSerie" type="button" class="btn btn-primary btn-lg px-4 gap-3">
                    <span lang="en">Start game serie of 5 ({{$category['name']}})</span>
                    <span lang="lv">Uzsakt speles seriju no 5 raundiem ({{$category['name']}})</span>
                </a>
            @endforeach
        </div>
        <div>
        <div id="mediummode" class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            @foreach($categories as $category)
                <a href="/{{$category['category_id']}}/medium/gameStartSerie" type="button" class="btn btn-warning btn-lg px-4 gap-3">
                    <span lang="en">Start game serie of 5 ({{$category['name']}})</span>
                    <span lang="lv">Uzsakt speles seriju no 5 raundiem ({{$category['name']}})</span>
                </a>
            @endforeach
        </div>
        </div>
        <div id="hardmode" class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            @foreach($categories as $category)
                <a href="/{{$category['category_id']}}/hard/gameStartSerie" type="button" class="btn btn-danger btn-lg px-4 gap-3">
                    <span lang="en">Start game serie of 5 ({{$category['name']}})</span>
                    <span lang="lv">Uzsakt speles seriju no 5 raundiem ({{$category['name']}})</span>
                </a>
            @endforeach
        </div>
    </div>
</div>

<script>
    showEasyMode();
    
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
    
</script>

@endsection