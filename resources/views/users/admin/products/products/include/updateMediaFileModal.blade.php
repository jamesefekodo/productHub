<script>
    window.openUpdateMediaModal = function(mediaId, fileType, currentSrc, sortOrder, isPrimary) {
        const isVideo     = fileType === 'video';
        const acceptTypes = isVideo ? 'video/mp4,video/webm,video/ogg' : 'image/jpeg,image/png,image/webp,image/gif';
        const previewHtml = isVideo
            ? `<video src="${currentSrc}" controls style="width:100%; max-height:200px; border-radius:6px; background:#000;"></video>`
            : `<img src="${currentSrc}" style="width:100%; max-height:200px; object-fit:contain; border-radius:6px; border:1px solid #ddd;">`;

        const primaryHtml = !isVideo ? `
            <div style="flex:1;">
                <label style="font-size:13px; color:#444; display:block; margin-bottom:6px;">
                    {{ t('app.product.primaryImage') }} <span style="color:red;">*</span>
                </label>
                <select name="is_primary" id="modalIsPrimary" class="form-control">
                    <option value="1" ${isPrimary == 1 ? 'selected' : ''}>{{ t('app.status.true') }}</option>
                    <option value="0" ${isPrimary == 0 ? 'selected' : ''}>{{ t('app.status.false') }}</option>
                </select>
            </div>
        ` : '';

        Swal.fire({
            title: '{{ t('app.buttons.update') }} :: {{ t('app.product.mediaFile') }}',
            showCloseButton: true,
            showConfirmButton: false,
            html: `
                <form id="updateMediaForm" method="POST"
                      action="{{ route('updateProductMedia', ['id' => '__ID__']) }}"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="product" value="{{ $data['product']->slug }}">

                    {{-- Current preview --}}
                    <div style="margin-bottom: 12px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                            <p style="font-size:13px; color:#666; margin:0;">{{ t('app.product.currentFile') }}:</p>
                            <button type="button"
                                    onclick="window.deleteMediaFile(${mediaId})"
                                    style="font-size:12px; color:#d33; background:none; border:none; cursor:pointer; padding:0; text-decoration:underline;">
                                {{ t('app.buttons.delete') }}
                            </button>
                        </div>
                        ${previewHtml}
                    </div>

                    {{-- New file input --}}
                    <div style="margin-bottom: 12px;">
                        <label style="font-size:13px; color:#444; display:block; margin-bottom:6px;">
                            {{ t('app.product.replaceFile') }}:
                        </label>
                        <input type="file"
                               name="media_file"
                               accept="${acceptTypes}"
                               class="form-control"
                               id="newMediaFile"
                               onchange="window.previewNewMedia(this, '${fileType}')">
                    </div>

                    {{-- New file preview --}}
                    <div id="newMediaPreview" style="margin-bottom: 12px; display:none;">
                        <p style="font-size:13px; color:#666; margin-bottom:6px;">{{ t('app.product.newFilePreview') }}:</p>
                        <div id="newMediaPreviewContent"></div>
                    </div>

                    {{-- Sort order + Primary --}}
                    <div style="display:flex; gap:12px; margin-bottom:12px;">
                        <div style="flex:1;">
                            <label style="font-size:13px; color:#444; display:block; margin-bottom:6px;">
                                {{ t('app.product.sortOrder') }} <span style="color:red;">*</span>
                            </label>
                            <input type="number"
                                   name="sort_order"
                                   id="modalSortOrder"
                                   class="form-control"
                                   min="0"
                                   value="${sortOrder}"
                                   required>
                        </div>
                        ${primaryHtml}
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2">
                        {{ t('app.buttons.update') }}
                    </button>
                </form>
            `.replace('__ID__', mediaId),

            didOpen: () => {
                const form = document.getElementById('updateMediaForm');

                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '{{ t('app.notifications.updated') }}',
                                text: data.message ?? 'Media updated successfully.',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => window.location.reload());
                        } else {
                            Swal.showValidationMessage(data.message ?? 'Something went wrong.');
                        }
                    })
                    .catch(() => {
                        Swal.showValidationMessage('Upload failed. Please try again.');
                    });
                });
            }
        });
    };

    // ─── Delete media ──────────────────────────────────────────────────────────
    window.deleteMediaFile = function(mediaId) {
        Swal.fire({
            title: '{{ t('app.buttons.delete') }}?',
            text: '{{ t('app.deleteConfirmText') }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '{{ t('app.buttons.delete') }}',
            cancelButtonText: '{{ t('app.buttons.cancel') }}',
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`{{ url('product/media/delete') }}/${mediaId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '{{ t('app.notifications.deleted') }}',
                            text: data.message ?? 'Media deleted.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => window.location.reload());
                    } else {
                        Swal.fire('Error', data.message ?? 'Could not delete.', 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Delete failed. Please try again.', 'error');
                });
            }
        });
    };

    // ─── Preview new file ──────────────────────────────────────────────────────
    window.previewNewMedia = function(input, fileType) {
        const previewBox     = document.getElementById('newMediaPreview');
        const previewContent = document.getElementById('newMediaPreviewContent');

        if (!input.files.length) {
            previewBox.style.display = 'none';
            return;
        }

        const url = URL.createObjectURL(input.files[0]);

        previewContent.innerHTML = fileType === 'video'
            ? `<video src="${url}" controls style="width:100%; max-height:200px; border-radius:6px; background:#000;"></video>`
            : `<img src="${url}" style="width:100%; max-height:200px; object-fit:contain; border-radius:6px; border:1px solid #ddd;">`;

        previewBox.style.display = 'block';
    };
</script>
