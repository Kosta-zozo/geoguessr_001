@extends ('layouts.app')
@section ('content')    
<div class="px-4 py-5 my-5 text-center">
    <h3 class="display-5 fw-bold text-body-emphasis">Choose your game</h3>
    <div class="col-lg-6 mx-auto">
        <p class="lead mb-4">Here you can pick game mode of your choice.</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a href="/game" type="button" class="btn btn-primary btn-lg px-4 gap-3">Start a single game</a>
            <a href="/gameStartSerie" type="button" class="btn btn-primary btn-lg px-4 gap-3">Start game serie of 3</a>
            <a href="/gameStartSerieEasy" type="button" class="btn btn-primary btn-lg px-4 gap-3">Start game serie of 3 (easy)</a>
            <a href="/gameStartSerieMedium" type="button" class="btn btn-primary btn-lg px-4 gap-3">Start game serie of 3 (medium)</a>
            <a href="/gameStartSerieHard" type="button" class="btn btn-primary btn-lg px-4 gap-3">Start game serie of 3 (hard)</a>
        </div>
    </div>
</div>
@endsection