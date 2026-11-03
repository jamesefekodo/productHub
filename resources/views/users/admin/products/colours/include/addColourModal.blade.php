<script>
    function addColourFormModal(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.addNew') }} :: {{ t('app.product.colour') }}",
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: sizeClass
            },
            html: `
            <form id="swalForm" method="POST" action="{{ route('createNewColourSubmit') }}" onsubmit="return validateColor()">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ t('app.name') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control text-center" value="{{ old('name') }}" required>
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('name') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ t('app.product.colourCode') }}</label>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 80%;">
                                <input type="text"
                                       name="hex_code"
                                       class="form-control text-center"
                                       value="#000000"
                                       placeholder="#000000"
                                       maxlength="7"
                                       required
                                       style="border: 3px solid #000000;"
                                       onkeyup="window.syncColors(this, 'text')"
                                       onchange="window.syncColors(this, 'text')">
                            </td>
                            <td style="width: 20%; padding-left: 10px;">
                                <input type="color"
                                       value="#000000"
                                       style="width: 100%; height: 38px; cursor: pointer;"
                                       onchange="window.syncColors(this, 'picker')"
                                       oninput="window.syncColors(this, 'picker')">
                            </td>
                        </tr>
                    </table>
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('hex_code') }}</p>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
            </form>
        `
        });
    }

    // Global sync function attached to window
    window.syncColors = function(element, type) {
        const form = element.closest('form');
        if (!form) return;

        const textInput = form.querySelector('input[name="hex_code"]');
        const colorInput = form.querySelector('input[type="color"]');

        if (!textInput || !colorInput) return;

        if (type === 'picker') {
            // Color picker changed
            textInput.value = colorInput.value.toUpperCase();
            textInput.style.borderColor = colorInput.value;
        } else {
            // Text input changed
            let val = textInput.value.trim();

            // Add # if missing
            if (val && !val.startsWith('#')) {
                val = '#' + val;
                textInput.value = val;
            }

            // Update picker if valid hex
            if (/^#[0-9A-Fa-f]{6}$/i.test(val)) {
                colorInput.value = val.toUpperCase();
                textInput.style.borderColor = val;
            }
        }
    };

    window.validateColor = function() {
        const form = document.getElementById('swalForm');
        const hexInput = form.querySelector('input[name="hex_code"]');
        let val = hexInput.value.trim();

        if (!val.startsWith('#')) {
            val = '#' + val;
            hexInput.value = val;
        }

        return /^#[0-9A-Fa-f]{6}$/i.test(val);
    };

    // Auto-open modal if there are validation errors
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            addColourFormModal();
        @endif
    });
</script>
