@extends('layouts.app')

@section('content')

<div class="text-center">
    <div class="w-300">
        <ol>
            @foreach ($resultArray as $result)
            <li>
                <p>Result: {{ $result[0] }}</p>
                <p>Time spent: {{ $result[1] }}</p>
            </li>
            @endforeach
        </ol>
    </div>
</div>

@endsection