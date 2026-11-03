<script>
    function editBankAccountFormModal{{ $account->id }}(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.buttons.edit') }} :: {{ t('app.payment.bankAccount') }}",
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: sizeClass
            },
            html: `
                <form id="swalForm" method="POST" action="{{ route('updateBankAccountSubmit', ['id' => $account->id]) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">{{ t('app.payment.bankName') }}<span class="text-danger">*</span></label>
                        <input type="text" name="bank_name" class="form-control text-center" value="{{ $account->bank_name }}" required>
                        <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('bank_name') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ t('app.payment.accountNumber') }}<span class="text-danger">*</span></label>
                        <input type="text" name="account_number" class="form-control text-center" value="{{ $account->account_number }}" required>
                        <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('account_number') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ t('app.payment.accountName') }}<span class="text-danger">*</span></label>
                        <input type="text" name="account_name" class="form-control text-center" value="{{ $account->account_name }}" required>
                        <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('account_name') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            {{ t('app.payment.currency') }}
                            <span class="text-danger">*</span>
                        </label>

                        <select name="currency" class="form-select text-center" required>
                            <option value="NGN" {{ $account->currency == 'ngn' ? 'selected' : '' }}>NGN - Nigerian Naira</option>
                            <option value="USD" {{ $account->currency == 'usd' ? 'selected' : '' }}>USD - US Dollar</option>
                            <option value="EUR" {{ $account->currency == 'eur' ? 'selected' : '' }}>EUR - Euro</option>
                        </select>

                        <p style="color: red; font-size: 12px; margin-top: 5px;">
                            {{ $errors->first('currency') }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ t('app.status.status') }}<span class="text-danger">*</span></label>
                        <select class="form-select text-center" name="is_active" required>
                            <option selected disabled>
                                -{{ t('app.select') }}-
                            </option>

                            <option value="1" {{ $account->is_active == 1 ? 'selected' : '' }}>
                                {{ t('app.status.active') }}
                            </option>

                            <option value="0" {{ $account->is_active == 0 ? 'selected' : '' }}>
                                {{ t('app.status.inactive') }}
                            </option>
                        </select>
                        <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('is_active') }}</p>
                    </div>




                    <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
                </form>
            `
        });
    }
</script>
