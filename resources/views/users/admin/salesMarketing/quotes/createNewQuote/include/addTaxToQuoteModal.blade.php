<script>
    function addTaxToQuoteModal(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.addNew') }} :: {{ t('app.payment.tax') }}",
            showCloseButton: true,
            showConfirmButton: false, // no SweetAlert confirm button
            customClass: {
                popup: sizeClass
            },
            html: `
            <form id="swalForm" method="POST" action="{{ route('addTaxToQuote', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ t('app.payment.tax') }}<span class="text-danger">*</span></label>
                    <select class="form-select text-center" name="tax" required>
                        <option disabled selected>
                            - {{ t('app.select') }} -
                        </option>
                        @if ($data['activeTaxes']->isNotEmpty())
                            @foreach ($data['activeTaxes'] as $tax)
                                <option value="{{ $tax->id }}">
                                    {{ $tax->tax_name }}
                                    => @switch($tax->tax_method)
                                        @case('percentage')
                                            {{ $tax->tax_rate }}%
                                        @break

                                        @case('fixed_amount')
                                            ₦{{ number_format($tax->tax_rate, 2) }}
                                        @break

                                        @default
                                    @endswitch
                                </option>
                            @endforeach
                        @endif
                    </select>

                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('tax') }}</p>
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

