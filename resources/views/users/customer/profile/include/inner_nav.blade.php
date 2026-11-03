<!-- Mobile toggle button -->
<button class="btn btn-outline-primary d-md-none w-100 mb-4" data-bs-toggle="collapse" data-bs-target="#innerNavMenu"
    aria-expanded="false" aria-controls="innerNavMenu">
    <i class="fas fa-bars me-2"></i> {{ t('app.menu') }}
</button>

<!-- Collapsible inner nav -->
<div id="innerNavMenu" class="collapse d-md-block">
    <div class="inner-nav">
        <h6 class="inner-nav-title text-center">{{ t('app.user.profile') }}</h6>

        <div class="inner-nav-list">
            <a href="{{ route('customerMyProfile') }}"
                class="inner-nav-item {{ Str::startsWith(request()->path(), 'customer/profile') ? 'active' : '' }}">
                <i class="fas fa-user me-1"></i>
                {{ t('app.user.myProfile') }}
            </a>

            <a href="
            {{-- {{ route('productVariations', ['slug' => $data['product']->slug]) }} --}}
             "
                class="inner-nav-item {{ request()->routeIs('productVariations', 'addProductVariant', 'viewProductVariant', 'editProductVariant') ? 'active' : '' }}">

                <i data-feather="map"></i>
                {{ t('app.product.quotes') }}
            </a>
        </div>
    </div>
</div>
