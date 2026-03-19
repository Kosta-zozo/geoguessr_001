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
            <div class="p-3 border">
                <h3>
                    <span lang="en">Registration</span>
                    <span lang="lv">Registracija</span>
                </h3>
                <form action="/register" method="post" onsubmit="return checkForm()">
                    @csrf
                	<label for="name">
                        <span lang="en">Enter login:</span>
                        <span lang="lv">Ievadi loginu:</span>
                    </label><br>
                	<input type="text" class="form-control" id="name" name="name"><br>
                	<label for="password">
                        <span lang="en">Enter password:</span>
                        <span lang="lv">Ievadi paroli:</span>
                    </label><br>
                	<input type="password" class="form-control" id="password" name="password"><br>
                	<label for="password">
                        <span lang="en">Enter same password:</span>
                        <span lang="lv">Ievadi to pašu paroli:</span>
                    </label><br>
                	<input type="password" class="form-control" id="repassword" name="repassword">
                    <p id="alertWrongPassword" class="text-danger" style="display: none;">
                        <span lang="en">The password is not the same!</span>
                        <span lang="lv">Paroles nav vienadas</span>
                    </p><br>
                	<input type="submit" class="btn btn-outline-primary" value="Register">
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function checkForm() {
        if (document.getElementById("password").value == document.getElementById("repassword").value)
        {
            return true;
        }
        else
        {
            document.getElementById("alertWrongPassword").style.display = "block";
            return false;
        }
    }
</script>
@endsection
@section ('sidebar')
    <!-- No sidebar for login page -->
@endsection
@if(session('message'))
<div class="alert alert-warning">
    {{session('message')}}
</div>
@endif