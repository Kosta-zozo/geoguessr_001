@extends('layouts.app')

@section('content')
@if(session('message'))
<div class="alert alert-info">
    {{session('message')}}
</div>
@endif

<div class="px-4 py-5 my-5 text-center">
    <div class="col-lg-6 mx-auto" style='max-width: 300px;'>
        <form action="/editCategory" method="post" enctype="multipart/form-data">
            @csrf
            <label for="name">
                <span lang="en">Edit category name:</span>
                <span lang="lv">Rediģet nosaukumu:</span>
            </label>
            <input id="name" name="name" type="text" value="{{ $name }}" class="form-control" maxlength="25">
            <input id="id" name="id" type="hidden" value="{{ $id }}" class="form-control">
            <br>
            <label for="imageFile" class="btn btn-secondary">
                <span lang="en">Upload your image</span>
                <span lang="lv">Augšupielādējiet savu attēlu</span>
            </label>
            <input id="imageFile" name="image" type="file" style="display:none;">
            <img id="placeImage" src="/public/img/{{ $image_name }}" alt="attels nav pieejams" width="100%" style="height: 350px;">
            <br>
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