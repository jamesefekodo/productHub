<script>
function removeProductVariantColourFormModal(sizeClass = 'swal-size-md mx-5') {
    Swal.fire({
        // title: "{{ t('app.buttons.remove') }} :: {{ t('app.product.colour') }}",
        showCloseButton: true,
        showConfirmButton: false, // no SweetAlert confirm button
        customClass: {
            popup: sizeClass
        },
        html: `
            <form id="swalForm" method="POST" action="{{ route('removeProductVariantColourSubmit') }}">
                @csrf

                <input type="hidden" name="variant" value="{{ $data['variant']->slug }}">
                <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.proceed') }}</button>
            </form>
        `
    });
}

// Auto-open modal if there are validation errors
document.addEventListener('DOMContentLoaded', function() {
    @if($errors->any())
        removeProductVariantColourFormModal();
    @endif
});
</script>
