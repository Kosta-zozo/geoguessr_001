@extends('layouts.app')

@section('content')
<style>
    .btn-primary, .bg-primary {
        background-color: #46769B !important;
        border-color: #46769B;
        color: white;
    }
    .btn-primary:hover {
        background-color: #2F5F8A !important;
        border-color: #2F5F8A;
    }
</style>

<div class="text-center">
    <div style="width:400px; margin:auto;">
        <br>
        <br>
        <br>
        <a href="/gameHub" class="btn btn-primary rounded-0">
            <span lang="en">Return to game hub</span>
            <span lang="lv">Atgriezties spēļu centrā</span>
        </a>
        <br>
        <br>
        <h3>
            <!-- <span lang="en">Serie result: </span>
            <span lang="lv">Sērijas rezultāts: </span> -->
            <span lang="en">Final score: </span>
            <span lang="lv">Finalais rezultāts: </span>
        </h3>
        <h1>
            <span id="serieresult">5555</span>
            <span lang="en"> points</span>
            <span lang="lv"> punkti</span>
        </h1>
        <br>
        <br>
        <h3>Summary:</h3>
        @for ($i = 0; $i < count($resultArray); $i++)
            <div class="shadow bg-primary p-2">
                <h6>Round {{ $i+1 }} of 5</h6>
                <p>
                    <span lang="en">Result: </span>
                    <span lang="lv">Rezultats: </span>
                    {{ floor($resultArray[$i][0]) }}
                    <span lang="en"> points</span>
                    <span lang="lv"> punkti</span>
                </p>
                <p>
                    <span lang="en">Time spent: </span>
                    <span lang="lv">Pateretais laiks: </span>
                    {{ $resultArray[$i][1] }}
                    <span lang="en"> (in seconds)</span>
                    <span lang="lv"> (sekundes)</span>
                </p>
            </div>
        <hr>
        @endfor
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