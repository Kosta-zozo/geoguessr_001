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
            <a href="/addNewPlace" class="btn btn-primary border-dark rounded-0 m-1">Add a new place</a>
        @endif
        @foreach ($places as $place)
            <div class="row align-content-start border border-2 border-dark m-2 p-2">
                <div class="col-3">
                    <img src="public/img/{{ $place['image_name'] }}" alt="No image found" class="w-100 h-100 border border-1 border-dark">
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

@endsection