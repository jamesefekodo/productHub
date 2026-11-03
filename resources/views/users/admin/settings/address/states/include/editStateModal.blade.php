<script>
    function editStateFormModal{{ $state->id }}(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.buttons.edit') }} :: {{ t('app.settings.address.state') }}",
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: sizeClass
            },
            html: `
                <form id="swalForm" method="POST" action="{{ route('updateStateSubmit', ['id' => $state->id]) }}"
                >
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">{{ t('app.settings.address.state') }}</label>
                        <input type="text" name="state" class="form-control text-center" value="{{ $state->name }}" required>
                        <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('state') }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ t('app.settings.address.country') }}</label>

                        <select class="form-select text-center @error('country') is-invalid @enderror" name="country" required>

                            <option value="" disabled {{ old('country') ? '' : 'selected' }}>
                                - {{ t('app.select') }} -
                            </option>

                            @forelse ($data['allCountries'] as $country)
                                <option value="{{ $country->id }}" {{ $state->country_id == $country->id ? 'selected' : '' }}>
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
                        <label class="form-label">{{ t('app.status.status') }}</label>
                        <select class="form-select text-center" name="status" required>
                            <option selected disabled>
                                -Select-</option>
                            <option value="active" {{ $state->status == 'active' ? 'selected' : '' }}>
                                {{ t('app.status.active') }}</option>
                            <option value="inactive" {{ $state->status == 'inactive' ? 'selected' : '' }}>
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
