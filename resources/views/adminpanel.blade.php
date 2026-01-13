@extends ('layouts.app')
@section ('content')
<div class="px-4 py-5 my-5 text-center">
    <div class="col-lg-2 mx-auto">
        <h2 class="text-danger">This page is under development! (it's not working)</h2>
        <h3>Add new place</h3>
        <form action="/addPlace" method="post" enctype="multipart/form-data">
            @csrf
            <input type="text" class="form-control"><br>
            <input id="image-file" name="image" type="file" class="form-control"><br>
            <select name="difficulty" id="form-difficulty" class="form-control">
                <option value="easy">easy</option>
                <option value="medium">medium</option>
                <option value="hard">hard</option>
            </select><br>
            <input type="submit" value="Submit new place" class="btn btn-primary">
        </form>
    </div>
</div>
@endsection