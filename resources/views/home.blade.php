@extends ('layouts.app')
@section ('content')    
<div class="px-4 py-5 my-5 text-center">
    <h1 class="display-5 fw-bold text-body-emphasis">
        <span lang="en">Geolocation guesser</span>
        <span lang="lv">Geolokaciju minetajs</span>
    </h1>
    <div class="col-lg-6 mx-auto">
        <p class="lead mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed nec consectetur lectus. Duis non pulvinar est. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nulla mattis ipsum ac lectus tempus, eget posuere massa placerat. Nulla facilisis molestie leo at tristique.</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a href="/gameHub" type="button" class="btn btn-primary btn-lg px-4 gap-3 rounded-0" style="background-color: #46769B; border: 0;">
                <span lang="en">Start game</span>
                <span lang="lv">Uzsakt speli</span>
            </a>
        </div>
        <br>
        <img src="/public/leaflet_image_000.webp" alt="image not found">
    </div>
</div>
@endsection