@extends ('layouts.app')
@section ('content')    
<div class="px-4 py-5 my-5 text-center">
    <h3 class="display-5 fw-bold text-body-emphasis">Choose your challenge</h3>
    <div class="col-lg-6 mx-auto">
        <p class="lead mb-4">Here you can pick game mode of your choice.</p>
        <button class="btn btn-primary" onclick="showEasyMode()">Easy</button>
        <button class="btn btn-warning" onclick="showMediumMode()">Medium</button>
        <button class="btn btn-danger" onclick="showHardMode()">Hard</button>
        <br>
        <br>
        <div id="easymode" class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <!-- <a href="/game" type="button" class="btn btn-primary btn-lg px-4 gap-3">Start a single game</a> -->
            <!-- <a href="/gameStartSerie" type="button" class="btn btn-primary btn-lg px-4 gap-3">Start game serie of 3</a> -->
            <a href="/1/easy/gameStartSerie" type="button" class="btn btn-primary btn-lg px-4 gap-3">Start game serie of 5 (Historical places)</a>
            <a href="/2/easy/gameStartSerie" type="button" class="btn btn-primary btn-lg px-4 gap-3">Start game serie of 5 (Water bodies)</a>
            <a href="/3/easy/gameStartSerie" type="button" class="btn btn-primary btn-lg px-4 gap-3">Start game serie of 5 (Monuments)</a>
            <a href="/4/easy/gameStartSerie" type="button" class="btn btn-primary btn-lg px-4 gap-3">Start game serie of 5 (Main streets)</a>
            <a href="/6/easy/gameStartSerie" type="button" class="btn btn-primary btn-lg px-4 gap-3">Start game serie of 5 (City names)</a>
        </div>
        <div>
        <div id="mediummode" class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a href="/1/medium/gameStartSerie" type="button" class="btn btn-warning btn-lg px-4 gap-3">Start game serie of 5 (Historical places)</a>
            <a href="/2/medium/gameStartSerie" type="button" class="btn btn-warning btn-lg px-4 gap-3">Start game serie of 5 (Water bodies)</a>
            <a href="/3/medium/gameStartSerie" type="button" class="btn btn-warning btn-lg px-4 gap-3">Start game serie of 5 (Monuments)</a>
            <a href="/4/medium/gameStartSerie" type="button" class="btn btn-warning btn-lg px-4 gap-3">Start game serie of 5 (Main streets)</a>
            <a href="/6/medium/gameStartSerie" type="button" class="btn btn-warning btn-lg px-4 gap-3">Start game serie of 5 (City names)</a>
        </div>
        </div>
        <div id="hardmode" class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a href="/1/hard/gameStartSerie" type="button" class="btn btn-danger btn-lg px-4 gap-3">Start game serie of 5 (Historical places)</a>
            <a href="/2/hard/gameStartSerie" type="button" class="btn btn-danger btn-lg px-4 gap-3">Start game serie of 5 (Water bodies)</a>
            <a href="/3/hard/gameStartSerie" type="button" class="btn btn-danger btn-lg px-4 gap-3">Start game serie of 5 (Monuments)</a>
            <a href="/4/hard/gameStartSerie" type="button" class="btn btn-danger btn-lg px-4 gap-3">Start game serie of 5 (Main streets)</a>
            <a href="/6/hard/gameStartSerie" type="button" class="btn btn-danger btn-lg px-4 gap-3">Start game serie of 5 (City names)</a>
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