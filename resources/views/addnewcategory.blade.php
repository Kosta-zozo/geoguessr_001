@extends('layouts.app')

@section('content')
@if(session('message'))
<div class="alert alert-info">
    {{session('message')}}
</div>
@endif

<div class="px-4 py-5 my-5 text-center">
    <div class="col-lg-6 mx-auto" style='max-width: 300px;'>
        <form action="/addCategory" method="post">
            @csrf
            <label for="name">
                <span lang="en">Enter new category name:</span>
                <span lang="lv">Ievadi jaunas kategorijas nosaukumu:</span>
            </label>
            <input id="name" name="name" type="text" class="form-control" maxlength="25">
            <br>
            <button type="submit" class="btn btn-primary">
                <span lang="en">Submit new category</span>
                <span lang="lv">Izveidot jaunu kategoriju</span>
            </button>
            <a href="/categorylist" class="btn btn-secondary">
                <span lang="en">Cancel</span>
                <span lang="lv">Atcelt</span>
            </a>
        </form>
    </div>
</div>
@endsection