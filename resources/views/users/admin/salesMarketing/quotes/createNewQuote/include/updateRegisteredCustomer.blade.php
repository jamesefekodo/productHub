<script>
function addRegisteredCustomerFormModal(sizeClass = 'swal-size-md mx-5') {
    Swal.fire({
        title: "{{ t('app.buttons.update') }} :: {{ t('app.sales.updateCustomer') }}",
        showCloseButton: true,
        showConfirmButton: false, // no SweetAlert confirm button
        customClass: {
            popup: sizeClass
        },
        html: `
            <form id="" method="POST" action="{{ route('updateRegisteredCustomerSubmit', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">{{ t('app.sales.customer') }}</label>
                    <input type="text" name="customer" class="form-control text-center" value="{{ $data['existingTemporaryQuote']->customer }}">
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('customer') }}</p>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
            </form>
        `
    });
}

// Auto-open modal if there are validation errors
document.addEventListener('DOMContentLoaded', function() {
    @if($errors->any())
        addRegisteredCustomerFormModal();
    @endif
});
</script>

