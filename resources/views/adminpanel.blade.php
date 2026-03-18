@extends ('layouts.app')
@section ('content')
<div class="px-4 py-5 my-5 text-center">
    <div class="col-lg-6 mx-auto">
        @if(Auth::user()->admin)
            <!-- <a href="/addNewPlace" class="btn btn-primary">
                <span lang="en">Add a new place</span>
                <span lang="lv">Izveidot jaunu lokaciju</span>
            </a> -->
            <!-- <a href="/addNewCategory" class="btn btn-primary">
                <span lang="en">Add a new category</span>
                <span lang="lv">Izveidot jaunu kategoriju</span>
            </a> -->
        @endif
        <a href="/placelist" class="btn btn-primary">
            <span lang="en">View all places</span>
            <span lang="lv">Atradit visas lokacijas</span>
        </a>
        <a href="/categorylist" class="btn btn-primary">
            <span lang="en">View all categories</span>
            <span lang="lv">Atradit visas kategorijas</span>
        </a>
    </div>
</div>
@endsection