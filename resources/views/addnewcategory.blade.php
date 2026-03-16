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
            <label for="name">Enter new category name:</label>
            <input id="name" name="name" type="text" class="form-control" maxlength="25">
            <br>
            <input type="submit" value="Submit new category" class="btn btn-primary">
        </form>
    </div>
</div>
@endsection