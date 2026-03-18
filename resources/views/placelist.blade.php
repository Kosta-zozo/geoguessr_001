@extends('layouts.app')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="row justify-content-center">
    <div class="col-6">
        @if(Auth::user()->admin)
            <a href="/addNewPlace" class="btn btn-primary border-dark rounded-0 m-1">
                <span lang="en">Add a new place</span>
                <span lang="lv">Izveidot jaunu lokaciju</span>
            </a> <br>
        @endif
        <button class="btn btn-secondary border-dark rounded-0 m-1" onclick="filterPlaces('all')">
            <span lang="en">All</span>
            <span lang="lv">Visi</span>
        </button>
        <button class="btn btn-secondary border-dark rounded-0 m-1" onclick="filterPlaces('easy')">
            <span lang="en">Easy</span>
            <span lang="lv">Viegli</span>
        </button>
        <button class="btn btn-secondary border-dark rounded-0 m-1" onclick="filterPlaces('medium')">
            <span lang="en">Medium</span>
            <span lang="lv">Videji</span>
        </button>
        <button class="btn btn-secondary border-dark rounded-0 m-1" onclick="filterPlaces('hard')">
            <span lang="en">Hard</span>
            <span lang="lv">Gruti</span>
        </button>
        <!-- <p id="test">test</p> -->
        @foreach ($places as $place)
            <div class="placeCard row align-content-start border border-2 border-dark m-2 p-2">
                <input type="hidden" value="{{ $place['difficulty'] }}"> <!-- for filtering -->
                <div class="col-3">
                    <img src="public/img/{{ $place['image_name'] }}" alt="No image found" class="w-100 h-100 border border-1 border-dark">
                </div>
                <div class="col">
                    <p>
                        <span lang="en">Image name: </span>
                        <span lang="lv">Attelas nosaukums: </span>
                        {{ $place['image_name'] }}
                    </p>
                    <p>
                        <span lang="en">Country: </span>
                        <span lang="lv">Valsts: </span>
                        {{ $place['name'] }}
                    </p>
                    <p>
                        <span lang="en">Difficulty: </span>
                        <span lang="lv">Grutiba: </span>
                        {{ $place['difficulty'] }}
                    </p>
                    @if(Auth::user()->admin)
                        <a href="/{{ $place['place_id'] }}/deleteplace" class="btn btn-danger border-dark rounded-0">
                            <span lang="en">Delete</span>
                            <span lang="lv">Dzest</span>
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    let placeCards = document.getElementsByClassName('placeCard');
    function filterPlaces(difficulty) {
        if (difficulty != "all")
            for (let index = 0; index < placeCards.length; index++) {
                if (placeCards[index].firstElementChild.value == difficulty)
                    placeCards[index].style.display = "flex";
                else
                    placeCards[index].style.display = "none";

            }
        else
            for (let index = 0; index < placeCards.length; index++) {
                placeCards[index].style.display = "flex";
            }
    }
</script>

@endsection