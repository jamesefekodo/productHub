<script>
    function addWhatsAppSupportFormModal(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.addNew') }} :: {{ t('app.settings.address.whatsappNumber') }}",
            showCloseButton: true,
            showConfirmButton: false, // no SweetAlert confirm button
            customClass: {
                popup: sizeClass
            },
            html: `
            <form id="swalForm" method="POST" action="{{ route('addWhatsappNumberSubmit') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ t('app.name') }}</label>
                    <input type="text" name="name" class="form-control text-center" value="{{ old('name') }}" required>
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('name') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">
                        {{ t('app.settings.address.whatsappNumber') }}
                    </label>

                    <input type="tel"
                        name="number"
                        class="form-control text-center"
                        value="{{ old('number') }}"
                        placeholder="e.g. +2348012345678"
                        pattern="^\+?[0-9]{7,15}$"
                        required>

                    @error('number')
                        <p class="text-danger small mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
            </form>
        `
        });
    }

    // Auto-open modal if there are validation errors
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            addWhatsAppSupportFormModal();
        @endif
    });
</script>
