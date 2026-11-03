<!-- Mobile toggle button -->
<button class="btn btn-outline-primary d-md-none w-100 mb-2" data-bs-toggle="collapse" data-bs-target="#innerNavMenu"
    aria-expanded="false" aria-controls="innerNavMenu">
    <i class="feather-menu me-1"></i> {{ t('app.menu') }}
</button>

<!-- Collapsible inner nav -->
<div id="innerNavMenu" class="collapse d-md-block">
    <div class="inner-nav">
        <h6 class="inner-nav-title text-center">{{ t('app.product.category') }}</h6>

        <div class="inner-nav-list">
            <a href="{{ route('viewCategory', ['slug' => $data['category']->slug]) }}"
                class="inner-nav-item {{ request()->routeIs('categories','categoriesSearch','viewCategory', 'editCategory') ? 'active' : '' }}">
                <i data-feather="globe"></i>
                {{ t('app.product.category') }}
            </a>

            <a href="{{ route('subCategories', ['slug' => $data['category']->slug]) }}"
                class="inner-nav-item {{ request()->routeIs('subCategories','subCategoriesSearch', 'viewSubCategory', 'editSubCategory') ? 'active' : '' }}">
                <i data-feather="map"></i>
                {{ t('app.product.subCategories') }}
            </a>
        </div>
    </div>
</div>
