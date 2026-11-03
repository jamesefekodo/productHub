<!-- Mobile toggle button -->
<button class="btn btn-outline-primary d-md-none w-100 mb-2" data-bs-toggle="collapse" data-bs-target="#innerNavMenu"
    aria-expanded="false" aria-controls="innerNavMenu">
    <i class="feather-menu me-1"></i> {{ t('app.payment.paymentSettings') }}
</button>

<!-- Collapsible inner nav -->
<div id="innerNavMenu" class="collapse d-md-block">
    <div class="inner-nav">
        <h6 class="inner-nav-title text-center">{{ t('app.payment.paymentSettings') }}</h6>

        <div class="inner-nav-list">
            <a href="{{ route('paymentMethods') }}"
                class="inner-nav-item {{ Str::startsWith(request()->path(), 'admin/payment/setting/payment_methods') ? 'active' : '' }}">
                <i data-feather="credit-card"></i>
                {{ t('app.payment.paymentMethods') }}
            </a>

            <a href="{{ route('bankAccounts') }}"
                class="inner-nav-item {{ Str::startsWith(request()->path(), 'admin/payment/setting/bank_accounts') ? 'active' : '' }}">
                <i data-feather="credit-card"></i>
                {{ t('app.payment.bankAccounts') }}
            </a>

            <a href="{{ route('taxes') }}"
                class="inner-nav-item {{ Str::startsWith(request()->path(), 'admin/payment/setting/taxes') ? 'active' : '' }}">
                <i data-feather="percent"></i>
                {{ t('app.payment.taxes') }}
            </a>

            <a href="{{ route('discounts') }}"
                class="inner-nav-item {{ Str::startsWith(request()->path(), 'admin/payment/setting/discounts') ? 'active' : '' }}">
                <i data-feather="tag"></i>
                {{ t('app.payment.discounts') }}
            </a>
        </div>
    </div>
</div>
