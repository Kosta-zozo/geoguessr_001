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
                    {{ floor($result[0]) }}
                    <span lang="en"> points</span>
                    <span lang="lv"> punkti</span>
                </p>
                <p>
                    <span lang="en">Time spent: </span>
                    <span lang="lv">Pateretais laiks: </span>
                    {{ $result[1] }}
                    <span lang="en"> (in seconds)</span>
                    <span lang="lv"> (sekundes)</span>
                </p>
            </li>
            <hr>
            @endforeach
        </ol>
        <h3>
            <span lang="en">Serie result: </span>
            <span lang="lv">Sērijas rezultāts: </span>
            <span id="serieresult">5555</span>
            <span lang="en"> points</span>
            <span lang="lv"> punkti</span>
        </h3>
        <a href="/gameHub" class="btn btn-primary">
            <span lang="en">Return to game hub</span>
            <span lang="lv">Atgriezties spēļu centrā</span>
        </a>
    </div>
</div>

<script>
    var points = 0;
    @foreach ($resultArray as $result)
        points += {{ floor($result[0]) }};
    @endforeach
    serieresult.innerHTML = points;
</script>

@endsection