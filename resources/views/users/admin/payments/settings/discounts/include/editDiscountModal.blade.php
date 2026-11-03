<script>
    function editDiscountFormModal{{ $discount->id }}(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.buttons.edit') }} :: {{ t('app.payment.discount') }}",
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: sizeClass
            },
            html: `
                <form id="swalForm" method="POST" action="{{ route('updateDiscountSubmit', ['id' => $discount->id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="row col-md-12">
                        <div class="mb-3 col-md-12">
                            <label class="form-label">{{ t('app.payment.discountName') }}<span class="text-danger">*</span></label>
                            <input type="text" name="discount_name" class="form-control text-center" value="{{ $discount->discount_name }}" required>
                            <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('discount_name') }}</p>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label">{{ t('app.payment.discountMethod') }}<span class="text-danger">*</span></label>
                        <select class="form-select text-center" name="discount_method" required>
                            <option disabled selected>
                                - {{ t('app.select') }} -
                            </option>
                            <option value="fixed_amount" {{ $discount->discount_method == "fixed_amount" ? 'selected' : '' }}>{{ t('app.payment.fixedAmount') }}</option>
                            <option value="percentage" {{ $discount->discount_method == "percentage" ? 'selected' : '' }}>{{ t('app.payment.percentage') }}</option>
                        </select>

                            <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('discount_method') }}</p>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label">{{ t('app.payment.discountRate') }}<span class="text-danger">*</span></label>
                            <input type="text" name="discount_rate" class="form-control text-center" value="{{ $discount->discount_rate }}" required>
                            <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('discount_rate') }}</p>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">
                                {{ t('app.default') }}
                                <span class="text-danger">*</span>
                            </label>

                            <select name="is_default" class="form-select text-center" required>
                                <option value="1" {{ $discount->is_default == 1 ? 'selected' : '' }}>
                                    {{ t('app.status.true') }}
                                </option>
                                <option value="0" {{ $discount->is_default == 0 ? 'selected' : '' }}>
                                    {{ t('app.status.false') }}
                                </option>
                            </select>

                            <p style="color: red; font-size: 12px; margin-top: 5px;">
                                {{ $errors->first('is_default') }}
                            </p>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ t('app.status.status') }}<span class="text-danger">*</span></label>
                            <select class="form-select text-center" name="is_active" required>
                                <option selected disabled>
                                    -{{ t('app.select') }}-
                                </option>

                                <option value="1" {{ $discount->is_active == 1 ? 'selected' : '' }}>
                                    {{ t('app.status.active') }}
                                </option>

                                <option value="0" {{ $discount->is_active == 0 ? 'selected' : '' }}>
                                    {{ t('app.status.inactive') }}
                                </option>
                            </select>
                            <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('is_active') }}</p>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
                </form>
            `
        });
    }
</script>
