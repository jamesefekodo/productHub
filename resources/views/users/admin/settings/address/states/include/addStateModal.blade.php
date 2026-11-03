<script>
    function addStateFormModal(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.addNew') }} :: {{ t('app.settings.address.state') }}",
            showCloseButton: true,
            showConfirmButton: false, // no SweetAlert confirm button
            customClass: {
                popup: sizeClass
            },
            html: `
            <form id="swalForm" method="POST" action="{{ route('createNewStateSubmit') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ t('app.settings.address.country') }}</label>

                    <select class="form-select text-center @error('country') is-invalid @enderror"
                            name="country"
                            required>

                        <option value="" disabled {{ old('country') ? '' : 'selected' }}>
                            - {{ t('app.select') }} -
                        </option>

                        @forelse ($data['activeCountries'] as $country)
                            <option value="{{ $country->id }}"
                                {{ old('country') == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @empty
                            <option disabled>No countries available</option>
                        @endforelse

                    </select>

                    @error('country')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ t('app.settings.address.state') }}</label>
                    <input type="text" name="state" class="form-control text-center" value="{{ old('state') }}" required>
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('state') }}</p>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
            </form>
        `
        });
    }

    // Auto-open modal if there are validation errors
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            addStateFormModal();
        @endif
    });
</script>
