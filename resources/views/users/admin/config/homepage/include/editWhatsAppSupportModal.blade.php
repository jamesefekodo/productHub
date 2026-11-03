<script>
    function editWhatsAppSupportFormModal{{ $line->id }}(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.buttons.edit') }} :: {{ t('app.settings.address.whatsappNumber') }}",
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: sizeClass
            },
            html: `
                <form id="swalForm" method="POST" action="{{ route('updateWhatsAppNumberSubmit', ['id' => $line->id]) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">{{ t('app.name') }}</label>
                        <input type="text" name="name" class="form-control text-center" value="{{ old('name', $line->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ t('app.settings.address.whatsappNumber') }}</label>
                        <input type="tel" name="number" class="form-control text-center"
                               value="{{ old('number', $line->number) }}"
                               placeholder="e.g. +2348012345678"
                               pattern="^\+?[0-9]{7,15}$" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ t('app.status.status') }}</label>
                        <select class="form-select text-center" name="status" required>
                            <option value="" disabled>-{{ t('app.select') }}-</option>
                            <option value="active" {{ old('status', $line->status) == 'active' ? 'selected' : '' }}>
                                {{ t('app.status.active') }}
                            </option>
                            <option value="inactive" {{ old('status', $line->status) == 'inactive' ? 'selected' : '' }}>
                                {{ t('app.status.inactive') }}
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
                </form>
            `
        });
    }
</script>
