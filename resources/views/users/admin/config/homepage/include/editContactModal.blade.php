<script>
    function editContactFormModal(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.buttons.edit') }} :: {{ t('app.settings.address.contactDetails') }}",
            showCloseButton: true,
            showConfirmButton: false, // no SweetAlert confirm button
            customClass: {
                popup: sizeClass
            },
            html: `
            <form id="swalForm" method="POST" action="{{ route('updateContactDetailsSubmit') }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">{{ t('auth.phone') }}</label>
                    <input type="text" name="phone" class="form-control text-center" value="{{ $data['systemSettings']->phone ?? '' }}" required>
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('phone') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ t('auth.email') }}</label>
                    <input type="email" name="email" class="form-control text-center" value="{{ $data['systemSettings']->email ?? '' }}" required>
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('email') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ t('app.settings.address.address') }}</label>
                    <input type="text" name="address" class="form-control text-center" value="{{ $data['systemSettings']->address ?? '' }}" required>
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('address') }}</p>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
            </form>
        `
        });
    }

    // Auto-open modal if there are validation errors
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            editContactFormModal();
        @endif
    });
</script>
