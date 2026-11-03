@extends('layouts.adminLayout')
@section('title', t('app.buttons.edit') . '::' . $data['category']->category)
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10"><a href="#">{{ t('app.product.categories') }}</h5>
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
                    {{-- <div class="d-flex d-md-none">
                        <a href="javascript:void(0)" class="page-header-right-close-toggle">
                            <i class="feather-arrow-left me-2"></i>
                            <span>{{ t('app.buttons.back') }}</span>
                        </a>
                    </div> --}}
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
                    <div class="col-md-10">
                        <div class="card stretch stretch-full">
                            <div class="card-body lead-status">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="fw-bold mb-0 me-4">
                                        <span class="d-block mb-2">{{ t('app.buttons.edit') }} ::
                                            {{ t('app.product.category') }}
                                            @php
                                                $lang = $data['language'];
                                                $field = 'category_' . $lang;
                                                $value = !empty($data['category']->$field)
                                                    ? $data['category']->$field
                                                    : $data['category']->category;
                                            @endphp
                                            ({{ $value }})</span></span>
                                        {{-- <span class="fs-12 fw-normal text-muted text-truncate-1-line">Typically refers to
                                            adding a new potential customer or sales prospect</span> --}}
                                    </h5>
                                    {{-- editing per language starts --}}
                                    <div class="d-flex flex-wrap justify-content-end gap-2">
                                        @foreach (activeLanguages()->where('status', 'active') as $language)
                                            <a href="{{ route('editCategory', ['slug' => $data['category']->slug]) }}?language={{ $language->language_code }}"
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
                                        action="{{ route('updateCategorySubmit', ['slug' => $data['category']->slug]) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="language" value="{{ $data['language'] }}">
                                        @php
                                            $image = asset('system/images/icon_placeholder.png');
                                            if (
                                                $data['category']->image &&
                                                file_exists(
                                                    public_path(
                                                        'assets/uploads/products/categories/icons/' .
                                                            $data['category']->image,
                                                    ),
                                                )
                                            ) {
                                                $image = asset(
                                                    'assets/uploads/products/categories/icons/' .
                                                        $data['category']->image,
                                                );
                                            }
                                        @endphp
                                        <div class="mb-3 form-group upload-container">
                                            <div class="row d-flex">
                                                <div class="row">
                                                    <div
                                                        class="col-md-6 d-flex flex-column align-items-center justify-content-center text-center">
                                                        <input type="file" id="passport-upload-1"
                                                            accept=".jpg, .jpeg, .png, .webp" onchange="previewPassport1()"
                                                            name="image" class="mb-2">
                                                        {{ t('app.icon') }}
                                                        <p class="text-danger">{{ $errors->first('image') }}</p>
                                                    </div>

                                                    <div
                                                        class="col-md-6 d-flex flex-column align-items-center justify-content-center text-center">
                                                        <label for="passport-upload-1" class="preview-frame">
                                                            <img id="passport-preview-1" src="{{ $image }}"
                                                                alt="" class="img-fluid">
                                                            <div class="overlay"><i class="fas fa-upload"></i></div>
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row col-md-12">
                                            <div class="mb-4 col-md-6">
                                                <label class="form-label">{{ t('app.name') }} <span
                                                        class="text-danger">*</span></label>
                                                @php
                                                    $lang = $data['language'];
                                                    $field = 'category_' . $lang;
                                                    $value = !empty($data['category']->$field)
                                                        ? $data['category']->$field
                                                        : $data['category']->category;
                                                @endphp
                                                <input type="text" class="form-control" value="{{ $value }}"
                                                    name="category" required>
                                            </div>
                                            <div class="mb-4 col-md-6">
                                                <label class="form-label">{{ t('app.settings.seo.metaTitle') }}</label>
                                                @php
                                                    $lang = $data['language'];
                                                    $field = 'meta_title_' . $lang;
                                                    $value = !empty($data['category']->$field)
                                                        ? $data['category']->$field
                                                        : $data['category']->meta_title;
                                                @endphp
                                                <input type="text" class="form-control" value="{{ $value }}"
                                                    name="meta_title" required>
                                                <p id="status-error" class="text-danger">{{ $errors->first('meta_title') }}
                                                </p>
                                            </div>
                                            <div class="mb-4 col-md-12">
                                                <label
                                                    class="form-label">{{ t('app.settings.seo.metaDescription') }}</label>
                                                @php
                                                    $lang = $data['language'];
                                                    $field = 'meta_description_' . $lang;
                                                    $value = !empty($data['category']->$field)
                                                        ? $data['category']->$field
                                                        : $data['category']->meta_description;
                                                @endphp

                                                <textarea class="form-control" name="meta_description" style="min-height: 200px;" required>{{ old('meta_description', $value) }}</textarea>
                                                <p id="status-error" class="text-danger">
                                                    {{ $errors->first('meta_description') }}</p>
                                            </div>
                                            <div class="mb-4 col-md-6">
                                                <label class="form-label">{{ t('app.status.status') }} <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select text-center" name="is_active" required>
                                                    <option value="" disabled>- Select -</option>
                                                    <option value=1
                                                        {{ $data['category']->is_active == 1 ? 'selected' : '' }}>
                                                        {{ t('app.status.active') }}
                                                    </option>
                                                    <option value=0
                                                        {{ $data['category']->is_active == 0 ? 'selected' : '' }}>
                                                        {{ t('app.status.inactive') }}
                                                    </option>
                                                </select>
                                                <p id="status-error" class="text-danger">{{ $errors->first('status') }}
                                                </p>
                                            </div>
                                            <div class="mb-4 col-md-6">
                                                <label class="form-label">{{ t('app.product.featured') }} <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select text-center" name="is_featured" required>
                                                    <option value="" disabled>- Select -</option>
                                                    <option value=1
                                                        {{ $data['category']->is_featured == 1 ? 'selected' : '' }}>
                                                        {{ t('app.status.true') }}
                                                    </option>
                                                    <option value=0
                                                        {{ $data['category']->is_featured == 0 ? 'selected' : '' }}>
                                                        {{ t('app.status.false') }}
                                                    </option>
                                                </select>
                                                <p id="status-error" class="text-danger">{{ $errors->first('status') }}
                                                </p>
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
                                    href="{{ route('viewCategory', ['slug' => $data['category']->slug]) }}">
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

        <script>
            function previewPassport1() {
                const preview = document.getElementById('passport-preview-1');
                const fileInput = document.getElementById('passport-upload-1');
                const file = fileInput.files[0];
                const reader = new FileReader();

                reader.onload = function() {
                    preview.src = reader.result;
                }

                if (file) {
                    reader.readAsDataURL(file);
                } else {
                    preview.src = 'placeholder.jpg';
                }
            }
        </script>
    </div>

    {{-- modals start --}}
    @include('users.admin.products.categories.include.addSubCategoryModal')
    {{-- modals end --}}

@endsection
