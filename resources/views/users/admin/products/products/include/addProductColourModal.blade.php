<script>
function addProductColourFormModal(sizeClass = 'swal-size-md mx-5') {
    Swal.fire({
        title: "{{ t('app.buttons.update') }} :: {{ t('app.product.colour') }}",
        showCloseButton: true,
        showConfirmButton: false, // no SweetAlert confirm button
        customClass: {
            popup: sizeClass
        },
        html: `
            <form id="swalForm" method="POST" action="{{ route('addProductColourSubmit') }}">
                @csrf

                <input type="hidden" name="product" value="{{ $data['product']->slug }}">

                <div class="mb-3">
                    <label class="form-label">{{ t('app.product.colour') }}</label>

                    <select class="form-select text-center @error('colour') is-invalid @enderror" name="colour" required>

                        <option value="" disabled {{ old('colour') ? '' : 'selected' }}>
                            - {{ t('app.select') }} -
                        </option>

                        @forelse ($data['allColours'] as $colour)
                            <option value="{{ $colour->id }}" {{ $data['product']->colour_id == $colour->id ? 'selected' : '' }}>
                                {{ $colour->name }}
                            </option>
                        @empty
                            <option disabled>No colour available</option>
                        @endforelse

                    </select>

                    @error('colour')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
            </form>
        `
    });
}

// Auto-open modal if there are validation errors
document.addEventListener('DOMContentLoaded', function() {
    @if($errors->any())
        addProductColourFormModal();
    @endif
});
</script>
