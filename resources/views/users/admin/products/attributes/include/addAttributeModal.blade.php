<script>
    function addAttributeFormModal(sizeClass = 'swal-size-md mx-5') {
    Swal.fire({
        title: "{{ t('app.addNew') }} :: {{ t('app.product.attributes') }}",
        showCloseButton: true,
        showConfirmButton: false,
        customClass: { popup: sizeClass },
        html: `
            <form method="POST" action="{{ route('createNewAttributeSubmit') }}" id="attribute-form">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ t('app.name') }}</label>
                    <input type="text" name="name" class="form-control text-center" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ t('app.product.attributeValue') }}</label>
                    <div id="attribute-values-wrapper">
                        <div class="input-group mb-2 attribute-value-row mt-t">
                            <input type="text" name="value[]" class="form-control text-center" placeholder="e.g. Red" value="{{ old('value[]') }}">
                            <button type="button" class="btn btn-outline-danger remove-attribute">&times;</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-3" id="add-attribute-value">
                        + {{ t('app.product.addAnotherValue') }}
                    </button>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    {{ t('app.buttons.submit') }}
                </button>
            </form>
        `
    }).then(() => {
        // This runs after modal opens
    });

    // Set up event listeners after a short delay to ensure DOM is ready
    setTimeout(() => {
        const popup = document.querySelector('.swal2-popup');
        if (!popup) return;

        const addBtn = popup.querySelector('#add-attribute-value');
        const wrapper = popup.querySelector('#attribute-values-wrapper');

        // Add button click
        if (addBtn) {
            addBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const newRow = `
                    <div class="input-group mb-2 attribute-value-row">
                        <input type="text" name="value[]" class="form-control text-center" placeholder="e.g. Blue">
                        <button type="button" class="btn btn-outline-danger remove-attribute">&times;</button>
                    </div>
                `;
                wrapper.insertAdjacentHTML('beforeend', newRow);
            });
        }

        // Remove button click (delegation)
        if (wrapper) {
            wrapper.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.remove-attribute');
                if (removeBtn) {
                    e.preventDefault();
                    const rows = wrapper.querySelectorAll('.attribute-value-row');
                    if (rows.length > 1) {
                        removeBtn.closest('.attribute-value-row').remove();
                    }
                }
            });
        }
    }, 100);
}



 // Auto-open modal if there are validation errors
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            addAttributeFormModal();
        @endif
    });
</script>

