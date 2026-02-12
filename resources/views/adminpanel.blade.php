@extends ('layouts.app')
@section ('content')
<div class="px-4 py-5 my-5 text-center">
    <div class="col-lg-6 mx-auto">
        @if(Auth::user()->admin)
            <a href="/addNewPlace" class="btn btn-primary">Add a new place</a>
        @endif
        <a href="/placelist" class="btn btn-primary">View all places</a>
    </div>
</div>
@endsection