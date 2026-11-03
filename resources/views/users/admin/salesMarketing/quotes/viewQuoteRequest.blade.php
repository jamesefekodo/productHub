@extends('layouts.adminLayout')
@section('title', t('app.buttons.view') . '::' . $data['quote']->slug)
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10"><a href="#">{{ t('app.product.quotes') }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><a
                                href="#">{{ t('app.sales.sales') }}/{{ t('app.sales.marketing') }}</a></li>
                    <li class="breadcrumb-item"><a href="#"><a href="#">{{ t('app.product.quotes') }}</a>
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
                        <button onclick="addSubCategoryFormModal('swal-size-md')" class="btn btn-primary">
                            <i class="feather-plus me-2"></i>
                            <span>{{ t('app.product.addSubCategory') }}</span>
                        </button>
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
                    <div class="tab-pane fade show active" id="profileTab" role="tabpanel">
                        <div class="card card-body lead-info">
                            <div class="mb-4 d-flex align-items-center justify-content-between ps-5">
                                <h5 class="fw-bold mb-0">
                                    <span class="d-block mb-2">{{ t('app.product.quote') }} ::
                                        <span class="text-uppercase">{{ $data['quote']->slug }}</span>
                                    </span>
                                    {{-- <span class="fs-12 fw-normal text-muted d-block">Following information for your lead</span> --}}
                                </h5>
                            </div>
                            <div class="row col-md-12">
                                <div class="col-md-4 ps-5">
                                    @php

                                        $image = asset('system/images/default_profile_pic.jpg');
                                        if (
                                            $data['quote']->customer &&
                                            $data['quote']->customer->photo &&
                                            file_exists(
                                                public_path(
                                                    'assets/uploads/users/photos/' . $data['quote']->customer->photo,
                                                ),
                                            )
                                        ) {
                                            $image = asset(
                                                'assets/uploads/users/photos/' . $data['quote']->customer->photo,
                                            );
                                        }
                                    @endphp

                                    <img src="{{ $image }}" class="img-fluid">
                                </div>
                                <div class="col-md-8 ps-5">
                                    <h5 class="text-decoration-underline">{{ t('app.sales.quoteRequestFrom') }}</h5>
                                    <p><span class="fw-bold">{{ t('app.name') }}:
                                        </span>
                                        @php
                                            $data['quote']->customer = $data['quote']->customer;

                                            $firstName =
                                                $data['quote']->customer->first_name ?? ($data['quote']->name ?? '');
                                            $middleName = $data['quote']->customer->middle_name ?? '';
                                            $lastName = $data['quote']->customer->last_name ?? '';
                                            $email = $data['quote']->customer->email1 ?? ($data['quote']->email ?? '');
                                            $phone = $data['quote']->customer->phone1 ?? ($data['quote']->phone ?? '');
                                        @endphp

                                        {{ trim($firstName . ' ' . $middleName . ' ' . $lastName) }}
                                    </p>
                                    <p><span class="fw-bold">{{ t('auth.email') }}:
                                        </span>
                                        @if ($data['quote']->customer)
                                            {{ $data['quote']->customer->email }}
                                        @else
                                            {{ $data['quote']->email }}
                                        @endif
                                    </p>
                                    <p><span class="fw-bold">{{ t('auth.phone') }}:
                                        </span>
                                        @if ($data['quote']->customer)
                                            {{ $data['quote']->customer->phone }}
                                        @else
                                            {{ $data['quote']->phone }}
                                        @endif
                                    </p>
                                    {{-- ################################# --}}
                                    <h5 class="text-decoration-underline">{{ t('app.sales.requestDetails') }}</h5>
                                    <p><span class="fw-bold">{{ t('app.sales.ref') }}:
                                        </span>
                                        <span class="text-uppercase">{{ $data['quote']->slug }}

                                        </span>
                                    </p>
                                    <p><span class="fw-bold">{{ t('app.dateSubmitted') }}:
                                        </span>
                                        {{ \Carbon\Carbon::parse($data['quote']->submitted_at)->isoFormat('MMMM Do, YYYY [at] h:mm A') }}
                                    </p>
                                    <p><span class="fw-bold">{{ t('app.status.status') }}:
                                        </span>
                                        @switch($data['quote']->status)
                                            @case('draft')
                                                <span class="badge bg-soft-warning text-warning">{{ t('app.status.draft') }}</span>
                                            @break

                                            @case('submitted')
                                                <span
                                                    class="badge bg-soft-warning text-warning">{{ t('app.status.submitted') }}</span>
                                            @break

                                            @case('quoted')
                                                <span
                                                    class="badge bg-soft-primary text-primary">{{ t('app.status.quoted') }}</span>
                                            @break

                                            @case('approved')
                                                <span
                                                    class="badge bg-soft-primary text-primary">{{ t('app.status.approved') }}</span>
                                            @break

                                            @case('rejected')
                                                <span
                                                    class="badge bg-soft-danger text-danger">{{ t('app.status.rejected') }}</span>
                                            @break

                                            @case('expired')
                                                <span class="badge bg-soft-danger text-danger">{{ t('app.status.expired') }}</span>
                                            @break

                                            @case('converted')
                                                <span
                                                    class="badge bg-soft-danger text-danger">{{ t('app.status.converted') }}</span>
                                            @break

                                            @default
                                                {{ $data['quote']->status }}
                                        @endswitch
                                    </p>
                                    <p><span class="fw-bold">{{ t('app.address.address') }}:
                                        </span>
                                        {{ $data['quote']->address }}
                                    </p>

                                    <div class="section-divider">
                                        <span>{{ t('app.product.products') }}</span>
                                    </div>
                                    <section class="product-variation">
                                        <div class="container-fluid">
                                            @if ($data['quote']->items->isNotEmpty())
                                                @foreach ($data['quote']->items as $item)
                                                    @if ($item->variant)
                                                        {{-- display variant start --}}
                                                        @php
                                                            $locale = currentLocale();
                                                            $field = 'name_' . $locale;
                                                            $variantName = !empty($item->variant->$field)
                                                                ? $item->variant->$field
                                                                : $item->variant->name;
                                                        @endphp
                                                        <a href="{{ route('viewProductVariant', ['slug' => $item->variant->slug]) }}"
                                                            class="variant-card-link text-decoration-none text-dark"
                                                            target="_blank">
                                                            <div class="card variant-card mb-4 border-0 shadow-sm">

                                                                <div class="card-body">
                                                                    <div class="row align-items-center">

                                                                        {{-- IMAGE --}}
                                                                        <div class="col-md-4 text-center mb-3 mb-md-0">
                                                                            <img src="{{ optional($item->variant->mediaFile)->file_name
                                                                                ? asset('assets/uploads/products/products/media/images/' . $item->variant->mediaFile->file_name)
                                                                                : asset('system/images/icon_placeholder.png') }}"
                                                                                alt="{{ $variantName }}"
                                                                                class="img-fluid rounded border variant-image">
                                                                        </div>

                                                                        {{-- DETAILS --}}
                                                                        <div class="col-md-8">


                                                                            <h4 class="fw-bold mb-3">
                                                                                <span
                                                                                    class="badge bg-warning text-white fs-6 me-2">{{ $loop->iteration }}</span>
                                                                                {{ $variantName }}
                                                                                <span class="fs-6 fw-normal text-muted">
                                                                                    :: {{ t('app.product.quantity') }} ->
                                                                                    {{ $item->quantity }}
                                                                                </span>
                                                                            </h4>
                                                                            <div class="row">

                                                                                {{-- LEFT COLUMN --}}
                                                                                <div class="col-md-6">

                                                                                    <p class="mb-2">
                                                                                        <span
                                                                                            class="fw-semibold">{{ t('app.product.sku') }}:</span>
                                                                                        <span
                                                                                            class="text-uppercase">{{ $item->variant->sku }}</span>
                                                                                    </p>

                                                                                    <p class="mb-2">
                                                                                        <span
                                                                                            class="fw-semibold">{{ t('app.product.price') }}:</span>
                                                                                        @if (!is_null($item->variant->price))
                                                                                            <span
                                                                                                class="text-success fw-bold">
                                                                                                ₦{{ number_format($item->variant->price, 2) }}
                                                                                            </span>
                                                                                        @endif
                                                                                    </p>

                                                                                    <p class="mb-2">
                                                                                        <span
                                                                                            class="fw-semibold">{{ t('app.published') }}:</span>
                                                                                        <span
                                                                                            class="badge {{ $item->variant->is_active ? 'bg-success' : 'bg-danger' }}">
                                                                                            {{ $item->variant->is_active ? t('app.status.true') : t('app.status.false') }}
                                                                                        </span>
                                                                                    </p>

                                                                                    <p class="mb-0">
                                                                                        <span
                                                                                            class="fw-semibold">{{ t('app.product.availability') }}:</span>
                                                                                        <span
                                                                                            class="badge {{ $item->variant->is_available ? 'bg-success' : 'bg-danger' }}">
                                                                                            {{ $item->variant->is_available ? t('app.status.available') : t('app.status.notAvailable') }}
                                                                                        </span>
                                                                                    </p>

                                                                                </div>

                                                                                {{-- RIGHT COLUMN --}}
                                                                                <div class="col-md-6">

                                                                                    {{-- COLOUR --}}
                                                                                    <p class="mb-3">
                                                                                        <span
                                                                                            class="fw-semibold">{{ t('app.product.colour') }}:</span>

                                                                                        @if ($item->variant->colour)
                                                                                            @php
                                                                                                $hex = preg_match(
                                                                                                    '/^#[0-9A-Fa-f]{6}$/',
                                                                                                    $item->variant
                                                                                                        ->colour
                                                                                                        ->hex_code,
                                                                                                )
                                                                                                    ? $item->variant
                                                                                                        ->colour
                                                                                                        ->hex_code
                                                                                                    : '#000000';

                                                                                                $colourField =
                                                                                                    'name_' . $locale;
                                                                                                $colourName = !empty(
                                                                                                    $item->variant
                                                                                                        ->colour
                                                                                                        ->$colourField
                                                                                                )
                                                                                                    ? $item->variant
                                                                                                        ->colour
                                                                                                        ->$colourField
                                                                                                    : $item->variant
                                                                                                        ->colour->name;
                                                                                            @endphp

                                                                                            <span
                                                                                                style="width:60px; height:25px; background-color: {{ $hex }}; border:1px solid #ccc; display:inline-block;">
                                                                                            </span>
                                                                                            {{ $colourName }}
                                                                                        @else
                                                                                            -
                                                                                        @endif
                                                                                    </p>

                                                                                    {{-- ATTRIBUTES --}}
                                                                                    <div>
                                                                                        <span
                                                                                            class="fw-semibold d-block mb-2">
                                                                                            {{ t('app.product.otherAttributes') }}:
                                                                                        </span>

                                                                                        @if ($item->variant->attributes->isNotEmpty())
                                                                                            <div
                                                                                                class="d-flex flex-wrap gap-2">
                                                                                                @foreach ($item->variant->attributes as $attribute)
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
                                                                <div class="d-flex justify-content-end m-2 ms-4">
                                                                    <a href="{{ route('viewProductVariant', ['slug' => $item->variant->slug]) }}"
                                                                        class="btn btn-primary"
                                                                        target="_blank">{{ t('app.buttons.view') }}</a>
                                                                </div>
                                                            </div>
                                                        </a>
                                                        {{-- display variant end --}}
                                                    @else
                                                        {{-- main product start --}}
                                                        @php
                                                            $locale = currentLocale();
                                                            $field = 'name_' . $locale;
                                                            $productName = !empty($item->product->$field)
                                                                ? $item->product->$field
                                                                : $item->product->name;
                                                        @endphp
                                                        <a href="{{ route('viewProduct', ['slug' => $item->product->slug]) }}"
                                                            class="variant-card-link text-decoration-none text-dark"
                                                            target="_blank">
                                                            <div class="card variant-card mb-4 border-0 shadow-sm">

                                                                <div class="card-body">
                                                                    <div class="row align-items-center">

                                                                        {{-- ############################ --}}
                                                                        @php
                                                                            $thumbnailImage = asset(
                                                                                'system/images/icon_placeholder.png',
                                                                            );

                                                                            if (
                                                                                !$item->product->mediaFiles->isEmpty()
                                                                            ) {
                                                                                $primaryImage = $item->product->mediaFiles
                                                                                    ->where('is_primary', true)
                                                                                    ->first();

                                                                                if ($primaryImage) {
                                                                                    $thumbnailImage = asset(
                                                                                        'assets/uploads/products/products/media/images/' .
                                                                                            $primaryImage->file_name,
                                                                                    );
                                                                                }
                                                                            }
                                                                        @endphp

                                                                        {{-- ############################ --}}
                                                                        {{-- IMAGE --}}
                                                                        <div class="col-md-4 text-center mb-3 mb-md-0">
                                                                            <img src="{{ $thumbnailImage }}"
                                                                                alt="{{ $productName }}"
                                                                                class="img-fluid rounded border variant-image">
                                                                        </div>

                                                                        {{-- DETAILS --}}
                                                                        <div class="col-md-8">

                                                                            <h4 class="fw-bold mb-3">
                                                                                <span
                                                                                    class="badge bg-warning text-white fs-6 me-2">{{ $loop->iteration }}</span>
                                                                                {{ $productName }}
                                                                                <span class="fs-6 fw-normal text-muted">
                                                                                    :: {{ t('app.product.quantity') }} ->
                                                                                    {{ $item->quantity }}
                                                                                </span>
                                                                            </h4>


                                                                            <div class="row">

                                                                                {{-- LEFT COLUMN --}}
                                                                                <div class="col-md-6">

                                                                                    <p class="mb-2">
                                                                                        <span
                                                                                            class="fw-semibold">{{ t('app.product.sku') }}:</span>
                                                                                        <span
                                                                                            class="text-uppercase">{{ $item->product->sku }}</span>
                                                                                    </p>

                                                                                    <p class="mb-2">
                                                                                        <span
                                                                                            class="fw-semibold">{{ t('app.product.price') }}:</span>
                                                                                        @if (!is_null($item->product->price))
                                                                                            <span
                                                                                                class="text-success fw-bold">
                                                                                                ₦{{ number_format($item->product->price, 2) }}
                                                                                            </span>
                                                                                        @endif
                                                                                    </p>

                                                                                    <p class="mb-2">
                                                                                        <span
                                                                                            class="fw-semibold">{{ t('app.published') }}:</span>
                                                                                        <span
                                                                                            class="badge {{ $item->product->is_active ? 'bg-success' : 'bg-danger' }}">
                                                                                            {{ $item->product->is_active ? t('app.status.true') : t('app.status.false') }}
                                                                                        </span>
                                                                                    </p>

                                                                                    <p class="mb-0">
                                                                                        <span
                                                                                            class="fw-semibold">{{ t('app.product.availability') }}:</span>
                                                                                        <span
                                                                                            class="badge {{ $item->product->is_available ? 'bg-success' : 'bg-danger' }}">
                                                                                            {{ $item->product->is_available ? t('app.status.available') : t('app.status.notAvailable') }}
                                                                                        </span>
                                                                                    </p>

                                                                                </div>

                                                                                {{-- RIGHT COLUMN --}}
                                                                                <div class="col-md-6">

                                                                                    {{-- COLOUR --}}
                                                                                    <p class="mb-3">
                                                                                        <span
                                                                                            class="fw-semibold">{{ t('app.product.colour') }}:</span>

                                                                                        @if ($item->product->colour)
                                                                                            @php
                                                                                                $hex = preg_match(
                                                                                                    '/^#[0-9A-Fa-f]{6}$/',
                                                                                                    $item->product
                                                                                                        ->colour
                                                                                                        ->hex_code,
                                                                                                )
                                                                                                    ? $item->product
                                                                                                        ->colour
                                                                                                        ->hex_code
                                                                                                    : '#000000';

                                                                                                $colourField =
                                                                                                    'name_' . $locale;
                                                                                                $colourName = !empty(
                                                                                                    $item->product
                                                                                                        ->colour
                                                                                                        ->$colourField
                                                                                                )
                                                                                                    ? $item->product
                                                                                                        ->colour
                                                                                                        ->$colourField
                                                                                                    : $item->product
                                                                                                        ->colour->name;
                                                                                            @endphp

                                                                                            <span
                                                                                                style="width:60px; height:25px; background-color: {{ $hex }}; border:1px solid #ccc; display:inline-block;">
                                                                                            </span>
                                                                                            {{ $colourName }}
                                                                                        @else
                                                                                            -
                                                                                        @endif
                                                                                    </p>

                                                                                    {{-- ATTRIBUTES --}}
                                                                                    <div>
                                                                                        <span
                                                                                            class="fw-semibold d-block mb-2">
                                                                                            {{ t('app.product.otherAttributes') }}:
                                                                                        </span>

                                                                                        @if ($item->product->attributes->isNotEmpty())
                                                                                            <div
                                                                                                class="d-flex flex-wrap gap-2">
                                                                                                @foreach ($item->product->attributes as $attribute)
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
                                                                <div class="d-flex justify-content-end m-2 ms-4">
                                                                    <a href="{{ route('viewProduct', ['slug' => $item->product->slug]) }}"
                                                                        class="btn btn-primary"
                                                                        target="_blank">{{ t('app.buttons.view') }}</a>
                                                                </div>
                                                            </div>
                                                        </a>
                                                        {{-- main product end --}}
                                                    @endif
                                                @endforeach
                                            @endif

                                        </div>
                                    </section>
                                </div>


                            </div>

                        </div>


                        {{-- createQuoteSubmitRequestNo --}}
                        <div class="card card-body lead-info pt-0 ps-0 pe-0">
                            <h3 class="inline-block bg-primary text-white px-3 py-1 text-center rounded">
                                {{ t('app.product.quotes') }}
                            </h3>
                            <div class="d-flex justify-content-end m-2">
                                <form method="GET" action="{{ route('createQuoteSubmitRequestNo') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="hidden" name="request_slug" class="form-control text-center"
                                            value="{{ $data['quote']->slug }}">
                                        <p style="color: red; font-size: 12px; margin-top: 5px;">
                                            {{ $errors->first('request_slug') }}</p>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 mt-2"><i
                                            class="feather-plus me-2"></i>
                                        <span>{{ t('app.sales.createNewQuote') }}</span></button>
                                </form>
                            </div>
                            {{-- <button onclick="createNewQuoteWithRequestNoModal('swal-size-md')" class="btn btn-warning">
                                <i class="feather-plus me-2"></i>
                                <span>{{ t('app.sales.createNewQuote') }}</span>
                            </button> --}}
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Odit nemo, iste suscipit numquam
                            cumque voluptates neque maxime consectetur quas vero consequatur magni in? Doloribus non
                            repudiandae, temporibus tempora magni earum!
                        </div>
                        {{-- Back Home --}}
                        <div class="row mb-4 justify-content-center">
                            <div class="col-auto">
                                <a class="btn btn-primary" href="{{ route('quoteRequests') }}">
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
    {{-- @include('users.admin.products.categories.include.addSubCategoryModal') --}}
    {{-- modals end --}}

@endsection
