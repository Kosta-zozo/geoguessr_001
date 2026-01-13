@extends ('layouts.app')
@section ('content')
<div class="px-4 py-5 my-5 text-center">
    <div class="col-lg-3 mx-auto">
        <div>
            @if ($errors->any())
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger">
                        {{$error}} <br>
                    </div>
                @endforeach
            @endif

            <br>
            <div class="p-3 border border-secondary">
                <h3>Login</h3>
                <form action="/login" method="post">
                    @csrf
                	<label for="name">Enter login:</label><br>
                	<input type="text" class="form-control" name="name"><br>
                	<label for="password">Enter password:</label><br>
                	<input type="password" class="form-control" name="password"><br>
                	<input type="submit" class="btn btn-outline-primary" value="Login">
                </form>
                <a href="/registerform">You don't have an account?</a>
            </div>
        </div>
    </div>
</div>
@endsection
@section ('sidebar')
    <!-- No sidebar for login page -->
@endsection
@if(session('message'))
<div class="alert alert-warning">
    {{session('message')}}
</div>
@endif