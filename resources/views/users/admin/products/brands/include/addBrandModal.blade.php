<script>
    function addBandFormModal(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.addNew') }} :: {{ t('app.product.brand') }}",
            showCloseButton: true,
            showConfirmButton: false, // no SweetAlert confirm button
            customClass: {
                popup: sizeClass
            },
            html: `
            <form id="swalForm" method="POST" action="{{ route('createNewBrandSubmit') }}" enctype="multipart/form-data">
                @csrf
                @php
                    $image = asset('system/images/logo_placeholder.jpg');
                @endphp
                <div class="mb-3 form-group upload-container">
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-6"><label for="passport-upload" class="preview-frame">
                                <img id="passport-preview"
                                    src="{{ $image }}" alt="">
                                <div class="overlay"><i class="fas fa-upload"></i></div>
                            </label>
                        </div>
                        <div class="col-md-6"><span class="text-red">*</span><input type="file" id="passport-upload"
                                accept=".jpg, .jpeg, .png, .webp" onchange="previewPassport()"
                                name="logo">
                        </div>
                        <p class="text-red">{{ $errors->first('logo') }}</p>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ t('app.name') }} <span class="text-red">*</span></label>
                    <input type="text" name="name" class="form-control text-center" value="{{ old('name') }}" required>
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('name') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ t('app.description') }}</label>
                    <input type="text" name="description" class="form-control text-center" value="{{ old('description') }}">
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('description') }}</p>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
            </form>
        `
        });
    }

    // Auto-open modal if there are validation errors
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            addBandFormModal();
        @endif
    });
</script>


{{-- Preview Passport --}}
<script>
    function previewPassport() {
        const preview = document.getElementById('passport-preview');
        const fileInput = document.getElementById('passport-upload');
        const file = fileInput.files[0];
        const reader = new FileReader();

        reader.onload = function() {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = 'placeholder.jpg';
        }
    }
</script>


{{-- description text area --}}
