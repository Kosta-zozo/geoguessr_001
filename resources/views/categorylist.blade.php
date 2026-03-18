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
            <a href="/addNewCategory" class="btn btn-primary border-dark rounded-0 m-1">
                <span lang="en">Add a new category</span>
                <span lang="lv">Izveidot jaunu kategoriju</span>
            </a> <br>
        @endif
        @foreach ($categories as $category)
            <div class="placeCard row align-content-start border border-2 border-dark m-2 p-2">
                <div class="col">
                    <div class="m-0 d-flex justify-content-between">
                        <p class="m-0">
                            <span lang="en">Category name: </span>
                            <span lang="lv">Kategorijas nosaukums: </span>
                            <i>{{ $category['name'] }}</i>
                        </p>
                        @if(Auth::user()->admin)
                            <div>
                                <a href="/{{ $category['id'] }}/editcategory" class="btn btn-primary border-dark rounded-0">
                                    <span lang="en">Edit</span>
                                    <span lang="lv">Rediģet</span>
                                </a>
                                <button onclick="openDeleteWindow({{ $category['id'] }})" class="btn btn-danger border-dark rounded-0">
                                    <span lang="en">Delete</span>
                                    <span lang="lv">Dzest</span>
                                </button>
                                <!-- <a href="/{{ $category['id'] }}/deletecategory" class="btn btn-danger border-dark rounded-0 justify-content-end">Delete</a> -->
                            </div>
                        @endif
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
        <span lang="en">Atcelt</span>
    </button>
</div>

<script>
    hideDeleteWindow();

    function openDeleteWindow(id)
    {
        deleteButton.href = "/" + id + "/deletecategory";
        deleteConfirmation.style.display = "initial";
    }
    function hideDeleteWindow()
    {
        deleteConfirmation.style.display = "none";
    }
</script>
@endsection