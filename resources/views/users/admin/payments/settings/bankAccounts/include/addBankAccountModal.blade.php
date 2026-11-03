<script>
function addBankAccountFormModal(sizeClass = 'swal-size-md mx-5') {
    Swal.fire({
        title: "{{ t('app.addNew') }} :: {{ t('app.payment.bankAccount') }}",
        showCloseButton: true,
        showConfirmButton: false, // no SweetAlert confirm button
        customClass: {
            popup: sizeClass
        },
        html: `
            <form id="swalForm" method="POST" action="{{ route('createNewBankAccount') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ t('app.payment.bankName') }}<span class="text-danger">*</span></label>
                    <input type="text" name="bank_name" class="form-control text-center" value="{{ old('bank_name') }}" required>
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('bank_name') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ t('app.payment.accountNumber') }}<span class="text-danger">*</span></label>
                    <input type="text" name="account_number" class="form-control text-center" value="{{ old('account_number') }}" required>
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('account_number') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ t('app.payment.accountName') }}<span class="text-danger">*</span></label>
                    <input type="text" name="account_name" class="form-control text-center" value="{{ old('account_name') }}" required>
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('account_name') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">
                        {{ t('app.payment.currency') }}
                        <span class="text-danger">*</span>
                    </label>

                    <select name="currency" class="form-select text-center" required>
                        <option value="NGN" {{ old('currency') == 'ngn' ? 'selected' : '' }}>NGN - Nigerian Naira</option>
                        <option value="USD" {{ old('currency') == 'usd' ? 'selected' : '' }}>USD - US Dollar</option>
                        <option value="EUR" {{ old('currency') == 'eur' ? 'selected' : '' }}>EUR - Euro</option>
                    </select>

                    <p style="color: red; font-size: 12px; margin-top: 5px;">
                        {{ $errors->first('currency') }}
                    </p>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
            </form>
        `
    });
}

// Auto-open modal if there are validation errors
document.addEventListener('DOMContentLoaded', function() {
    @if($errors->any())
        addBankAccountFormModal();
    @endif
});
</script>
