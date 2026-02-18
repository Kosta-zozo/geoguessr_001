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
            <a href="/addNewPlace" class="btn btn-primary border-dark rounded-0 m-1">Add a new place</a> <br>
        @endif
        <button class="btn btn-secondary border-dark rounded-0 m-1" onclick="filterPlaces('all')">All</button>
        <button class="btn btn-secondary border-dark rounded-0 m-1" onclick="filterPlaces('easy')">Easy</button>
        <button class="btn btn-secondary border-dark rounded-0 m-1" onclick="filterPlaces('medium')">Medium</button>
        <button class="btn btn-secondary border-dark rounded-0 m-1" onclick="filterPlaces('hard')">Hard</button>
        <p id="test">test</p>
        @foreach ($places as $place)
            <div class="placeCard row align-content-start border border-2 border-dark m-2 p-2">
                <input type="hidden" value="{{ $place['difficulty'] }}"> <!-- for filtering -->
                <div class="col-3">
                    <img src="img/{{ $place['image_name'] }}" alt="No image found" class="w-100 h-100 border border-1 border-dark">
                </div>
                <div class="col">
                    <p>Image name: {{ $place['image_name'] }}</p>
                    <p>Country: {{ $place['name'] }}</p>
                    <p>Difficulty: {{ $place['difficulty'] }}</p>
                    @if(Auth::user()->admin)
                        <a href="/{{ $place['place_id'] }}/deleteplace" class="btn btn-danger border-dark rounded-0">Delete</a>
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