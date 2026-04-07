@extends('layouts.app')

@section('content')
@if(session('message'))
<div class="alert alert-info">
    {{session('message')}}
</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="px-4 py-5 my-5 text-center">
    <div class="col-lg-6 mx-auto" style='max-width: 300px;'>
        <form action="/addCategory" method="post" enctype="multipart/form-data">
            @csrf
            <label for="name">
                <span lang="en">Enter new category name:</span>
                <span lang="lv">Ievadi jaunas kategorijas nosaukumu:</span>
            </label>
            <input id="name" name="name" type="text" class="form-control" maxlength="25">
            <br>
            <label for="imageFile" class="btn btn-secondary">
                <span lang="en">Upload your image</span>
                <span lang="lv">Augšupielādējiet savu attēlu</span>
            </label>
            <input id="imageFile" name="image" type="file" style="display:none;">
            <img id="placeImage" src="img/placeholder.jpg" alt="place num.1" width="100%" style="height: 350px;">
            <br>
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

<script>
    // IMAGE PREVIEW
    imageFile.onchange = evt => {
    const [file] = imageFile.files
    if (file) {
        placeImage.src = URL.createObjectURL(file)
    }
    }
</script>
@endsection