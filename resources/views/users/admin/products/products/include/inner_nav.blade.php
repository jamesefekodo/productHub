<!-- Mobile toggle button -->
<button class="btn btn-outline-primary d-md-none w-100 mb-2" data-bs-toggle="collapse" data-bs-target="#innerNavMenu"
    aria-expanded="false" aria-controls="innerNavMenu">
    <i class="feather-menu me-1"></i> {{ t('app.menu') }}
</button>

<!-- Collapsible inner nav -->
<div id="innerNavMenu" class="collapse d-md-block">
    <div class="inner-nav">
        <h6 class="inner-nav-title text-center">{{ t('app.product.product') }}</h6>

        <div class="inner-nav-list">
            <a href="{{ route('viewProduct', ['slug' => $data['product']->slug]) }}"
                class="inner-nav-item {{ request()->routeIs('products', 'productsSearch', 'viewProduct', 'editProduct') ? 'active' : '' }}">
                <i data-feather="globe"></i>
                {{ t('app.product.product') }}
            </a>

            <a href="{{ route('productVariations', ['slug' => $data['product']->slug]) }}"
                class="inner-nav-item {{ request()->routeIs('productVariations', 'addProductVariant','viewProductVariant', 'editProductVariant') ? 'active' : '' }}">

                <i data-feather="map"></i>
                {{ t('app.product.variations') }}
            </a>
        </div>
    </div>
</div>
