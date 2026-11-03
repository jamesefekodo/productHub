<div class="mt-3 mb-3">
    @php
        $subTotal = $data['existingTemporaryQuote']->items->sum(function ($item) {
            return $item->unit_price * $item->quantity;
        });
    @endphp

    @php
        $totalDiscount = 0;
        if ($data['existingTemporaryQuote']->discounts->isNotEmpty()) {
            foreach ($data['existingTemporaryQuote']->discounts as $discount) {
                if ($discount->discount->discount_method == 'percentage') {
                    $totalDiscount += ($discount->discount->discount_rate / 100) * $subTotal;
                }
                if ($discount->discount->discount_method == 'fixed_amount') {
                    $totalDiscount += $discount->discount->discount_rate;
                }
            }
        }
    @endphp

    @php
        $balanceAfterDiscount = max(0, $subTotal - $totalDiscount);
        $totalTax = 0;
        foreach ($data['existingTemporaryQuote']->taxes ?? [] as $tax) {
            if ($tax->tax->tax_method === 'percentage') {
                $totalTax += ($tax->tax->tax_rate / 100) * $balanceAfterDiscount;
            } elseif ($tax->tax->tax_method === 'fixed_amount') {
                $totalTax += $tax->tax->tax_rate;
            }
        }
    @endphp

    @php
        $grandTotal = $balanceAfterDiscount + $totalTax;
    @endphp

    {{-- Summary Card --}}
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">

        {{-- Subtotal --}}
        <div class="d-flex justify-content-between align-items-center px-4 py-3 bg-soft-primary text-primary border-bottom">
            <div class="d-flex align-items-center gap-2">
                <i class="ri-list-check-2 fs-5"></i>
                <span class="fw-semibold">{{ t('app.sales.subTotal') }}</span>
            </div>
            <span class="fw-bold fs-5">₦{{ number_format($subTotal, 2) }}</span>
        </div>

        {{-- Discount --}}
        <div class="d-flex justify-content-between align-items-center px-4 py-3 bg-soft-warning text-warning border-bottom">
            <div class="d-flex align-items-center gap-2">
                <i class="ri-coupon-3-line fs-5"></i>
                <span class="fw-semibold">{{ t('app.payment.discounts') }}</span>
            </div>
            <span class="fw-bold fs-5">
                - ₦{{ number_format($totalDiscount, 2) }}
            </span>
        </div>

        {{-- Tax --}}
        <div class="d-flex justify-content-between align-items-center px-4 py-3 bg-soft-danger text-danger border-bottom">
            <div class="d-flex align-items-center gap-2">
                <i class="ri-percent-line fs-5"></i>
                <span class="fw-semibold">{{ t('app.payment.taxes') }}</span>
            </div>
            <span class="fw-bold fs-5">
                + ₦{{ number_format($totalTax, 2) }}
            </span>
        </div>

        {{-- Grand Total --}}
        <div class="d-flex justify-content-between align-items-center px-4 py-3 bg-dark text-white">
            <div>
                <div class="fw-bold fs-6">{{ t('app.payment.grandTotal') }}</div>
                <div class="opacity-75" style="font-size: 0.72rem;">
                    ({{ t('app.sales.subTotal') }} − {{ t('app.payment.discounts') }}) + {{ t('app.payment.tax') }}
                </div>
            </div>
            <span class="fw-bold fs-4">₦{{ number_format($grandTotal, 2) }}</span>
        </div>

    </div>

    {{-- Mobile stacked view for very small screens --}}
    <style>
        @media (max-width: 400px) {
            .summary-row {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 4px;
            }
            .summary-row span.fw-bold {
                align-self: flex-end;
            }
        }
    </style>

</div>
