@extends('layouts.userLayout')
@section('title', t('app.product.product') . ' :: ' . $data['product']->name)
@section('content')

    <div class="desktop-nav-bg"></div>
    <div class="main-content-wrapper">

        <section class="section">
            {{-- category --}}
            @php
                $locale = currentLocale();
                $field = 'category_' . $locale;

                $catName =
                    isset($data['product']->category->$field) && $data['product']->category->$field
                        ? $data['product']->category->$field
                        : $data['product']->category->category;
            @endphp

            {{-- sub category --}}

            @php
                $locale = currentLocale();
                $field = 'sub_category_' . $locale;

                $subCatName =
                    isset($data['product']->subCategory->$field) && $data['product']->subCategory->$field
                        ? $data['product']->subCategory->$field
                        : $data['product']->subCategory->sub_category;
            @endphp

            {{-- product --}}
            @php
                $locale = currentLocale();
                $field = 'name_' . $locale;

                $prodName =
                    isset($data['product']->$field) && $data['product']->$field
                        ? $data['product']->$field
                        : $data['product']->name;
            @endphp

            {{-- breadcomb start --}}
            <div aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a
                            href="{{ route(routePrefix() . 'ViewCategory', ['slug' => $data['product']->category->slug]) }}">{{ $catName }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a
                            href="{{ route(routePrefix() . 'ViewSubCategory', ['slug' => $data['product']->subCategory->slug]) }}">{{ $subCatName }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $prodName }}
                    </li>
                </ol>
            </div>

            {{-- breadcomb end --}}



            <div class="my-page-header">{{ $prodName }}</div>
            <div class="mt-5">
                <div class="row col-md-12">
                    @include('users.admin.products.products.include.productMediaDisplay')
                    <div class="col-md-6">
                        {{-- short description --}}
                        <p>
                            @php
                                $locale = currentLocale();
                                $field = 'short_description_' . $locale;
                                $shortDesc = !empty($data['product']->$field)
                                    ? $data['product']->$field
                                    : $data['product']->short_description;
                            @endphp

                            {{ $shortDesc }}
                        </p>
                        {{-- brand --}}
                        <p>
                            <span class="fw-bold">{{ t('app.product.brand') }}:
                            </span>
                            @if ($data['product']->brand)
                                @php
                                    $logoPath = public_path(
                                        'assets/uploads/products/brands/logo/' . $data['product']->brand->logo,
                                    );
                                @endphp
                                @if ($data['product']->brand->logo && file_exists($logoPath))
                                    <img src="{{ asset('assets/uploads/products/brands/logo/' . $data['product']->brand->logo) }}"
                                        width="50px">
                                @else
                                    <img src="{{ asset('system/images/logo_placeholder.jpg') }}" width="50px">
                                @endif
                                {{ $data['product']->brand->brand }}
                            @else
                                -
                            @endif
                        </p>
                        <div class="my-1" style="display:flex; align-items:center; gap:8px;">

                            @if ($data['product']->colour)
                                <span class="fw-bold">{{ t('app.product.colour') }}:</span>
                                @php
                                    $hex = preg_match('/^#[0-9A-Fa-f]{6}$/', $data['product']->colour->hex_code)
                                        ? $data['product']->colour->hex_code
                                        : '#000000';
                                @endphp


                                <span
                                    style="width:60px; height:25px; background-color: {{ $hex }}; border:1px solid #ccc; display:inline-block;">
                                </span>
                                @php
                                    $locale = currentLocale();
                                    $field = 'name_' . $locale;
                                    $value = !empty($data['product']->colour->$field)
                                        ? $data['product']->colour->$field
                                        : $data['product']->colour->name;
                                @endphp
                                {{ $value }}
                                {{-- <span class="badge bg-soft-primary text-primary cursor-pointer me-2"
                                    onclick="addProductColourFormModal('swal-size-md')">
                                    <i class="feather-edit"></i>
                                </span>
                                <span class="badge bg-soft-danger text-danger cursor-pointer"
                                    onclick="removeProductColourFormModal('swal-size-md')">
                                    <i class="feather-trash"></i>
                                </span> --}}
                            @else
                                {{-- <span class="badge bg-soft-primary text-primary cursor-pointer me-2"
                                    onclick="addProductColourFormModal('swal-size-md')">
                                    <i class="feather-plus"></i>
                                </span> --}}
                            @endif
                        </div>
                        <br>
                        {{-- other attributes --}}
                        <div class="my-1 d-flex align-items-center flex-wrap" style="gap:8px;">
                            @if ($data['product']->attributes->isNotEmpty())
                                <span class="fw-bold">{{ t('app.product.otherAttributes') }}:</span>

                                <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                                    @foreach ($data['product']->attributes as $index => $attribute)
                                        @php
                                            $locale = currentLocale();
                                            $nameField = 'name_' . $locale;
                                            $valueField = 'value_' . $locale;

                                            $attName = !empty($attribute->attribute->$nameField)
                                                ? $attribute->attribute->$nameField
                                                : $attribute->attribute->name;

                                            $attValue = !empty($attribute->value->$valueField)
                                                ? $attribute->value->$valueField
                                                : $attribute->value->value;

                                            // Add comma unless it's the last item
$separator =
    $index == $data['product']->attributes->count() - 1 ? '.' : ',';
                                        @endphp

                                        <span class="attribute-badge">
                                            <strong>{{ $attName }}</strong>:
                                            {{ $attValue }}{{ $separator }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif


                        </div>
                        {{-- Variations --}}
                        @if ($data['product']->activeVariants->isNotEmpty())
                            <div class="section-divider mb-3">
                                <span>{{ t('app.product.variations') }}</span>
                            </div>

                            <div class="row g-4">
                                @foreach ($data['product']->activeVariants as $variant)
                                    @php
                                        $locale = currentLocale();
                                        $field = 'name_' . $locale;
                                        $variantName = !empty($variant->$field) ? $variant->$field : $variant->name;

                                        $hex = preg_match('/^#[0-9A-Fa-f]{6}$/', $variant->colour->hex_code ?? '')
                                            ? $variant->colour->hex_code
                                            : '#000000';

                                        $colourField = 'name_' . $locale;
                                        $colourName = !empty($variant->colour->$colourField)
                                            ? $variant->colour->$colourField
                                            : $variant->colour->name ?? '-';
                                    @endphp

                                    <div class="col-md-6 col-lg-4">
                                        <div class="card h-100 shadow-sm border-0 rounded-3">

                                            {{-- Image Section --}}
                                            <div class="position-relative">
                                                <img src="{{ optional($variant->mediaFile)->file_name
                                                    ? asset('assets/uploads/products/products/media/images/' . $variant->mediaFile->file_name)
                                                    : asset('system/images/icon_placeholder.png') }}"
                                                    alt="{{ $variantName }}" class="card-img-top img-fluid rounded-top"
                                                    style="height:250px; object-fit:cover;">

                                                {{-- Variant Name Overlay --}}
                                                <div class="position-absolute bottom-0 start-0 w-100 px-3 py-2"
                                                    style="background: rgba(0,0,0,0.6);">
                                                    <h6 class="text-white mb-0 fw-semibold">
                                                        {{ $variantName }}
                                                    </h6>
                                                </div>
                                            </div>

                                            {{-- Card Body --}}
                                            <div class="card-body">

                                                {{-- Colour --}}
                                                <div class="d-flex align-items-center mb-3 gap-2">
                                                    <span class="fw-semibold">
                                                        {{ t('app.product.colour') }}:
                                                    </span>

                                                    <span>{{ $colourName }}</span>

                                                    <span
                                                        style="
                                width: 40px;
                                height: 20px;
                                background-color: {{ $hex }};
                                border: 1px solid #ccc;
                                border-radius: 4px;
                                display: inline-block;">
                                                    </span>
                                                </div>

                                                {{-- Other Attributes --}}
                                                <div>
                                                    <span class="fw-semibold d-block mb-2">
                                                        {{ t('app.product.otherAttributes') }}:
                                                    </span>

                                                    @if ($variant->attributes->isNotEmpty())
                                                        <div class="d-flex flex-wrap gap-2">
                                                            @foreach ($variant->attributes as $attribute)
                                                                @php
                                                                    $nameField = 'name_' . $locale;
                                                                    $valueField = 'value_' . $locale;

                                                                    $attName = !empty($attribute->attribute->$nameField)
                                                                        ? $attribute->attribute->$nameField
                                                                        : $attribute->attribute->name;

                                                                    $attValue = !empty($attribute->value->$valueField)
                                                                        ? $attribute->value->$valueField
                                                                        : $attribute->value->value;
                                                                @endphp

                                                                <span class="badge bg-light text-dark border">
                                                                    {{ $attName }}: {{ $attValue }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>

                                                @php
                                                    $modalId = 'quoteModal_' . $variant->id;
                                                @endphp

                                                <button type="button" class="btn btn-warning mt-2" data-bs-toggle="modal"
                                                    data-bs-target="#{{ $modalId }}"
                                                    data-product="{{ $data['product']->slug }}"
                                                    data-variant="{{ $variant->slug }}">
                                                    {{ t('app.product.addToQuote') }}
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>
                {{-- ##################################################### --}}
                {{-- share product --}}
                <br>
                <div class="main-share-wrapper">
                    <div class="main-share-card">
                        <div class="main-share-header">
                            <h3 class="main-share-title">Share this product</h3>
                            {{-- <p class="main-share-subtitle">Send to your community</p> --}}
                        </div>

                        <div class="main-share-url-box">
                            <i class="fas fa-globe main-share-input-icon"></i>
                            <input type="text" id="mainShareUrlInput" class="main-share-input"
                                value="https://producthub.com/item/49201" readonly>
                            <button id="mainShareCopyBtn" class="main-share-copy-btn">
                                <span id="mainShareCopyText">Copy</span>
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>

                        <div class="main-share-grid">
                            <a href="javascript:void(0)" class="main-share-item" data-platform="whatsapp"
                                title="Share on WhatsApp">
                                <div class="main-share-icon-wrap whatsapp"><i class="fab fa-whatsapp"></i></div>
                                <span class="main-share-label">WhatsApp</span>
                            </a>
                            <a href="javascript:void(0)" class="main-share-item" data-platform="facebook"
                                title="Share on Facebook">
                                <div class="main-share-icon-wrap facebook"><i class="fab fa-facebook-f"></i></div>
                                <span class="main-share-label">Facebook</span>
                            </a>
                            <a href="javascript:void(0)" class="main-share-item" data-platform="twitter" title="Share on X">
                                <div class="main-share-icon-wrap twitter">
                                    <div class="main-share-icon-wrap twitter"><i class="fab fa-twitter"></i></div>
                                </div>
                                <span class="main-share-label">Twitter</span>
                            </a>
                            <a href="javascript:void(0)" class="main-share-item" data-platform="linkedin"
                                title="Share on LinkedIn">
                                <div class="main-share-icon-wrap linkedin"><i class="fab fa-linkedin-in"></i></div>
                                <span class="main-share-label">LinkedIn</span>
                            </a>
                            <a href="javascript:void(0)" class="main-share-item" data-platform="email"
                                title="Share via Email">
                                <div class="main-share-icon-wrap email"><i class="fas fa-envelope"></i></div>
                                <span class="main-share-label">Email</span>
                            </a>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const copyBtn = document.getElementById('mainShareCopyBtn');
                        const urlInput = document.getElementById('mainShareUrlInput');
                        const copyText = document.getElementById('mainShareCopyText');

                        // 1. Sync Input with current URL
                        urlInput.value = window.location.href;

                        // 2. Copy to Clipboard
                        copyBtn.addEventListener('click', function() {
                            navigator.clipboard.writeText(urlInput.value).then(() => {
                                // Success Feedback
                                copyText.innerText = "Copied!";
                                copyBtn.style.background = "#10b981";

                                setTimeout(() => {
                                    copyText.innerText = "Copy";
                                    copyBtn.style.background = "#0078d4";
                                }, 2000);
                            });
                        });

                        // 3. Social Share Popups
                        const pageUrl = encodeURIComponent(window.location.href);
                        const pageTitle = encodeURIComponent(document.title);

                        document.querySelectorAll('.main-share-item').forEach(item => {
                            item.addEventListener('click', function(e) {
                                e.preventDefault();
                                const platform = this.getAttribute('data-platform');
                                let shareUrl = "";

                                switch (platform) {
                                    case 'whatsapp':
                                        shareUrl =
                                            `https://api.whatsapp.com/send?text=${pageTitle}%20${pageUrl}`;
                                        break;
                                    case 'facebook':
                                        shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${pageUrl}`;
                                        break;
                                    case 'twitter':
                                        shareUrl =
                                            `https://twitter.com/intent/tweet?text=${pageTitle}&url=${pageUrl}`;
                                        break;
                                    case 'linkedin':
                                        shareUrl =
                                            `https://www.linkedin.com/sharing/share-offsite/?url=${pageUrl}`;
                                        break;
                                    case 'email':
                                        shareUrl =
                                            `mailto:?subject=${pageTitle}&body=Checkout this: ${pageUrl}`;
                                        break;
                                }

                                if (shareUrl) {
                                    window.open(shareUrl, '_blank',
                                        'width=600,height=450,scrollbars=yes,resizable=yes');
                                }
                            });
                        });
                    });
                </script>
                <br>
                {{-- add to quote --}}
                <button type="button" class="btn-3d-quote" data-bs-toggle="modal" data-bs-target="#quoteModal"
                    data-product="{{ $data['product']->slug }}" {{-- data-variant="{{ $variant->slug }}" --}}>
                    <i class="fa-solid fa-file-invoice me-2"></i>
                    {{ t('app.product.addToQuote') }}
                </button>
                <div class="modal fade" id="quoteModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <form action="{{ route(routePrefix() . 'AddToQuote') }}" method="POST">
                                @csrf

                                <input type="hidden" name="product" id="modalProduct">
                                <input type="hidden" name="variant" id="modalVariant">

                                <div class="modal-header">
                                    <h5 class="modal-title">{{ t('app.product.enterQuantity') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <label class="form-label">{{ t('app.product.quantity') }}</label>
                                    <input type="number" name="quantity" class="form-control text-center"
                                        min="1" value="1" required>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success w-100">
                                        {{ t('app.product.confirmAndAddQuote') }}
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {

                        var quoteModal = document.getElementById('quoteModal');

                        quoteModal.addEventListener('show.bs.modal', function(event) {

                            var button = event.relatedTarget;

                            var productSlug = button.getAttribute('data-product');
                            var variantSlug = button.getAttribute('data-variant');

                            document.getElementById('modalProduct').value = productSlug;
                            document.getElementById('modalVariant').value = variantSlug;
                        });

                    });
                </script>



                <hr>
                {{-- ####################################### --}}
                <div class="flex-accordion mt-5">

                    <!-- Headers -->
                    <div class="flex-accordion-headers d-flex flex-wrap justify-content-center">
                        <div class="flex-accordion-header active" data-target="content1">
                            {{ t('app.fullDescription') }}</div>
                        <div class="flex-accordion-header" data-target="content2">
                            {{ t('app.product.reviews') }}</div>
                    </div>

                    <!-- Contents -->
                    <div class="flex-accordion-contents">
                        <div id="content1" class="flex-accordion-content show">

                            <span class="fw-bold">{{ t('app.fullDescription') }}:</span> <br>
                            @php
                                $locale = currentLocale();
                                $field = 'description_' . $locale;
                                $fullDesc = !empty($data['product']->$field)
                                    ? $data['product']->$field
                                    : $data['product']->description;
                            @endphp

                            {!! $fullDesc !!}
                        </div>
                        <div id="content2" class="flex-accordion-content">
                            {{-- prduct reviews start --}}
                            <section class="main-rev-section">
                                <div class="main-rev-container">

                                    <div class="main-rev-form-wrap mt-5 mb-5">
                                        <div class="main-rev-header">
                                            <h2 class="main-rev-title">Customer Reviews</h2>
                                            <p class="main-rev-subtitle">Your opinion matters to us and the community.</p>
                                        </div>

                                        <form id="mainRevForm" class="main-rev-card">
                                            <div class="main-rev-rating-input">
                                                <span class="main-rev-label">Select Rating:</span>
                                                <div class="main-rev-stars">
                                                    <input type="radio" name="rating" id="r5"
                                                        value="5"><label for="r5">★</label>
                                                    <input type="radio" name="rating" id="r4"
                                                        value="4"><label for="r4">★</label>
                                                    <input type="radio" name="rating" id="r3"
                                                        value="3"><label for="r3">★</label>
                                                    <input type="radio" name="rating" id="r2"
                                                        value="2"><label for="r2">★</label>
                                                    <input type="radio" name="rating" id="r1"
                                                        value="1"><label for="r1">★</label>
                                                </div>
                                            </div>

                                            <div class="main-rev-row">
                                                <input type="text" class="main-rev-input" placeholder="Your Name"
                                                    required>
                                                <input type="email" class="main-rev-input" placeholder="Your Email"
                                                    required>
                                            </div>

                                            <textarea class="main-rev-input" rows="3" placeholder="Share your thoughts about the product..." required></textarea>

                                            <button type="submit" class="main-rev-btn">
                                                Submit Review <i class="fas fa-chevron-right"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="main-rev-slider-wrap">
                                        <!-- No owl-carousel class here — initialized exclusively by ID in JS below -->
                                        <div id="mainRevOwlSlider">

                                            <div class="main-rev-item">
                                                <div class="main-rev-score">★★★★★</div>
                                                <p class="main-rev-comment">"Excellent durability and precision. This
                                                    exceeded all my expectations for industrial use."</p>
                                                <div class="main-rev-meta">
                                                    <div class="main-rev-avatar">JD</div>
                                                    <div>
                                                        <h4 class="main-rev-name">James Dalton</h4>
                                                        <span class="main-rev-status">Verified Purchase</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="main-rev-item">
                                                <div class="main-rev-score">★★★★☆</div>
                                                <p class="main-rev-comment">"The integration was seamless. Docking one star
                                                    only because shipping took an extra day."</p>
                                                <div class="main-rev-meta">
                                                    <div class="main-rev-avatar" style="background: #10b981;">MK</div>
                                                    <div>
                                                        <h4 class="main-rev-name">Maria Khan</h4>
                                                        <span class="main-rev-status">Verified Purchase</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="main-rev-item">
                                                <div class="main-rev-score">★★★★★</div>
                                                <p class="main-rev-comment">"Top tier support team. They helped me
                                                    configure the automation settings in minutes."</p>
                                                <div class="main-rev-meta">
                                                    <div class="main-rev-avatar" style="background: #f59e0b;">AL</div>
                                                    <div>
                                                        <h4 class="main-rev-name">Alex Lee</h4>
                                                        <span class="main-rev-status">Expert Reviewer</span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </section>
                            {{-- product review ends --}}
                        </div>
                    </div>

                </div>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {

                        const headers = document.querySelectorAll(".flex-accordion-header");
                        const contents = document.querySelectorAll(".flex-accordion-content");

                        headers.forEach(header => {
                            header.addEventListener("click", function() {

                                // Remove active class from all headers
                                headers.forEach(h => h.classList.remove("active"));
                                // Hide all contents
                                contents.forEach(c => c.classList.remove("show"));

                                // Activate clicked header
                                this.classList.add("active");
                                // Show its content
                                const targetId = this.getAttribute("data-target");
                                document.getElementById(targetId).classList.add("show");

                            });
                        });

                    });
                </script>
                {{-- ########################################## --}}
            </div>



            {{-- ############################# --}}
            {{-- Add product variant to quote --}}
            @if ($data['product']->activeVariants->isNotEmpty())

                @foreach ($data['product']->activeVariants as $variant)
                    @php
                        $modalId = 'quoteModal_' . $variant->id;
                    @endphp

                    {{-- <button type="button" class="btn btn-warning mt-2" data-bs-toggle="modal"
                        data-bs-target="#{{ $modalId }}" data-product="{{ $data['product']->slug }}"
                        data-variant="{{ $variant->slug }}">
                        {{ t('app.product.addToQuote') }}
                    </button> --}}

                    <div class="modal fade" id="{{ $modalId }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">

                                <form action="{{ route(routePrefix() . 'AddToQuote') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="product" id="modalProduct_{{ $variant->id }}">
                                    <input type="hidden" name="variant" id="modalVariant_{{ $variant->id }}">

                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            {{ t('app.product.enterQuantity') }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <label class="form-label">
                                            {{ t('app.product.quantity') }}
                                        </label>
                                        <input type="number" name="quantity" class="form-control text-center"
                                            min="1" value="1" required>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success w-100">
                                            {{ t('app.product.confirmAndAddQuote') }}
                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {

                            var modal = document.getElementById('{{ $modalId }}');

                            if (modal) {
                                modal.addEventListener('show.bs.modal', function(event) {

                                    var button = event.relatedTarget;

                                    var productSlug = button.getAttribute('data-product');
                                    var variantSlug = button.getAttribute('data-variant');

                                    document.getElementById('modalProduct_{{ $variant->id }}').value = productSlug;
                                    document.getElementById('modalVariant_{{ $variant->id }}').value = variantSlug;
                                });
                            }

                        });
                    </script>
                @endforeach
            @endif

            {{-- ###################### --}}



        </section>
    </div>





@endsection
