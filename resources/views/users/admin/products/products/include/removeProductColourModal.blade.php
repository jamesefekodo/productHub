<script>
function removeProductColourFormModal(sizeClass = 'swal-size-md mx-5') {
    Swal.fire({
        // title: "{{ t('app.buttons.remove') }} :: {{ t('app.product.colour') }}",
        showCloseButton: true,
        showConfirmButton: false, // no SweetAlert confirm button
        customClass: {
            popup: sizeClass
        },
        html: `
            <form id="swalForm" method="POST" action="{{ route('removeProductColourSubmit') }}">
                @csrf

                <input type="hidden" name="product" value="{{ $data['product']->slug }}">
                <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.proceed') }}</button>
            </form>
        `
    });
}

// Auto-open modal if there are validation errors
document.addEventListener('DOMContentLoaded', function() {
    @if($errors->any())
        removeProductColourFormModal();
    @endif
});
</script>
