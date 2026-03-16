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
            <a href="/addNewCategory" class="btn btn-primary border-dark rounded-0 m-1">Add a new category</a> <br>
        @endif
        @foreach ($categories as $category)
            <div class="placeCard row align-content-start border border-2 border-dark m-2 p-2">
                <div class="col">
                    <p class="m-0 d-flex justify-content-between">
                        Category name: {{ $category['name'] }}
                        @if(Auth::user()->admin)
                            <a href="/{{ $category['id'] }}/deletecategory" class="btn btn-danger border-dark rounded-0 justify-content-end">Delete</a>
                        @endif
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection