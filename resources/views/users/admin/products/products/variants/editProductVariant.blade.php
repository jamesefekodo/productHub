@extends('layouts.adminLayout')
@section('title', t('app.buttons.edit') . '::' . $data['variant']->name)
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
                    <li class="breadcrumb-item"><a href="#"><a href="#">{{ t('app.product.variations') }}</a>
                    </li>
                    <li class="breadcrumb-item">{{ t('app.buttons.edit') }}</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    {{-- <div class="d-flex d-md-none">
                        <a href="javascript:void(0)" class="page-header-right-close-toggle">
                            <i class="feather-arrow-left me-2"></i>
                            <span>{{ t('app.buttons.back') }}</span>
                        </a>
                    </div> --}}
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
            <div class="row col-md-12">
                <div class="col-md-2">
                    @include('users.admin.products.products.include.inner_nav')
                </div>
                <div class="col-md-10">
                    <div class="tab-content">
                        <div class="row col-md-12">
                            <div class="card stretch stretch-full">
                                <div class="card-body lead-status">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="fw-bold mb-0 me-4">
                                            <span class="d-block mb-2">{{ t('app.buttons.edit') }} ::
                                                @php
                                                    $locale = currentLocale();
                                                    $field = 'name_' . $locale;
                                                    $value = !empty($data['variant']->$field)
                                                        ? $data['variant']->$field
                                                        : $data['variant']->name;
                                                @endphp

                                                {{ $value }}
                                            </span>
                                            {{-- <span class="fs-12 fw-normal text-muted text-truncate-1-line">Typically refers to
                                            adding a new potential customer or sales prospect</span> --}}
                                        </h5>
                                        {{-- editing per language starts --}}
                                        <div class="d-flex flex-wrap justify-content-end gap-2">
                                            @foreach (activeLanguages()->where('status', 'active') as $language)
                                                <a href="{{ route('editProductVariant', ['slug' => $data['variant']->slug]) }}?language={{ $language->language_code }}"
                                                    class="btn btn-sm edit-by-lang {{ $data['language'] == $language->language_code ? 'edit-by-lang-active' : '' }} d-flex align-items-center gap-1">
                                                    <img src="{{ asset('system/images/lang/' . $language->flag) }}"
                                                        alt="" width="22">
                                                    {{ $language->language }}
                                                </a>
                                            @endforeach
                                        </div>

                                        {{-- editing per language ends --}}
                                    </div>

                                </div>

                                <div class="card-body general-info">
                                    <div class="card-body">
                                        <form id="swalForm" method="POST"
                                            action="{{ route('updateSubProductSubmit', ['slug' => $data['variant']->slug]) }}
                                         "
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <input type="hidden" name="language" value="{{ $data['language'] }}">


                                            <div class="row col-md-12">
                                                <div class="mb-4 col-md-6">
                                                    <label class="form-label">{{ t('app.product.productName') }} <span
                                                            class="text-danger">*</span></label>
                                                    @php
                                                        $lang = $data['language'];
                                                        $field = 'name_' . $lang;
                                                        $varName = !empty($data['variant']->$field)
                                                            ? $data['variant']->$field
                                                            : $data['variant']->name;
                                                    @endphp
                                                    <input type="text" class="form-control" name="name"
                                                        value="{{ $varName }}" required>
                                                    <p class="text-danger">{{ $errors->first('name') }}
                                                    </p>
                                                </div>

                                                <div class="mb-4 col-md-6">
                                                    <label class="form-label">{{ t('app.product.sku') }}</label>
                                                    <input type="text" class="form-control" name="sku"
                                                        value="{{ $data['variant']->sku }}">
                                                    <p class="text-danger">{{ $errors->first('sku') }}
                                                    </p>
                                                </div>

                                                <div class="mb-4 col-md-4">
                                                    <label class="form-label">{{ t('app.product.price') }} (₦)</label>
                                                    <input type="text" class="form-control" name="price"
                                                        value="{{ $data['variant']->price }}">
                                                    <p class="text-danger">{{ $errors->first('price') }}
                                                    </p>
                                                </div>
                                                <div class="mb-4 col-md-4">
                                                    <label class="form-label">
                                                        {{ t('app.published') }}
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-select text-center" name="is_active" required>

                                                        <option value="" disabled>
                                                            - {{ t('app.select') }} -
                                                        </option>

                                                        <option value="1"
                                                            {{ $data['variant']->is_active ? 'selected' : '' }}>
                                                            {{ t('app.status.true') }}
                                                        </option>

                                                        <option value="0"
                                                            {{ !$data['variant']->is_active ? 'selected' : '' }}>
                                                            {{ t('app.status.false') }}
                                                        </option>

                                                    </select>

                                                    <p class="text-danger">{{ $errors->first('is_active') }}</p>
                                                </div>
                                                <div class="mb-4 col-md-4">
                                                    <label class="form-label">
                                                        {{ t('app.product.availability') }}
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-select text-center" name="is_available" required>

                                                        <option value="" disabled>
                                                            - {{ t('app.select') }} -
                                                        </option>

                                                        <option value="1"
                                                            {{ $data['variant']->is_available ? 'selected' : '' }}>
                                                            {{ t('app.status.true') }}
                                                        </option>

                                                        <option value="0"
                                                            {{ !$data['variant']->is_available ? 'selected' : '' }}>
                                                            {{ t('app.status.false') }}
                                                        </option>

                                                    </select>

                                                    <p class="text-danger">{{ $errors->first('is_available') }}</p>
                                                </div>
                                            </div>

                                            <button type="submit"
                                                class="btn btn-primary w-100 mt-2">{{ t('app.buttons.update') }}</button>


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
        </div>
        <!-- [ Main Content ] end -->
    </div>
@endsection
