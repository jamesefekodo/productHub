<script>
    window.openVariantImageModal = function(mediaId, currentSrc) {

        Swal.fire({
            title: "{{ t('app.buttons.update') }} :: {{ t('app.product.mediaFile') }}",
            showCloseButton: true,
            showConfirmButton: false,
            html: `
                <form id="updateVariantImageForm"
                    method="POST"
                    action="{{ route('updateVariantImage', ['id' => '__ID__']) }}"
                    enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <input type="hidden" name="variant_slug" value="{{ $data['variant']->slug }}">

                    <!-- Current Image -->
                    <div style="margin-bottom:12px;">
                        <p style="font-size:13px; color:#666; margin-bottom:6px;">
                            {{ t('app.product.currentFile') }}:
                        </p>

                        <img src="${currentSrc}"
                            style="width:100%; max-height:200px; object-fit:contain; border-radius:6px; border:1px solid #ddd;">
                    </div>

                    <!-- New Image -->
                    <div style="margin-bottom:12px;">
                        <label style="font-size:13px; color:#444; display:block; margin-bottom:6px;">
                            {{ t('app.product.replaceFile') }}:
                        </label>

                        <input type="file"
                            name="media_file"
                            accept="image/jpeg,image/png,image/webp,image/gif"
                            class="form-control"
                            onchange="previewVariantNewImage(this)">
                    </div>

                    <!-- New Preview -->
                    <div id="variantNewPreview"
                        style="margin-bottom:12px; display:none;">
                        <p style="font-size:13px; color:#666; margin-bottom:6px;">
                            {{ t('app.product.newFilePreview') }}:
                        </p>
                        <div id="variantNewPreviewContent"></div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2">
                        {{ t('app.buttons.update') }}
                    </button>
                </form>
            `.replace('__ID__', mediaId),

            didOpen: () => {

                const form = document.getElementById('updateVariantImageForm');

                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(form);

                    fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: "{{ t('app.notifications.updated') }}",
                                    text: data.message ?? 'Updated successfully.',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => window.location.reload());
                            } else {
                                Swal.showValidationMessage(data.message ??
                                    'Error occurred.');
                            }
                        })
                        .catch(() => {
                            Swal.showValidationMessage('Upload failed.');
                        });
                });
            }
        });
    };
</script>
<script>
    window.previewVariantNewImage = function(input) {

        const previewBox = document.getElementById('variantNewPreview');
        const previewContent = document.getElementById('variantNewPreviewContent');

        if (!input.files.length) {
            previewBox.style.display = 'none';
            return;
        }

        const url = URL.createObjectURL(input.files[0]);

        previewContent.innerHTML = `
                    <img src="${url}"
                        style="width:100%; max-height:200px; object-fit:contain; border-radius:6px; border:1px solid #ddd;">
                `;

        previewBox.style.display = 'block';
    };
</script>
