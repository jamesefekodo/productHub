@extends('layouts.adminLayout')
@section('title', t('app.buttons.view') . '::' . $data['subCategory']->sub_category)
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10"><a href="#">{{ t('app.product.subCategories') }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><a href="#">{{ t('app.product.products') }}</a></li>
                    <li class="breadcrumb-item"><a href="#"><a href="#">{{ t('app.product.categories') }}</a>
                    </li>
                    <li class="breadcrumb-item">{{ t('app.buttons.view') }}</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex d-md-none">
                        <a href="{{ route('subCategories', ['slug' => $data['category']->slug]) }}"
                            class="page-header-right-close-toggle">
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
                    {{-- inner nav start --}}
                    <div class="col-md-2">
                        @include('users.admin.products.categories.include.inner_nav')
                    </div>
                    {{-- Inner nav end --}}
                    <div class="tab-pane fade show active col-md-10" id="profileTab" role="tabpanel">
                        <div class="card card-body lead-info">
                            <div class="mb-4 d-flex align-items-center justify-content-between ps-5">
                                <h5 class="fw-bold mb-0">
                                    <span class="d-block mb-2">{{ t('app.product.subCategory') }} ::

                                        @php
                                            $locale = currentLocale();
                                            $field = 'sub_category_' . $locale;
                                            $value = !empty($data['subCategory']->$field)
                                                ? $data['subCategory']->$field
                                                : $data['subCategory']->sub_category;
                                        @endphp

                                        {{ $value }}
                                    </span>
                                    {{-- <span class="fs-12 fw-normal text-muted d-block">Following information for your lead</span> --}}
                                </h5>
                            </div>
                            <div class="row col-md-12">
                                <div class="col-md-4 ps-5">
                                    @php
                                        $image = asset('system/images/icon_placeholder.png');
                                        if (
                                            $data['subCategory']->image &&
                                            file_exists(
                                                public_path(
                                                    'assets/uploads/products/categories/subCategories/icons/' .
                                                        $data['subCategory']->image,
                                                ),
                                            )
                                        ) {
                                            $image = asset(
                                                'assets/uploads/products/categories/subCategories/icons/' .
                                                    $data['subCategory']->image,
                                            );
                                        }
                                    @endphp

                                    <img src="{{ $image }}" class="img-fluid">
                                </div>
                                <div class="col-md-8 ps-5">
                                    <p><span class="fw-bold">{{ t('app.product.subCategory') }}:
                                        </span>
                                        @php
                                            $locale = currentLocale();
                                            $field = 'sub_category_' . $locale;
                                            $value = !empty($data['subCategory']->$field)
                                                ? $data['subCategory']->$field
                                                : $data['subCategory']->sub_category;
                                        @endphp

                                        {{ $value }}
                                    </p>
                                    <p><span class="fw-bold">{{ t('app.product.parentCategory') }}:
                                        </span>
                                        @php
                                            $locale = currentLocale();
                                            $field = 'category_' . $locale;
                                            $value = !empty($data['category']->$field)
                                                ? $data['category']->$field
                                                : $data['category']->category;
                                        @endphp

                                        {{ $value }}
                                    </p>
                                    <p><span class="fw-bold">{{ t('app.status.status') }}: </span>
                                        @switch($data['subCategory']->is_active)
                                            @case(true)
                                                <span
                                                    class="badge bg-soft-success text-success">{{ t('app.status.active') }}</span>
                                            @break

                                            @default
                                                <span
                                                    class="badge bg-soft-danger text-danger">{{ t('app.status.inactive') }}</span>
                                        @endswitch
                                    </p>
                                    <p><span class="fw-bold">{{ t('app.product.featured') }}: </span>
                                        @switch($data['subCategory']->is_featured)
                                            @case(true)
                                                <span class="badge bg-soft-success text-success">{{ t('app.status.true') }}</span>
                                            @break

                                            @default
                                                <span class="badge bg-soft-danger text-danger">{{ t('app.status.false') }}</span>
                                        @endswitch
                                    </p>
                                    <div class="d-flex align-items-center gap-2">

                                        <span class="fw-bold">
                                            {{ t('app.product.products') }}:
                                        </span>

                                        <span>
                                            {{ $data['subCategory']->products->count() }}
                                        </span>

                                        {{-- search area --}}
                                        <form method="GET" action="{{ route('productsSearch') }}" target="_blank"
                                            class="m-0">

                                            <input type="hidden" name="searchKey"
                                                value="{{ $data['subCategory']->category }}">

                                            <button type="submit" class="badge bg-soft-primary text-primary border-0">
                                                <i class="feather-eye"></i>
                                            </button>

                                        </form>

                                    </div>
                                    <span class="text-decoration-underline fw-bold">{{ t('app.settings.seo.seo') }}</span>
                                    <p>
                                        <span class="fw-bold">{{ t('app.settings.seo.metaTitle') }}:
                                        </span>

                                        @php
                                            $locale = currentLocale();
                                            $field = 'meta_title_' . $locale;
                                            $value = !empty($data['subCategory']->$field)
                                                ? $data['subCategory']->$field
                                                : $data['subCategory']->meta_title;
                                        @endphp

                                        {{ $value }}
                                    </p>
                                    <p>
                                        <span class="fw-bold">{{ t('app.settings.seo.metaDescription') }}:
                                        </span>
                                        @php
                                            $locale = currentLocale();
                                            $field = 'meta_description_' . $locale;
                                        @endphp

                                        @php
                                            $locale = currentLocale();
                                            $field = 'meta_description_' . $locale;
                                            $value = !empty($data['subCategory']->$field)
                                                ? $data['subCategory']->$field
                                                : $data['subCategory']->meta_description;
                                        @endphp

                                        {{ $value }}
                                    </p>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('editSubCategory', ['slug' => $data['subCategory']->slug]) }}?language=en"
                                        class="btn btn-primary">
                                        <i class="feather-edit me-2"></i> {{ t('app.buttons.edit') }}
                                    </a>
                                </div>


                            </div>

                        </div>
                        {{-- Back Home --}}
                        <div class="row mb-4 justify-content-center">
                            <div class="col-auto">
                                <a class="btn btn-primary"
                                    href="{{ route('subCategories', ['slug' => $data['category']->slug]) }}">
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
    @include('users.admin.products.categories.include.addSubCategoryModal')
    {{-- modals end --}}

@endsection
