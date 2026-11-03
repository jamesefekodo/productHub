<script>
    function removeProductAttributeFormModal(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.buttons.delete') }} :: {{ t('app.product.attribute') }}",
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: sizeClass
            },
            html: `
            <style>
                .delete-attribute-card {
                    border: 1px solid #ccc;
                    padding: 10px 15px;
                    border-radius: 8px;
                    cursor: pointer;
                    user-select: none;
                    margin-bottom: 8px;
                    transition: all 0.2s;
                    background-color: #fff;
                    font-size: 14px;
                    display: inline-block;
                }
                .delete-attribute-card:hover {
                    border-color: #007bff;
                    background-color: #e6f0ff;
                }
                .selected-card {
                    border-color: #0056b3 !important;
                    background-color: #cce5ff !important;
                    font-weight: bold;
                    color: #004085;
                    box-shadow: 0 0 8px rgba(0,123,255,0.4);
                }
            </style>
            <form id="swalForm" method="POST" action="{{ route('removeProductAttributeSubmit') }}">
                @csrf
                <input type="hidden" name="product" value="{{ $data['product']->slug }}">
                <div class="mb-3">
                    <label class="form-label">{{ t('app.product.attribute') }} <span class="text-danger">*</span></label>
                    <div id="delete-attribute-cards" style="display:flex; flex-wrap:wrap; gap:10px;">
                        @if ($data['product']->attributes->isNotEmpty())
                            @foreach ($data['product']->attributes as $attribute)
                                @php
                                    $locale = currentLocale();
                                    $nameField = 'name_' . $locale;
                                    $valueField = 'value_' . $locale;
                                    $attName = !empty($attribute->attribute->$nameField) ? $attribute->attribute->$nameField : $attribute->attribute->name;
                                    $attValue = !empty($attribute->value->$valueField) ? $attribute->value->$valueField : $attribute->value->value;
                                @endphp
                                <div class="delete-attribute-card" data-id="{{ $attribute->id }}"
                                     onclick="window.toggleAttributeCard(this)">
                                    <strong>{{ $attName }}</strong>: {{ $attValue }}
                                </div>
                            @endforeach
                        @else
                            <span>- {{ t('app.no_attributes') }}</span>
                        @endif
                    </div>
                    <input type="hidden" name="attribute_ids" id="selected-attributes">
                </div>
                <button type="submit" class="btn btn-danger w-100 mt-2">{{ t('app.buttons.delete') }}</button>
            </form>
        `
        });
    }

    // Global function - inline onclick is most reliable in SweetAlert
    window.toggleAttributeCard = function(card) {
        // Toggle selected style inline (no class dependency)
        if (card.dataset.selected === 'true') {
            // Deselect
            card.dataset.selected = 'false';
            card.style.borderColor = '#ccc';
            card.style.backgroundColor = '#fff';
            card.style.fontWeight = 'normal';
            card.style.color = 'inherit';
            card.style.boxShadow = 'none';
        } else {
            // Select
            card.dataset.selected = 'true';
            card.style.borderColor = '#0056b3';
            card.style.backgroundColor = '#cce5ff';
            card.style.fontWeight = 'bold';
            card.style.color = '#004085';
            card.style.boxShadow = '0 0 8px rgba(0,123,255,0.4)';
        }

        // Update hidden input with all selected IDs
        const container = document.getElementById('delete-attribute-cards');
        const selectedIds = Array.from(container.querySelectorAll('[data-selected="true"]'))
            .map(c => c.dataset.id);
        document.getElementById('selected-attributes').value = selectedIds.join(',');
    };
</script>
