<script>
    function editTaxFormModal{{ $tax->id }}(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.buttons.edit') }} :: {{ t('app.payment.tax') }}",
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: sizeClass
            },
            html: `
                <form id="swalForm" method="POST" action="{{ route('updateTaxSubmit', ['id' => $tax->id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="row col-md-12">
                        <div class="mb-3 col-md-12">
                            <label class="form-label">{{ t('app.payment.taxName') }}<span class="text-danger">*</span></label>
                            <input type="text" name="tax_name" class="form-control text-center" value="{{ $tax->tax_name }}" required>
                            <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('tax_name') }}</p>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label">{{ t('app.payment.taxMethod') }}<span class="text-danger">*</span></label>
                        <select class="form-select text-center" name="tax_method" required>
                            <option disabled selected>
                                - {{ t('app.select') }} -
                            </option>
                            <option value="fixed_amount" {{ $tax->tax_method == "fixed_amount" ? 'selected' : '' }}>{{ t('app.payment.fixedAmount') }}</option>
                            <option value="percentage" {{ $tax->tax_method == "percentage" ? 'selected' : '' }}>{{ t('app.payment.percentage') }}</option>
                        </select>

                            <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('tax_method') }}</p>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label">{{ t('app.payment.taxRate') }}<span class="text-danger">*</span></label>
                            <input type="text" name="tax_rate" class="form-control text-center" value="{{ $tax->tax_rate }}" required>
                            <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('tax_rate') }}</p>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">
                                {{ t('app.default') }}
                                <span class="text-danger">*</span>
                            </label>

                            <select name="is_default" class="form-select text-center" required>
                                <option value="1" {{ $tax->is_default == 1 ? 'selected' : '' }}>
                                    {{ t('app.status.true') }}
                                </option>
                                <option value="0" {{ $tax->is_default == 0 ? 'selected' : '' }}>
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

                                <option value="1" {{ $tax->is_active == 1 ? 'selected' : '' }}>
                                    {{ t('app.status.active') }}
                                </option>

                                <option value="0" {{ $tax->is_active == 0 ? 'selected' : '' }}>
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
