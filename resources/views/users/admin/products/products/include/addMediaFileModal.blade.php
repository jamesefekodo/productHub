<script>
    function addMediaFileFormModal(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.buttons.update') }} :: {{ t('app.product.mediaFile') }}",
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: sizeClass
            },
            html: `
        <form id="swalForm"
              method="POST"
              enctype="multipart/form-data"
              action="{{ route('addProductMediaSubmit') }}">
            @csrf

            <input type="hidden" name="product"
                   value="{{ $data['product']->slug }}">

            <div class="row col-md-12">
                <div class="mb-3 col-md-8">
                    <label class="form-label">
                        {{ t('app.product.fileType') }}<span class="text-danger">*</span>

                    </label>

                    <select class="form-select text-center" name="file_type" required>

                        <option value="" disabled {{ old('file_type') ? '' : 'selected' }}>
                            - {{ t('app.select') }} -
                        </option>

                        <option value="image" {{ old('file_type') == 'image' ? 'selected' : '' }}>
                            {{ t('app.image') }}
                        </option>

                        <option value="video" {{ old('file_type') == 'video' ? 'selected' : '' }}>
                            {{ t('app.video') }}
                        </option>
                    </select>

                    @if ($errors->has('file_type'))
                        <small class="text-danger">
                            {{ $errors->first('file_type') }}
                        </small>
                    @endif
                </div>

                <div class="mb-3 col-md-4">
                    <label class="form-label">
                        {{ t('app.product.sortOrder') }}<span class="text-danger">*</span>

                    </label>
                    <input type="integer" name="sort_order" class="form-control" required>

                    @if ($errors->has('sort_order'))
                        <small class="text-danger">
                            {{ $errors->first('sort_order') }}
                        </small>
                    @endif
                </div>

                <div class="mb-3 col-md-12">
                    <label class="form-label">
                        {{ t('app.product.mediaFile') }}<span class="text-danger">*</span>

                    </label>

                    <input type="file" name="media_file" class="form-control" required>

                    @if ($errors->has('media_file'))
                        <small class="text-danger">
                            {{ $errors->first('media_file') }}
                        </small>
                    @endif
                </div>
            </div>


            <button type="submit"
                    class="btn btn-primary w-100 mt-2">
                {{ t('app.buttons.submit') }}
            </button>
        </form>
        `
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            addMediaFileFormModal();
        @endif
    });
</script>


