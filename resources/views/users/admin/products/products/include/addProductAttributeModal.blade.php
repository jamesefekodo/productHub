<script>
function addProductAttributeFormModal(sizeClass = 'swal-size-md mx-5') {
    Swal.fire({
        title: "{{ t('app.buttons.update') }} :: {{ t('app.product.attribute') }}",
        showCloseButton: true,
        showConfirmButton: false,
        customClass: { popup: sizeClass },
        html: `
        <form id="swalForm" method="POST" action="{{ route('addProductAttributeSubmit') }}">
            @csrf
            <input type="hidden" name="product" value="{{ $data['product']->slug }}">

            <div class="mb-3">
                <label class="form-label">{{ t('app.product.attribute') }} <span class="text-danger">*</span></label>
                <select class="form-select text-center" name="attribute_id" id="attribute-select" required>
                    <option value="">- {{ t('app.select') }} -</option>
                    @foreach ($data['activeAttributes'] as $attribute)
                        @php
                            $locale = currentLocale();
                            $field = 'name_' . $locale;
                            $value = !empty($attribute->$field) ? $attribute->$field : $attribute->name;
                        @endphp
                        <option value="{{ $attribute->id }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ t('app.product.value') }} <span class="text-danger">*</span></label>
                <select class="form-select text-center" id="value-select" name="value_id" disabled required>
                    <option value="">- {{ t('app.select') }} -</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
        </form>
        `,
        // Use 'onOpen' for compatibility with older versions
        onOpen: function() {
            const container = Swal.getHtmlContainer();
            if (!container) return;

            const attributeSelect = container.querySelector('#attribute-select');
            const valueSelect = container.querySelector('#value-select');

            if (!attributeSelect || !valueSelect) return;

            attributeSelect.addEventListener('change', function() {
                const attributeId = this.value;

                valueSelect.disabled = true;
                valueSelect.innerHTML = '<option>Loading...</option>';

                $.ajax({
                    url: "{{ route('fetchAttributeValue') }}",
                    method: "GET",
                    data: { attribute_id: attributeId },
                    success: function(res) {
                        valueSelect.innerHTML = '<option value="">- {{ t("app.select") }} -</option>';

                        if (!res.values || res.values.length === 0) {
                            valueSelect.innerHTML += '<option disabled>No values found</option>';
                            return;
                        }

                        res.values.forEach(function(value) {
                            valueSelect.innerHTML += `<option value="${value.id}">${value.localized_name}</option>`;
                        });

                        valueSelect.disabled = false;
                    },
                    error: function() {
                        valueSelect.innerHTML = '<option disabled>Error loading values</option>';
                    }
                });
            });
        }
    });
}

// Auto-open modal if validation errors exist
document.addEventListener('DOMContentLoaded', function() {
    @if ($errors->any())
        addProductAttributeFormModal();
    @endif
});
</script>
