<script>
function addNonRegisteredCustomerFormModal(sizeClass = 'swal-size-md mx-5') {
    Swal.fire({
        title: "{{ t('app.buttons.update') }} :: {{ t('app.sales.updateCustomer') }}",
        showCloseButton: true,
        showConfirmButton: false, // no SweetAlert confirm button
        customClass: {
            popup: sizeClass
        },
        html: `
            <form id="swalForm" method="POST" action="{{ route('updateNonRegisteredCustomerSubmit', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">{{ t('app.sales.customer') }}</label>
                    <input type="text" name="customer_name" class="form-control text-center" value="{{ $data['existingTemporaryQuote']->customer_name }}" required>
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('customer_name') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ t('auth.phone') }}</label>
                    <input type="text" name="customer_phone" class="form-control text-center" value="{{ $data['existingTemporaryQuote']->customer_phone }}" required>
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('customer_phone') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ t('auth.email') }}</label>
                    <input type="email" name="customer_email" class="form-control text-center" value="{{ $data['existingTemporaryQuote']->customer_email }}" required>
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('customer_email') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ t('app.settings.address.address') }}</label>
                    <input type="text" name="customer_address" class="form-control text-center" value="{{ $data['existingTemporaryQuote']->customer_address }}" required>
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('customer_address') }}</p>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
            </form>
        `
    });
}

// Auto-open modal if there are validation errors
document.addEventListener('DOMContentLoaded', function() {
    @if($errors->any())
        addNonRegisteredCustomerFormModal();
    @endif
});
</script>

{{--
customer_email
customer_phone
customer_address --}}
