@extends('layouts.app')

@section('content')
@if(session('message'))
<div class="alert alert-info">
    {{session('message')}}
</div>
@endif

<div class="px-4 py-5 my-5 text-center">
    <div class="col-lg-6 mx-auto" style='max-width: 300px;'>
        <form action="/editCategory" method="post">
            @csrf
            <label for="name">
                <span lang="en">Edit category name:</span>
                <span lang="lv">Rediģet nosaukumu:</span>
            </label>
            <input id="name" name="name" type="text" value="{{ $name }}" class="form-control" maxlength="25">
            <input id="id" name="id" type="hidden" value="{{ $id }}" class="form-control">
            <br>
            <button type="submit" class="btn btn-primary">
                <span lang="en">Save changes</span>
                <span lang="lv">Saglabat izmaiņas</span>
            </button>
            <a href="/categorylist" type="button" class="btn btn-secondary">
                <span lang="en">Cancel</span>
                <span lang="lv">Atcelt</span>
            </a>
        </form>
    </div>
</div>
@endsection