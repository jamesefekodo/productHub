<script>
    function editCountryFormModal{{ $country->id }}(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.buttons.edit') }} :: {{ t('app.settings.address.country') }}",
            showCloseButton: true,
            showConfirmButton: false,
            customClass: { popup: sizeClass },
            html: `
                <form id="swalForm" method="POST" action="{{ route('updateCountrySubmit', ['id' => $country->id]) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">{{ t('app.settings.address.country') }}</label>
                        <input type="text" name="country" class="form-control text-center" value="{{ $country->name }}" required>
                        <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('country') }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ t('app.settings.address.phoneCode') }}</label>
                        <input type="text" name="phone_code" class="form-control text-center" value="{{ $country->phone_code }}" required>
                        <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('phone_code') }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ t('app.status.status') }}</label>
                        <select class="form-select text-center" name="status" required>
                                        <option selected disabled>
                                            -{{ t('app.select') }}-</option>
                                        <option value="active" {{ $country->status == 'active' ? 'selected' : '' }}>
                                            {{ t('app.status.active') }}</option>
                                        <option value="inactive" {{ $country->status == 'inactive' ? 'selected' : '' }}>
                                            {{ t('app.status.inactive') }}</option>
                        </select>
                        <p id="phone-error" style="color: red; font-size: 12px; margin-top: 5px;"></p>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
                </form>
            `
        });
    }
    </script>
{{--
<script>
function editCountryFormModal(el, sizeClass = 'swal-size-md mx-5') {
    const countryId = el.dataset.id;
    const countryName = el.dataset.name;
    const phoneCode = el.dataset.phone;

    Swal.fire({
        title: "{{ t('app.buttons.edit') }} :: {{ t('app.settings.address.country') }}",
        showCloseButton: true,
        showConfirmButton: false,
        customClass: { popup: sizeClass },
        html: `
            <form id="swalForm" method="POST" action="{{ route('updateCountrySubmit', ['id' => $country->id]) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">{{ t('app.settings.address.country') }}</label>
                    <input type="text" name="country" class="form-control text-center" value="${countryName}" required>
                    <p id="country-error" style="color: red; font-size: 12px; margin-top: 5px;"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ t('app.settings.address.phoneCode') }}</label>
                    <input type="text" name="phone_code" class="form-control text-center" value="${phoneCode}" required>
                    <p id="phone-error" style="color: red; font-size: 12px; margin-top: 5px;"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ t('app.status.status') }}</label>
                    <select class="form-select" name="status" required>
                                    <option selected disabled>
                                        -Select-</option>
                                    <option value="active" {{ $country->status == 'active' ? 'selected' : '' }}>
                                        {{ t('app.status.status') }}</option>
                                    <option value="approved" {{ $country->status == 'approved' ? 'selected' : '' }}>
                                        Approved</option>
                                </select>
                    <p id="phone-error" style="color: red; font-size: 12px; margin-top: 5px;"></p>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
            </form>
        `
    });
}

// Auto-open modal if there are validation errors
document.addEventListener('DOMContentLoaded', function() {
    @if($errors->any())
        editCountryormModal();
    @endif
});
</script> --}}
