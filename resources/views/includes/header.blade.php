<div class="container">
    <header class="d-flex flex-wrap justify-content-center py-3 border-bottom">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
            <svg class="bi me-2" width="40" height="32">
                <use xlink:href="#bootstrap"></use>
            </svg>
            <span class="fs-4">Geolocation guesser</span>
        </a>
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a href="/home" class="nav-link @if (Request::is('home') || Request::is('/')) active @endif" aria-current="page">
                    <span lang="en">Home</span>
                    <span lang="lv">Sakums</span>
                </a>
            </li>
            @if(!Auth::user())
                <li class="nav-item"><a href="/loginform" class="nav-link @if (Request::is('loginform') || Request::is('registerform')) active @endif">
                    <span lang="en">Login</span>
                    <span lang="lv">Ielogoties</span>
                </a></li>
            @else
                <li class="nav-item"><a href="/gameHub" class="nav-link @if (Request::is('gameHub') || Request::is('game') || Request::is('submitResult') || Request::is('gameStartSerie') || Request::is('gameContinueSerie')) active @endif">
                    <span lang="en">Game</span>
                    <span lang="lv">Spele</span>
                </a></li>
                <li class="nav-item"><a href="/adminPanel" class="nav-link fst-italic @if (Request::is('adminPanel') || Request::is('addNewPlace') || Request::is('placelist') || Request::is('addNewCategory')) active @endif">{{ Auth::user()->name }}</a></li>
                <li class="nav-item"><a href="/logout" class="nav-link">
                    <span lang="en">Logout</span>
                    <span lang="lv">Izlogoties</span>
                </a></li>
            @endif
            <select id="langSelector" onchange="switchLanguage()" class="form-select form-select-sm" style="width:auto; background: none; padding:10px;">
                <option value="en" selected>EN</option>
                <option value="lv">LV</option>
            </select>
        </ul>
    </header>
</div>

<script>
    document.documentElement.setAttribute('lang', sessionStorage.getItem("language"));
    langSelector.value = sessionStorage.getItem("language");
    function switchLanguage(){
        document.documentElement.setAttribute('lang', langSelector.value);
        sessionStorage.setItem("language", langSelector.value);
    }
</script>