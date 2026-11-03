@extends('layouts.adminLayout')
@section('title', t('app.buttons.view') . '::' . $data['product']->name)
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10"><a href="#">{{ $data['product']->name }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><a href="#">{{ t('app.product.products') }}</a></li>
                    <li class="breadcrumb-item"><a href="#"><a href="#">{{ t('app.product.product') }}</a>
                    </li>
                    <li class="breadcrumb-item">{{ t('app.buttons.view') }}</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex d-md-none">
                        <a href="javascript:void(0)" class="page-header-right-close-toggle">
                            <i class="feather-arrow-left me-2"></i>
                            <span>{{ t('app.buttons.back') }}</span>
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('addProductVariant', ['slug' => $data['product']->slug]) }}"
                            class="btn btn-primary">
                            <i class="feather-plus me-2"></i>
                            <span>{{ t('app.product.addProductVariant') }}</span>
                        </a>
                    </div>
                </div>
                <div class="d-md-none d-flex align-items-center">
                    <a href="javascript:void(0)" class="page-header-right-open-toggle">
                        <i class="feather-align-right fs-20"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- [ page-header ] end -->
        <div class="bg-white py-3 border-bottom rounded-0 p-md-0 mb-0">
            <div class="d-md-none d-flex">
                <a href="javascript:void(0)" class="page-content-left-open-toggle">
                    <i class="feather-align-left fs-20"></i>
                </a>
            </div>
        </div>
        <!-- [ Main Content ] start -->
        <div class="main-content">
            <div class="tab-content">
                <div class="row col-md-12">
                    {{-- inner nav start --}}
                    <div class="col-md-2">
                        @include('users.admin.products.products.include.inner_nav')
                    </div>
                    {{-- Inner nav end --}}
                    <div class="tab-pane fade show active col-md-10" id="profileTab" role="tabpanel">
                        <div class="card card-body lead-info">
                            <div class="mb-4 d-flex align-items-center justify-content-between ps-5">
                                <h5 class="fw-bold mb-0">
                                    <span class="d-block mb-2">{{ t('app.product.product') }} ::

                                        @php
                                            $locale = currentLocale();
                                            $field = 'name_' . $locale;
                                            $value = !empty($data['product']->$field)
                                                ? $data['product']->$field
                                                : $data['product']->name;
                                        @endphp

                                        {{ $value }}
                                    </span>
                                    {{-- <span class="fs-12 fw-normal text-muted d-block">Following information for your lead</span> --}}
                                </h5>
                            </div>
                            <div class="row col-md-12">
                                @include('users.admin.products.products.include.productMediaDisplay')



                                <div class="col-md-8 ps-5">
                                    <p><span class="fw-bold">{{ t('app.product.productName') }}:
                                        </span>
                                        @php
                                            $locale = currentLocale();
                                            $field = 'name_' . $locale;
                                            $value = !empty($data['product']->$field)
                                                ? $data['product']->$field
                                                : $data['product']->name;
                                        @endphp

                                        {{ $value }}
                                    </p>
                                    <p><span class="fw-bold">{{ t('app.product.category') }}/{{ t('app.product.subCategory') }}:
                                        </span>
                                        @php
                                            $locale = currentLocale();
                                            $field = 'category_' . $locale;
                                            $cat = !empty($data['product']->category->$field)
                                                ? $data['product']->category->$field
                                                : $data['product']->category->category;
                                        @endphp

                                        {{ $cat }}/@php
                                            $locale = currentLocale();
                                            $field = 'sub_category_' . $locale;
                                            $subCat = !empty($data['product']->subCategory->$field)
                                                ? $data['product']->subCategory->$field
                                                : $data['product']->subCategory->sub_category;
                                        @endphp

                                        {{ $subCat }}
                                    </p>
                                    <div class="row col-md-12">
                                        <div class="col-md-6">
                                            <p><span class="fw-bold">{{ t('app.product.brand') }}:
                                                </span>
                                                @if ($data['product']->brand)
                                                    @php
                                                        $logoPath = public_path(
                                                            'assets/uploads/products/brands/logo/' .
                                                                $data['product']->brand->logo,
                                                        );
                                                    @endphp
                                                    @if ($data['product']->brand->logo && file_exists($logoPath))
                                                        <img src="{{ asset('assets/uploads/products/brands/logo/' . $data['product']->brand->logo) }}"
                                                            width="30px">
                                                    @else
                                                        <img src="{{ asset('system/images/logo_placeholder.jpg') }}"
                                                            width="30px">
                                                    @endif
                                                    {{ $data['product']->brand->brand }}
                                                @else
                                                    -
                                                @endif
                                            </p>
                                            <p><span class="fw-bold">{{ t('app.product.sku') }}:
                                                </span>
                                                {{ $data['product']->sku }}
                                            </p>
                                            <p><span class="fw-bold">{{ t('app.product.price') }}:
                                                </span>
                                                @if (!is_null($data['product']->price) && $data['product']->price != 0)
                                                    ₦{{ number_format($data['product']->price, 2) }}
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p> <span class="fw-bold">{{ t('app.product.availability') }}:
                                                </span>
                                                @switch($data['product']->is_available)
                                                    @case(true)
                                                        <span
                                                            class="badge bg-soft-success text-success">{{ t('app.status.available') }}</span>
                                                    @break

                                                    @default
                                                        <span
                                                            class="badge bg-soft-danger text-danger">{{ t('app.status.notAvailable') }}</span>
                                                @endswitch
                                            </p>
                                            <p><span class="fw-bold">{{ t('app.published') }}:
                                                </span>
                                                @switch($data['product']->is_active)
                                                    @case(true)
                                                        <span
                                                            class="badge bg-soft-success text-success">{{ t('app.status.true') }}</span>
                                                    @break

                                                    @default
                                                        <span
                                                            class="badge bg-soft-danger text-danger">{{ t('app.status.false') }}</span>
                                                @endswitch
                                            </p>
                                            <p> <span class="fw-bold">{{ t('app.product.featured') }}:
                                                </span>
                                                @switch($data['product']->is_featured)
                                                    @case(true)
                                                        <span
                                                            class="badge bg-soft-success text-success">{{ t('app.status.true') }}</span>
                                                    @break

                                                    @default
                                                        <span
                                                            class="badge bg-soft-danger text-danger">{{ t('app.status.false') }}</span>
                                                @endswitch
                                            </p>

                                        </div>
                                        <p><span class="fw-bold">{{ t('app.shortDescription') }}: <br>
                                            </span>
                                            @php
                                                $locale = currentLocale();
                                                $field = 'short_description_' . $locale;
                                                $shortDesc = !empty($data['product']->$field)
                                                    ? $data['product']->$field
                                                    : $data['product']->short_description;
                                            @endphp

                                            {{ $shortDesc }}
                                        </p>
                                    </div>


                                    <div class="section-divider">
                                        <span>{{ t('app.settings.seo.seo') }}</span>
                                    </div>
                                    <p><span class="fw-bold">{{ t('app.settings.seo.metaTitle') }}: <br>
                                        </span>
                                        @php
                                            $locale = currentLocale();
                                            $field = 'meta_title_' . $locale;
                                            $metaTitle = !empty($data['product']->$field)
                                                ? $data['product']->$field
                                                : $data['product']->meta_title;
                                        @endphp

                                        {{ $metaTitle }}
                                    </p>
                                    <p><span class="fw-bold">{{ t('app.settings.seo.metaKeywords') }}: <br>
                                        </span>
                                        @php
                                            $locale = currentLocale();
                                            $field = 'meta_keywords_' . $locale;
                                            $metaKeywords = !empty($data['product']->$field)
                                                ? $data['product']->$field
                                                : $data['product']->meta_keywords;
                                        @endphp

                                        {{ $metaKeywords }}
                                    </p>
                                    <p><span class="fw-bold">{{ t('app.settings.seo.metaDescription') }}: <br>
                                        </span>
                                        @php
                                            $locale = currentLocale();
                                            $field = 'meta_description_' . $locale;
                                            $metaKeywords = !empty($data['product']->$field)
                                                ? $data['product']->$field
                                                : $data['product']->meta_description;
                                        @endphp

                                        {{ $metaKeywords }}
                                    </p>
                                    <div class="">
                                        <div class="section-divider">
                                            <span>{{ t('app.product.mediaFiles') }}</span>
                                        </div>
                                        <section class="manage-media-files">
                                            @foreach ($data['product']->mediaFiles as $index => $media)
                                                @if ($media->file_type === 'video')
                                                    @php $filePath = asset('assets/uploads/products/products/media/videos/' . $media->file_name); @endphp
                                                    <div class="manage-media-item"
                                                        onclick="openUpdateMediaModal({{ $media->id }}, 'video', '{{ $filePath }}', {{ $media->sort_order }}, {{ $media->is_primary ? 1 : 0 }})">
                                                        <div class="manage-media-video">
                                                            <div class="play-icon"><i class="feather-play"></i></div>
                                                        </div>
                                                        <div class="manage-media-overlay"><span><i
                                                                    class="feather-edit"></i></span></div>
                                                    </div>
                                                @else
                                                    @php $filePath = asset('assets/uploads/products/products/media/images/' . $media->file_name); @endphp
                                                    <div class="manage-media-item"
                                                        onclick="openUpdateMediaModal({{ $media->id }}, 'image', '{{ $filePath }}', {{ $media->sort_order }}, {{ $media->is_primary ? 1 : 0 }})">
                                                        <img src="{{ $filePath }}" class="manage-media-img">
                                                        <div class="manage-media-overlay"><span><i
                                                                    class="feather-edit"></i></span></div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </section>
                                        @include('users.admin.products.products.include.updateMediaFileModal')
                                        <div
                                            class="d-flex justify-content-end align-items-center gap-2 page-header-right-items-wrapper">
                                            <button onclick="addMediaFileFormModal('swal-size-md')" class="btn btn-primary">
                                                <i class="feather-plus me-2"></i>
                                                <span>{{ t('app.product.addMediaFile') }}</span>
                                            </button>
                                        </div>
                                    </div>


                                    {{-- ########################################### --}}
                                    {{-- add to quote --}}
                                    <div class="">
                                        <div class="section-divider">
                                            <span>{{ t('app.product.addToQuote') }}</span>
                                        </div>

                                        <form id="swalForm" method="POST" action="{{ route('adminAddProductToQuote') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row col-md-12">
                                                <input type="hidden" class="form-control" name="product" value="{{ $data['product']->slug }}" required>
                                                <input type="hidden" class="form-control" name="variant" value="" required>


                                                <div class="mb-1 col-md-6">
                                                    <label class="form-label">
                                                        {{ t('app.product.quote') }} ({{ t('app.draft') }})
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <select class="form-select text-center" name="quote_number" required>
                                                        <option value="" disabled selected>
                                                            - {{ t('app.select') }} -
                                                        </option>

                                                        @if ($data['temporaryQuotes']->isNotEmpty())
                                                            @foreach ($data['temporaryQuotes'] as $quote)
                                                                <option value="{{ $quote->quote_number }}" class="text-uppercase"
                                                                    {{ old('quote_number') == $quote->quote_number ? 'selected' : '' }}>
                                                                    <span class="text-uppercase">{{ $quote->quote_number }}</span>
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <p class="text-danger">{{ $errors->first('quote_number') }}</p>
                                                </div>

                                                <div class="mb-1 col-md-4">
                                                    <label class="form-label">{{ t('app.product.quantity') }} <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="quantity" value="{{ old('quantity') }}" required>
                                                    <p class="text-danger">{{ $errors->first('quantity') }}
                                                    </p>
                                                </div>

                                                <div class="mt-4 col-md-2">
                                                    <button type="submit" class="btn btn-warning w-100 mt-2">{{ t('app.buttons.submit') }}</button>
                                                </div>


                                            </div>


                                        </form>
                                    </div>
                                    <hr>
                                    {{-- ########################################### --}}
                                    <section class="product-variation">
                                        <div class="container-fluid">

                                            @if ($data['product']->variants->isNotEmpty())
                                                @foreach ($data['product']->variants as $variant)
                                                    @php
                                                        $locale = currentLocale();
                                                        $field = 'name_' . $locale;
                                                        $variantName = !empty($variant->$field)
                                                            ? $variant->$field
                                                            : $variant->name;
                                                    @endphp

                                                    <a href="{{ route('viewProductVariant', ['slug' => $variant->slug]) }}"
                                                        class="variant-card-link text-decoration-none text-dark">
                                                        <div class="card variant-card mb-4 border-0 shadow-sm">

                                                            <div class="card-body">
                                                                <div class="row align-items-center">

                                                                    {{-- IMAGE --}}
                                                                    <div class="col-md-4 text-center mb-3 mb-md-0">
                                                                        <img src="{{ optional($variant->mediaFile)->file_name
                                                                            ? asset('assets/uploads/products/products/media/images/' . $variant->mediaFile->file_name)
                                                                            : asset('system/images/icon_placeholder.png') }}"
                                                                            alt="{{ $variantName }}"
                                                                            class="img-fluid rounded border variant-image">
                                                                    </div>

                                                                    {{-- DETAILS --}}
                                                                    <div class="col-md-8">

                                                                        <h5 class="fw-bold mb-3">
                                                                            {{ $variantName }}
                                                                        </h5>

                                                                        <div class="row">

                                                                            {{-- LEFT COLUMN --}}
                                                                            <div class="col-md-6">

                                                                                <p class="mb-2">
                                                                                    <span
                                                                                        class="fw-semibold">{{ t('app.product.sku') }}:</span>
                                                                                    <span
                                                                                        class="text-uppercase">{{ $variant->sku }}</span>
                                                                                </p>

                                                                                <p class="mb-2">
                                                                                    <span
                                                                                        class="fw-semibold">{{ t('app.product.price') }}:</span>
                                                                                    @if (!is_null($variant->price))
                                                                                        <span class="text-success fw-bold">
                                                                                            ₦{{ number_format($variant->price, 2) }}
                                                                                        </span>
                                                                                    @endif
                                                                                </p>

                                                                                <p class="mb-2">
                                                                                    <span
                                                                                        class="fw-semibold">{{ t('app.published') }}:</span>
                                                                                    <span
                                                                                        class="badge {{ $variant->is_active ? 'bg-success' : 'bg-danger' }}">
                                                                                        {{ $variant->is_active ? t('app.status.true') : t('app.status.false') }}
                                                                                    </span>
                                                                                </p>

                                                                                <p class="mb-0">
                                                                                    <span
                                                                                        class="fw-semibold">{{ t('app.product.availability') }}:</span>
                                                                                    <span
                                                                                        class="badge {{ $variant->is_available ? 'bg-success' : 'bg-danger' }}">
                                                                                        {{ $variant->is_available ? t('app.status.available') : t('app.status.notAvailable') }}
                                                                                    </span>
                                                                                </p>

                                                                            </div>

                                                                            {{-- RIGHT COLUMN --}}
                                                                            <div class="col-md-6">

                                                                                {{-- COLOUR --}}
                                                                                <p class="mb-3">
                                                                                    <span
                                                                                        class="fw-semibold">{{ t('app.product.colour') }}:</span>

                                                                                    @if ($variant->colour)
                                                                                        @php
                                                                                            $hex = preg_match(
                                                                                                '/^#[0-9A-Fa-f]{6}$/',
                                                                                                $variant->colour
                                                                                                    ->hex_code,
                                                                                            )
                                                                                                ? $variant->colour
                                                                                                    ->hex_code
                                                                                                : '#000000';

                                                                                            $colourField =
                                                                                                'name_' . $locale;
                                                                                            $colourName = !empty(
                                                                                                $variant->colour
                                                                                                    ->$colourField
                                                                                            )
                                                                                                ? $variant->colour
                                                                                                    ->$colourField
                                                                                                : $variant->colour
                                                                                                    ->name;
                                                                                        @endphp

                                                                                        <span
                                                                                            style="width:60px; height:25px; background-color: {{ $hex }}; border:1px solid #ccc; display:inline-block;">
                                                                                        </span>
                                                                                        {{-- {{ $colourName }} --}}
                                                                                    @else
                                                                                        -
                                                                                    @endif
                                                                                </p>

                                                                                {{-- ATTRIBUTES --}}
                                                                                <div>
                                                                                    <span class="fw-semibold d-block mb-2">
                                                                                        {{ t('app.product.otherAttributes') }}:
                                                                                    </span>

                                                                                    @if ($variant->attributes->isNotEmpty())
                                                                                        <div
                                                                                            class="d-flex flex-wrap gap-2">
                                                                                            @foreach ($variant->attributes as $attribute)
                                                                                                @php
                                                                                                    $nameField =
                                                                                                        'name_' .
                                                                                                        $locale;
                                                                                                    $valueField =
                                                                                                        'value_' .
                                                                                                        $locale;

                                                                                                    $attName = !empty(
                                                                                                        $attribute
                                                                                                            ->attribute
                                                                                                            ->$nameField
                                                                                                    )
                                                                                                        ? $attribute
                                                                                                            ->attribute
                                                                                                            ->$nameField
                                                                                                        : $attribute
                                                                                                            ->attribute
                                                                                                            ->name;

                                                                                                    $attValue = !empty(
                                                                                                        $attribute
                                                                                                            ->value
                                                                                                            ->$valueField
                                                                                                    )
                                                                                                        ? $attribute
                                                                                                            ->value
                                                                                                            ->$valueField
                                                                                                        : $attribute
                                                                                                            ->value
                                                                                                            ->value;
                                                                                                @endphp

                                                                                                <span
                                                                                                    class="attribute-badge">
                                                                                                    {{ $attName }}:
                                                                                                    {{ $attValue }}
                                                                                                </span>
                                                                                            @endforeach
                                                                                        </div>
                                                                                    @else
                                                                                        <span>-</span>
                                                                                    @endif
                                                                                </div>

                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            @endif

                                        </div>
                                    </section>



                                    {{-- ############################### --}}
                                    <div class="flex-accordion">

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
                                                <div class="my-1" style="display:flex; align-items:center; gap:8px;">

                                                    <span class="fw-bold">{{ t('app.product.colour') }}:</span>
                                                    @if ($data['product']->colour)
                                                        @php
                                                            $hex = preg_match(
                                                                '/^#[0-9A-Fa-f]{6}$/',
                                                                $data['product']->colour->hex_code,
                                                            )
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
                                                        <span
                                                            class="badge bg-soft-primary text-primary cursor-pointer me-2"
                                                            onclick="addProductColourFormModal('swal-size-md')">
                                                            <i class="feather-edit"></i>
                                                        </span>
                                                        <span class="badge bg-soft-danger text-danger cursor-pointer"
                                                            onclick="removeProductColourFormModal('swal-size-md')">
                                                            <i class="feather-trash"></i>
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge bg-soft-primary text-primary cursor-pointer me-2"
                                                            onclick="addProductColourFormModal('swal-size-md')">
                                                            <i class="feather-plus"></i>
                                                        </span>
                                                    @endif
                                                </div>
                                                <br>
                                                {{-- other attributes --}}
                                                <div class="my-1 d-flex align-items-center flex-wrap" style="gap:8px;">
                                                    <span class="fw-bold">{{ t('app.product.otherAttributes') }}:</span>

                                                    @if ($data['product']->attributes->isNotEmpty())
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
    $index ==
    $data['product']->attributes->count() - 1
        ? '.'
        : ',';
                                                                @endphp

                                                                <span class="attribute-badge">
                                                                    <strong>{{ $attName }}</strong>:
                                                                    {{ $attValue }}{{ $separator }}
                                                                </span>
                                                            @endforeach
                                                        </div>

                                                        {{-- remove attributes --}}
                                                        <span class="badge bg-soft-danger text-danger cursor-pointer"
                                                            onclick="removeProductAttributeFormModal('swal-size-md')">
                                                            <i class="feather-trash"></i>
                                                        </span>
                                                    @else
                                                        <span>-</span>
                                                    @endif

                                                    <span class="badge bg-soft-primary text-primary cursor-pointer"
                                                        onclick="addProductAttributeFormModal('swal-size-md')">
                                                        <i class="feather-plus"></i>
                                                    </span>
                                                </div>

                                                <br>

                                                <br>

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
                                                <p>This is content for Header 2. Hidden initially.</p>
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
                                    {{-- ########################## --}}
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    <a href="{{ route('editProduct', ['slug' => $data['product']->slug]) }}?language=en"
                                        class="btn btn-primary">
                                        <i class="feather-edit me-2"></i> {{ t('app.buttons.edit') }}
                                    </a>
                                </div>


                            </div>

                        </div>
                        {{-- Back Home --}}
                        <div class="row mb-4 justify-content-center">
                            <div class="col-auto">
                                <a class="btn btn-primary" href="{{ route('products') }}">
                                    <i class="fas fa-chevron-left"></i> &nbsp;
                                    {{ t('app.buttons.back') }}
                                </a>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>


    {{-- modals start --}}
    @include('users.admin.products.products.include.addProductColourModal')
    @include('users.admin.products.products.include.removeProductColourModal')

    @include('users.admin.products.products.include.addProductAttributeModal')
    @include('users.admin.products.products.include.removeProductAttributeModal')

    @include('users.admin.products.products.include.addMediaFileModal')

    {{-- modals end --}}

@endsection
