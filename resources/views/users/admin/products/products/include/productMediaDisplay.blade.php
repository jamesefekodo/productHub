@switch(getUserType())
    @case('root')
    @case('admin')
    @case('staff')
    <div class="col-md-4 ps-5 product-gallery">

        @break

    @default
<div class="col-md-6 ps-5 product-gallery">

@endswitch
    <div class="row">

        {{-- Main Viewer --}}
        <div class="col-12 mb-2">
            <div class="main-image-wrapper" id="imageWrapper">
                {{-- Image (default) --}}
                <img id="mainImage"
                    src="{{ asset('assets/uploads/products/products/media/images/' . $data['product']->mediaFiles->where('file_type', 'image')->first()->file_name) }}"
                    class="main-image">

                {{-- Video (hidden by default) --}}
                <video id="mainVideo" class="main-image" controls style="display:none; object-fit: contain;">
                    <source id="mainVideoSource" src="" type="video/mp4">
                </video>
            </div>

            {{-- Zoom Controls (hidden when video is active) --}}
            <div class="zoom-controls" id="zoomControls">
                <button onclick="zoomOut()" title="Zoom Out">−</button>
                <input type="range" id="zoomSlider" min="1" max="5" step="0.1" value="1"
                    oninput="zoomSlider(this.value)">
                <button onclick="zoomIn()" title="Zoom In">+</button>
                <button onclick="zoomReset()" title="Reset">↺</button>
            </div>
        </div>

        {{-- Thumbnails --}}
        <div class="col-12">
            <div class="thumbnails-wrapper">
                @foreach ($data['product']->mediaFiles as $index => $media)
                    @if ($media->file_type === 'video')
                        @php $filePath = asset('assets/uploads/products/products/media/videos/' . $media->file_name); @endphp
                        <div class="thumb-image thumb-video {{ $index === 0 ? 'active' : '' }}"
                            onclick="setMainVideo(this, '{{ $filePath }}')">
                            <div class="play-icon"><i class="feather-play"></i></div>
                        </div>
                    @else
                        @php $filePath = asset('assets/uploads/products/products/media/images/' . $media->file_name); @endphp
                        <img src="{{ $filePath }}" class="thumb-image {{ $index === 0 ? 'active' : '' }}"
                            onclick="setMainImage(this, '{{ $filePath }}')">
                    @endif
                @endforeach
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.getElementById('imageWrapper');
        const image = document.getElementById('mainImage');
        const video = document.getElementById('mainVideo');
        const zoomCtrls = document.getElementById('zoomControls');

        let scale = 1,
            panX = 0,
            panY = 0;
        let isDragging = false,
            dragStartX = 0,
            dragStartY = 0,
            dragOriginX = 0,
            dragOriginY = 0;
        let lastTouchDist = null,
            lastTouchX = null,
            lastTouchY = null;

        function applyTransform() {
            const maxPanX = (scale - 1) * wrapper.offsetWidth / 2;
            const maxPanY = (scale - 1) * wrapper.offsetHeight / 2;
            panX = Math.min(maxPanX, Math.max(-maxPanX, panX));
            panY = Math.min(maxPanY, Math.max(-maxPanY, panY));
            image.style.transform = `scale(${scale}) translate(${panX / scale}px, ${panY / scale}px)`;
            document.getElementById('zoomSlider').value = scale;
            window.currentZoom = scale;
        }

        // Hover zoom
        wrapper.addEventListener('mousemove', function(e) {
            if (video.style.display !== 'none') return; // skip if video is active
            if (isDragging) {
                panX = dragOriginX + (e.clientX - dragStartX);
                panY = dragOriginY + (e.clientY - dragStartY);
                applyTransform();
                return;
            }
            if (scale === 1) {
                const rect = wrapper.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;
                image.style.transformOrigin = `${x}% ${y}%`;
                image.style.transform = 'scale(2.5)';
            }
        });

        wrapper.addEventListener('mouseleave', function() {
            if (!isDragging && scale === 1) {
                image.style.transform = 'scale(1)';
                image.style.transformOrigin = 'center center';
                panX = 0;
                panY = 0;
            }
            isDragging = false;
        });

        wrapper.addEventListener('mousedown', function(e) {
            if (video.style.display !== 'none') return;
            if (scale > 1) {
                isDragging = true;
                dragStartX = e.clientX;
                dragStartY = e.clientY;
                dragOriginX = panX;
                dragOriginY = panY;
                wrapper.style.cursor = 'grabbing';
                e.preventDefault();
            }
        });

        window.addEventListener('mouseup', function() {
            isDragging = false;
            wrapper.style.cursor = scale > 1 ? 'grab' : 'zoom-in';
        });

        wrapper.addEventListener('wheel', function(e) {
            if (video.style.display !== 'none') return;
            e.preventDefault();
            scale = Math.min(5, Math.max(1, scale + (e.deltaY > 0 ? -0.2 : 0.2)));
            if (scale === 1) {
                panX = 0;
                panY = 0;
            }
            applyTransform();
        }, {
            passive: false
        });

        // Touch
        wrapper.addEventListener('touchstart', function(e) {
            if (video.style.display !== 'none') return;
            if (e.touches.length === 2) {
                lastTouchDist = getTouchDist(e.touches);
            } else if (e.touches.length === 1) {
                lastTouchX = e.touches[0].clientX;
                lastTouchY = e.touches[0].clientY;
            }
        }, {
            passive: true
        });

        wrapper.addEventListener('touchmove', function(e) {
            if (video.style.display !== 'none') return;
            e.preventDefault();
            if (e.touches.length === 2) {
                const dist = getTouchDist(e.touches);
                scale = Math.min(5, Math.max(1, scale + (dist - lastTouchDist) * 0.01));
                lastTouchDist = dist;
                if (scale === 1) {
                    panX = 0;
                    panY = 0;
                }
                applyTransform();
            } else if (e.touches.length === 1 && scale > 1) {
                panX += e.touches[0].clientX - lastTouchX;
                panY += e.touches[0].clientY - lastTouchY;
                lastTouchX = e.touches[0].clientX;
                lastTouchY = e.touches[0].clientY;
                applyTransform();
            }
        }, {
            passive: false
        });

        wrapper.addEventListener('touchend', function(e) {
            if (e.touches.length < 2) lastTouchDist = null;
            if (e.touches.length === 0) {
                lastTouchX = null;
                lastTouchY = null;
            }
        });

        function getTouchDist(touches) {
            const dx = touches[0].clientX - touches[1].clientX;
            const dy = touches[0].clientY - touches[1].clientY;
            return Math.sqrt(dx * dx + dy * dy);
        }

        window.currentZoom = scale;
        window._galleryState = {
            get scale() {
                return scale;
            },
            set scale(v) {
                scale = v;
            },
            get panX() {
                return panX;
            },
            set panX(v) {
                panX = v;
            },
            get panY() {
                return panY;
            },
            set panY(v) {
                panY = v;
            },
            applyTransform
        };
    });

    function applyZoom(level) {
        const s = window._galleryState;
        level = Math.min(5, Math.max(1, level));
        s.scale = level;
        if (level === 1) {
            s.panX = 0;
            s.panY = 0;
        }
        s.applyTransform();
    }

    function zoomIn() {
        applyZoom(window._galleryState.scale + 0.5);
    }

    function zoomOut() {
        applyZoom(window._galleryState.scale - 0.5);
    }

    function zoomReset() {
        applyZoom(1);
    }

    function zoomSlider(value) {
        applyZoom(parseFloat(value));
    }

    // Switch to image
    function setMainImage(thumb, src) {
        zoomReset();
        const image = document.getElementById('mainImage');
        const video = document.getElementById('mainVideo');
        const zoomCtrls = document.getElementById('zoomControls');

        // Stop and hide video, show image
        video.pause();
        video.style.display = 'none';
        image.style.display = 'block';
        zoomCtrls.style.display = 'flex';

        image.src = src;
        document.querySelectorAll('.thumb-image').forEach(t => t.classList.remove('active'));
        thumb.classList.add('active');
    }

    // Switch to video
    function setMainVideo(thumb, src) {
        zoomReset();
        const image = document.getElementById('mainImage');
        const video = document.getElementById('mainVideo');
        const zoomCtrls = document.getElementById('zoomControls');

        // Hide image and zoom controls, show video
        image.style.display = 'none';
        zoomCtrls.style.display = 'none';
        video.style.display = 'block';

        document.getElementById('mainVideoSource').src = src;
        video.load();
        video.play();

        document.querySelectorAll('.thumb-image').forEach(t => t.classList.remove('active'));
        thumb.classList.add('active');
    }
</script>
