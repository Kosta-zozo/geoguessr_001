@extends('layouts.app')

@section('content')

<div class="text-center">
    <div class="w-300">
        <ol>
            @foreach ($resultArray as $result)
            <li>
                <p>
                    <span lang="en">Result: </span>
                    <span lang="lv">Rezultats: </span>
                    {{ $result[0] }}
                </p>
                <p>
                    <span lang="en">Time spent: </span>
                    <span lang="lv">Pateretais laiks: </span>
                    {{ $result[1] }}
                </p>
            </li>
            @endforeach
        </ol>
        <a href="/gameHub" class="btn btn-primary">
            <span lang="en">Return to game hub</span>
            <span lang="lv">Atgriezties spēļu centrā</span>
        </a>
    </div>
</div>

@endsection