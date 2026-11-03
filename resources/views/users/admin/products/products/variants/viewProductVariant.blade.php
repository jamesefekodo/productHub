@extends('layouts.adminLayout')
@section('title', t('app.buttons.view') . '::' . $data['variant']->name)
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10"><a href="#">{{ $data['variant']->name }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><a href="#">{{ t('app.product.products') }}</a></li>
                    <li class="breadcrumb-item"><a href="#"><a href="#">{{ t('app.product.variant') }}</a>
                    </li>
                    <li class="breadcrumb-item">{{ t('app.buttons.view') }}</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex d-md-none">
                        <a href="{{ route('productVariations', ['slug' => $data['product']->slug]) }}"
                            class="page-header-right-close-toggle">
                            <i class="feather-arrow-left me-2"></i>
                            <span>{{ t('app.buttons.back') }}</span>
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
                                            $value = !empty($data['variant']->$field)
                                                ? $data['variant']->$field
                                                : $data['variant']->name;
                                        @endphp

                                        {{ $value }}
                                    </span>
                                    {{-- <span class="fs-12 fw-normal text-muted d-block">Following information for your lead</span> --}}
                                </h5>
                            </div>
                            <div class="row col-md-12">
                                @include('users.admin.products.products.include.productMediaDisplay')


                                <div class="col-md-8 ps-5">
                                    <h4 class="fw-bold">{{ t('app.product.parentProduct') }}</h4>
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
                                    <hr>
                                    {{-- add variations --}}

                                    <section class="product-variation">
                                        <h4 class="fw-bold">{{ t('app.product.variant') }}</h4>
                                        <div class="row col-md-12">
                                            <div class="col-md-5">
                                                <div class="position-relative">

                                                    <img src="{{ optional($data['variant']->mediaFile)->file_name
                                                        ? asset('assets/uploads/products/products/media/images/' . $data['variant']->mediaFile->file_name)
                                                        : asset('system/images/icon_placeholder.png') }}"
                                                        alt="{{ $data['variant']->name }}"
                                                        class="img-fluid w-100 rounded border"
                                                        style="object-fit:contain; max-height:400px;">

                                                    <button type="button" class="btn btn-sm btn-primary position-absolute"
                                                        style="bottom: 10px; right: 10px;"
                                                        onclick="openVariantImageModal(
                                                            '{{ $data['variant']->mediaFile->id ?? '' }}',
                                                            '{{ optional($data['variant']->mediaFile)->file_name
                                                                ? asset('assets/uploads/products/products/media/images/' . $data['variant']->mediaFile->file_name)
                                                                : asset('system/images/icon_placeholder.png') }}'
                                                        )">
                                                        <i class="feather-edit me-2"></i>
                                                    </button>
                                                </div>
                                                {{-- update image script start  --}}
                                                @include('users.admin.products.products.variants.include.updateMediaFile')
                                                {{-- update image script end --}}
                                            </div>


                                            <div class="col-md-7">
                                                <p><span class="fw-bold">{{ t('app.name') }}:
                                                    </span>
                                                    @php
                                                        $locale = currentLocale();
                                                        $field = 'name_' . $locale;
                                                        $value = !empty($data['variant']->$field)
                                                            ? $data['variant']->$field
                                                            : $data['variant']->name;
                                                    @endphp

                                                    {{ $value }}
                                                </p>
                                                <p><span class="fw-bold">{{ t('app.product.sku') }}:
                                                    </span>
                                                    <span class="text-uppercase">{{ $data['variant']->sku }}</span>
                                                </p>
                                                <p><span class="fw-bold">{{ t('app.product.price') }}:
                                                    </span>
                                                    @if (!is_null($data['variant']->price))
                                                        ₦{{ number_format($data['variant']->price, 2) }}
                                                    @endif
                                                </p>
                                                <p><span class="fw-bold">{{ t('app.published') }}:
                                                    </span>
                                                    <span
                                                        class="badge {{ $data['variant']->is_active ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}"
                                                        data-type="active"
                                                        data-value="{{ $data['variant']->is_active ? 1 : 0 }}">
                                                        {{ $data['variant']->is_active ? t('app.status.true') : t('app.status.false') }}
                                                    </span>
                                                </p>
                                                <p><span class="fw-bold">{{ t('app.product.availability') }}:
                                                    </span>
                                                    <span
                                                        class="badge {{ $data['variant']->is_available ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}"
                                                        data-type="available"
                                                        data-value="{{ $data['variant']->is_available ? 1 : 0 }}">
                                                        {{ $data['variant']->is_available ? t('app.status.available') : t('app.status.notAvailable') }}
                                                    </span>
                                                </p>
                                                <div class="my-1" style="display:flex; align-items:center; gap:8px;">

                                                    <span class="fw-bold">{{ t('app.product.colour') }}:</span>
                                                    @if ($data['variant']->colour)
                                                        @php
                                                            $hex = preg_match(
                                                                '/^#[0-9A-Fa-f]{6}$/',
                                                                $data['variant']->colour->hex_code,
                                                            )
                                                                ? $data['variant']->colour->hex_code
                                                                : '#000000';
                                                        @endphp


                                                        <span
                                                            style="width:60px; height:25px; background-color: {{ $hex }}; border:1px solid #ccc; display:inline-block;">
                                                        </span>
                                                        @php
                                                            $locale = currentLocale();
                                                            $field = 'name_' . $locale;
                                                            $value = !empty($data['variant']->colour->$field)
                                                                ? $data['variant']->colour->$field
                                                                : $data['variant']->colour->name;
                                                        @endphp
                                                        {{ $value }}
                                                        <span class="badge bg-soft-primary text-primary cursor-pointer me-2"
                                                            onclick="addProductVariantColourFormModal('swal-size-md')">
                                                            <i class="feather-edit"></i>
                                                        </span>
                                                        <span class="badge bg-soft-danger text-danger cursor-pointer"
                                                            onclick="removeProductVariantColourFormModal('swal-size-md')">
                                                            <i class="feather-trash"></i>
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge bg-soft-primary text-primary cursor-pointer me-2"
                                                            onclick="addProductVariantColourFormModal('swal-size-md')">
                                                            <i class="feather-plus"></i>
                                                        </span>
                                                    @endif
                                                </div>
                                                {{-- other attributes --}}
                                                <div class="my-1 d-flex align-items-center flex-wrap" style="gap:8px;">
                                                    <span class="fw-bold">{{ t('app.product.otherAttributes') }}:</span>

                                                    @if ($data['variant']->attributes->isNotEmpty())
                                                        <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                                                            @foreach ($data['variant']->attributes as $index => $attribute)
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
    $data['variant']->attributes->count() - 1
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
                                                        onclick="addProductVariantAttributeFormModal('swal-size-md')">
                                                        <i class="feather-plus"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    <a href="{{ route('editProductVariant', ['slug' => $data['variant']->slug]) }}?language=en"
                                        class="btn btn-primary">
                                        <i class="feather-edit me-2"></i> {{ t('app.buttons.edit') }}
                                    </a>
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
                                            <input type="hidden" class="form-control" name="product"
                                                value="{{ $data['product']->slug }}" required>
                                            <input type="hidden" class="form-control" name="variant" value="{{ $data['variant']->slug }}"
                                                required>


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
                                                            <option value="{{ $quote->quote_number }}"
                                                                class="text-uppercase"
                                                                {{ old('quote_number') == $quote->quote_number ? 'selected' : '' }}>
                                                                <span
                                                                    class="text-uppercase">{{ $quote->quote_number }}</span>
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <p class="text-danger">{{ $errors->first('quote_number') }}</p>
                                            </div>

                                            <div class="mb-1 col-md-4">
                                                <label class="form-label">{{ t('app.product.quantity') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="{{ old('quantity') }}" name="quantity" required>
                                                <p class="text-danger">{{ $errors->first('quantity') }}
                                                </p>
                                            </div>

                                            <div class="mt-4 col-md-2">
                                                <button type="submit"
                                                    class="btn btn-warning w-100 mt-2">{{ t('app.buttons.submit') }}</button>
                                            </div>


                                        </div>


                                    </form>
                                </div>

                            </div>

                        </div>
                        {{-- Back Home --}}
                        <div class="row mb-4 justify-content-center">
                            <div class="col-auto">
                                <a class="btn btn-primary"
                                    href="{{ route('productVariations', ['slug' => $data['product']->slug]) }}">
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
    @include('users.admin.products.products.variants.include.addProductVariantColourModal')
    @include('users.admin.products.products.variants.include.removeProductVariantColourModal')


    @include('users.admin.products.products.variants.include.addProductVariantAttributeModal')
    @include('users.admin.products.products.variants.include.removeProductVarianceAttributeModal')

@endsection
