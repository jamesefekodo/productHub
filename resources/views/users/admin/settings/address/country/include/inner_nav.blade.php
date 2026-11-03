<!-- Mobile toggle button -->
<button class="btn btn-outline-primary d-md-none w-100 mb-2" data-bs-toggle="collapse" data-bs-target="#innerNavMenu"
    aria-expanded="false" aria-controls="innerNavMenu">
    <i class="feather-menu me-1"></i> {{ t('app.menu') }}
</button>

<!-- Collapsible inner nav -->
<div id="innerNavMenu" class="collapse d-md-block">
    <div class="inner-nav">
        <h6 class="inner-nav-title text-center">{{ t('app.settings.address.address') }}</h6>

        <div class="inner-nav-list">
            <a href="{{ route('countries') }}" class="inner-nav-item {{ Str::startsWith(request()->path(), 'admin/settings/address/countries') ? 'active' : '' }}">
                <i data-feather="globe"></i>
                {{ t('app.settings.address.countries') }}
            </a>

            <a href="{{ route('states') }}" class="inner-nav-item {{ Str::startsWith(request()->path(), 'admin/settings/address/states') ? 'active' : '' }}">
                <i data-feather="map"></i>
                {{ t('app.settings.address.states') }}
            </a>

            <a href="{{ route('lgas') }}" class="inner-nav-item {{ Str::startsWith(request()->path(), 'admin/settings/address/lga') ? 'active' : '' }}">
                <i data-feather="map-pin"></i>
                {{ t('app.settings.address.lga') }}
            </a>
        </div>
    </div>
</div>
