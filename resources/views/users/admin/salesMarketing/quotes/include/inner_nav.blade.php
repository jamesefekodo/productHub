<!-- Mobile toggle button -->
<button class="btn btn-outline-primary d-md-none w-100 mb-2" data-bs-toggle="collapse" data-bs-target="#innerNavMenu"
    aria-expanded="false" aria-controls="innerNavMenu">
    <i class="feather-menu me-1"></i> {{ t('app.product.quotes') }}
</button>

<!-- Collapsible inner nav -->
<div id="innerNavMenu" class="collapse d-md-block">
    <div class="inner-nav">
        <h6 class="inner-nav-title text-center">{{ t('app.product.product') }}</h6>

        <div class="inner-nav-list">
            <a href="{{ route('quoteRequests') }}"
                class="inner-nav-item {{ Str::startsWith(request()->path(), 'admin/sales_marketing/quotes/requests') ? 'active' : '' }}">
                <i data-feather="globe"></i>
                {{ t('app.product.quoteRequests') }}
            </a>

            <a href="
            {{ route('draftedQuotes') }}
             "
                class="inner-nav-item {{ Str::startsWith(request()->path(), 'admin/sales_marketing/quotes/drafted_quotes') ? 'active' : '' }}">

                <i data-feather="map"></i>
                {{ t('app.product.draftedQuotes') }}
            </a>

            <a href="
            {{ route('quotes') }}
             "
                class="inner-nav-item {{ Str::startsWith(request()->path(), 'admin/sales_marketing/quotes/quotes') ? 'active' : '' }}">

                <i data-feather="map"></i>
                {{ t('app.product.quotes') }}
            </a>

            <a href="
            {{ route('quoteTermsAndConditions') }}
             "
                class="inner-nav-item {{ Str::startsWith(request()->path(), 'admin/sales_marketing/quotes/terms_and_conditions') ? 'active' : '' }}">

                <i data-feather="map"></i>
                {{ t('app.sales.termsAndConditions') }}
            </a>
        </div>
    </div>
</div>
