<!-- Button to open the modal manually -->
{{-- <button type="button" onclick="createNewQuoteWithRequestNoModal()" class="btn btn-primary">
    {{ t('app.addNew') }}
</button> --}}

<script>
function createNewQuoteWithRequestNoModal(sizeClass = 'swal-size-md mx-5') {
    Swal.fire({
        title: "{{ t('app.createNew') }} :: {{ t('app.product.quote') }}",
        showCloseButton: true,
        showConfirmButton: false, // no SweetAlert confirm button
        customClass: {
            popup: sizeClass
        },
        html: `
            <form id="swalForm" method="GET" action="{{ route('createQuoteSubmitRequestNo') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ t('app.sales.quoteRequestRef') }} <small class="text-danger">{{ t('app.sales.quoteRequestRefInputMessage') }}</small></label>
                    <input type="text" name="request_slug" class="form-control text-center" value="{{ old('request_slug') }}">
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('request_slug') }}</p>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
            </form>
        `
    });
}

// Auto-open modal if there are validation errors
document.addEventListener('DOMContentLoaded', function() {
    @if($errors->any())
        createNewQuoteWithRequestNoModal();
    @endif
});
</script>

