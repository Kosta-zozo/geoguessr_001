<div class="container">
    <header class="d-flex flex-wrap justify-content-center py-3 border-bottom">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
            <svg class="bi me-2" width="40" height="32">
                <use xlink:href="#bootstrap"></use>
            </svg>
            <span class="fs-4">Geolocation guesser</span>
        </a>
        <ul class="nav nav-pills">
            <li class="nav-item"><a href="/home" class="nav-link @if (Request::is('home') || Request::is('/')) active @endif" aria-current="page">Home</a></li>
            @if(!Auth::user())
                <li class="nav-item"><a href="/loginform" class="nav-link @if (Request::is('loginform') || Request::is('registerform')) active @endif">Login</a></li>
            @else
                <li class="nav-item"><a href="/game" class="nav-link @if (Request::is('game')) active @endif">Game</a></li>
                <li class="nav-item"><a href="/adminpanel" class="nav-link fst-italic @if (Request::is('adminpanel')) active @endif">{{ Auth::user()->name }}</a></li>
                <li class="nav-item"><a href="/logout" class="nav-link">Logout</a></li>
            @endif
        </ul>
    </header>
</div>