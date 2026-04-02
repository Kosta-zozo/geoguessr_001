<style>
    .active{
        background-color: #5E8CAD !important;
    }
    header {
        background-color: #75A2BF;
    }
    .nav-link {
        color: white;
    }
    .nav-item :hover {
        color: rgb(230, 230, 230);
    }
</style>

<div>
    <header class="d-flex flex-wrap justify-content-center border-bottom">
        <a href="/" class="d-flex align-items-center me-md-auto text-dark text-decoration-none m-2">
            <svg class="bi me-2" width="40" height="32">
                <use xlink:href="#bootstrap"></use>
            </svg>
            <span class="fs-4 text-light">Geolocation guesser</span>
        </a>
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a @if (Request::is('*/gameStartSerie') || Request::is('gameContinueSerie')) onclick="return confirm(getExitMessage());" @endif href="/home" class="nav-link p-3 rounded-0 @if (Request::is('home') || Request::is('/')) active @endif" aria-current="page">
                    <span lang="en">Home</span>
                    <span lang="lv">Sakums</span>
                </a>
            </li>
            <li class="nav-item">
                <a @if (Request::is('*/gameStartSerie') || Request::is('gameContinueSerie')) onclick="return confirm(getExitMessage());" @endif href="/leaderboard" class="nav-link p-3 rounded-0 @if (Request::is('leaderboard')) active @endif" aria-current="page">
                    <span lang="en">Leaderboard</span>
                    <span lang="lv">Līderu saraksts</span>
                </a>
            </li>
            @if(Auth::user())
                <li class="nav-item me-2"><a @if (Request::is('*/gameStartSerie') || Request::is('gameContinueSerie')) onclick="return confirm(getExitMessage());" @endif href="/gameHub" class="nav-link p-3 rounded-0 @if (Request::is('*/gameStartSerie') || Request::is('gameHub') || Request::is('game') || Request::is('submitResult') || Request::is('gameStartSerie') || Request::is('gameContinueSerie')) active @endif">
                    <span lang="en">Game</span>
                    <span lang="lv">Spele</span>
                </a></li>
                <div class="dropdown me-5">
                    <button type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-outline-dark p-3 border-0 rounded-0 dropdown-toggle @if (Request::is('adminPanel') || Request::is('addNewPlace') || Request::is('placelist') || Request::is('categorylist')) || Request::is('addNewCategory')) active @endif">
                        {{ Auth::user()->name }}
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @if(Auth::user()->admin)
                        <a @if (Request::is('*/gameStartSerie') || Request::is('gameContinueSerie')) onclick="return confirm(getExitMessage());" @endif href="/categorylist" class="dropdown-item @if (Request::is('adminPanel') || Request::is('addNewPlace') || Request::is('placelist') || Request::is('categorylist')) || Request::is('addNewCategory')) active @endif">
                            <span lang="en">Admin panel</span>
                            <span lang="lv">Administracijas panelis</span>
                        </a>
                        <hr class="dropdown-divider">
                        @endif
                        <a onclick="return confirm(getLogoutMessage());" href="/logout" class="dropdown-item">
                            <span lang="en">Logout</span>
                            <span lang="lv">Izlogoties</span>
                        </a>
                    </div>
                </div>
            @else
                <li class="nav-item"><a href="/loginform" class="nav-link @if (Request::is('loginform') || Request::is('registerform')) active @endif">
                    <span lang="en">Login</span>
                    <span lang="lv">Ielogoties</span>
                </a></li>
            @endif
            <select id="langSelector" onchange="switchLanguage()" class="form-select form-select-sm me-5" style="width:auto; background: none; padding:5px; margin:auto;">
                <option value="en">EN</option>
                <option value="lv" selected>LV</option>
            </select>
        </ul>
    </header>
</div>

<script>
    if (sessionStorage.getItem("language") == null) 
        sessionStorage.setItem("language", langSelector.value);
    document.documentElement.setAttribute('lang', sessionStorage.getItem("language"));

    langSelector.value = sessionStorage.getItem("language");
    function switchLanguage(){
        document.documentElement.setAttribute('lang', langSelector.value);
        sessionStorage.setItem("language", langSelector.value);
    }

    function getExitMessage()
    {
        if(sessionStorage.getItem("language") == "lv")
            return "Vai tiešam gribat partraukt speli?";
        else
            return "Are you sure, you want to stop the game?";
    }
    function getLogoutMessage()
    {
        if(sessionStorage.getItem("language") == "lv")
            return "Vai tiešam gribat izlogoties?";
        else
            return "Are you sure, you want to logout?";
    }
</script>