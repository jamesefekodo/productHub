<script>
    function addDiscountToQuoteModal(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.addNew') }} :: {{ t('app.payment.discount') }}",
            showCloseButton: true,
            showConfirmButton: false, // no SweetAlert confirm button
            customClass: {
                popup: sizeClass
            },
            html: `
            <form id="swalForm" method="POST" action="{{ route('addDiscountToQuote', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ t('app.payment.discount') }}<span class="text-danger">*</span></label>
                    <select class="form-select text-center" name="discount" required>
                        <option disabled selected>
                            - {{ t('app.select') }} -
                        </option>
                        @if ($data['activeDiscounts']->isNotEmpty())
                            @foreach ($data['activeDiscounts'] as $discount)
                                <option value="{{ $discount->id }}">
                                    {{ $discount->discount_name }}
                                    => @switch($discount->discount_method)
                                        @case('percentage')
                                            {{ $discount->discount_rate }}%
                                        @break

                                        @case('fixed_amount')
                                            ₦{{ number_format($discount->discount_rate, 2) }}
                                        @break

                                        @default
                                    @endswitch
                                </option>
                            @endforeach
                        @endif
                    </select>

                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('discount') }}</p>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
            </form>
        `
        });
    }

    // Auto-open modal if there are validation errors
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            addDiscountToQuoteModal();
        @endif
    });
</script>

