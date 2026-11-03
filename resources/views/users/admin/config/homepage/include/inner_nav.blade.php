<!-- Mobile toggle button -->
<button class="btn btn-outline-primary d-md-none w-100 mb-2" data-bs-toggle="collapse" data-bs-target="#innerNavMenu"
    aria-expanded="false" aria-controls="innerNavMenu">
    <i class="feather-menu me-1"></i> {{ t('app.menu') }}
</button>

<!-- Collapsible inner nav -->
<div id="innerNavMenu" class="collapse d-md-block">
    <div class="inner-nav">
        <h6 class="inner-nav-title text-center">{{ t('app.settings.homePageConfig') }}</h6>

        <div class="inner-nav-list">
            <a href="{{ route('homePageSliderConfig') }}"
                class="inner-nav-item {{ Str::startsWith(request()->path(), 'admin/settings/homepage/slider') ? 'active' : '' }}">
                <i data-feather="globe"></i>
                {{ t('app.settings.homePageSlider') }}
            </a>

            <a href="{{ route('contacts') }}"
                class="inner-nav-item {{ request()->routeIs('contacts') ? 'active' : '' }}">

                <i data-feather="map"></i>
                {{ t('app.settings.address.contacts') }}
            </a>
        </div>
    </div>
</div>
