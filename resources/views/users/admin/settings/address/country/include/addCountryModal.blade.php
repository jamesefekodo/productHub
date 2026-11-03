<!-- Button to open the modal manually -->
{{-- <button type="button" onclick="addCountryFormModal()" class="btn btn-primary">
    {{ t('app.addNew') }}
</button> --}}

<script>
function addCountryFormModal(sizeClass = 'swal-size-md mx-5') {
    Swal.fire({
        title: "{{ t('app.addNew') }} :: {{ t('app.settings.address.country') }}",
        showCloseButton: true,
        showConfirmButton: false, // no SweetAlert confirm button
        customClass: {
            popup: sizeClass
        },
        html: `
            <form id="swalForm" method="POST" action="{{ route('createNewCountrySubmit') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ t('app.settings.address.country') }}</label>
                    <input type="text" name="country" class="form-control text-center" value="{{ old('country') }}" required>
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('country') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ t('app.settings.address.phoneCode') }}</label>
                    <input type="text" name="phone_code" class="form-control text-center" value="{{ old('phone_code') }}" required>
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('phone_code') }}</p>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
            </form>
        `
    });
}

// Auto-open modal if there are validation errors
document.addEventListener('DOMContentLoaded', function() {
    @if($errors->any())
        addCountryFormModal();
    @endif
});
</script>

