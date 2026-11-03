<script>
    function addDiscountFormModal(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.addNew') }} :: {{ t('app.payment.discount') }}",
            showCloseButton: true,
            showConfirmButton: false, // no SweetAlert confirm button
            customClass: {
                popup: sizeClass
            },
            html: `
            <form id="swalForm" method="POST" action="{{ route('createDiscountSubmit') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ t('app.payment.discountName') }}<span class="text-danger">*</span></label>
                    <input type="text" name="discount_name" class="form-control text-center" value="{{ old('discount_name') }}" required>
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('discount_name') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ t('app.payment.discountMethod') }}<span class="text-danger">*</span></label>
                   <select class="form-select text-center" name="discount_method" required>
                    <option disabled selected>
                        - {{ t('app.select') }} -
                    </option>
                    <option value="fixed_amount">{{ t('app.payment.fixedAmount') }}</option>
                    <option value="percentage">{{ t('app.payment.percentage') }}</option>
                </select>

                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('discount_method') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ t('app.payment.discountRate') }}<span class="text-danger">*</span></label>
                    <input type="text" name="discount_rate" class="form-control text-center" value="{{ old('discount_rate') }}" required>
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('discount_rate') }}</p>
                </div>

                <div class="mb-3">
                        <label class="form-label">
                            {{ t('app.default') }}
                            <span class="text-danger">*</span>
                        </label>

                        <select name="is_default" class="form-select text-center" required>
                            <option value="1" {{ old('is_default', $item->is_default ?? 0) == 1 ? 'selected' : '' }}>
                                {{ t('app.status.true') }}
                            </option>
                            <option value="0" {{ old('is_default', $item->is_default ?? 0) == 0 ? 'selected' : '' }}>
                                {{ t('app.status.false') }}
                            </option>
                        </select>

                        <p style="color: red; font-size: 12px; margin-top: 5px;">
                            {{ $errors->first('is_default') }}
                        </p>
                    </div>

                <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
            </form>
        `
        });
    }

    // Auto-open modal if there are validation errors
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            addDiscountFormModal();
        @endif
    });
</script>

