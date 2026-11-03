@extends('layouts.adminLayout')
@section('title', t('app.buttons.edit') . '::' . t('app.settings.homePageSlider'))
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10"><a href="#">{{ t('app.settings.homePageSlider') }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><a href="#">{{ t('app.settings.settings') }}</a></li>
                    <li class="breadcrumb-item"><a href="#"><a
                                href="#">{{ t('app.buttons.edit') }}</a>
                    </li>
                    <li class="breadcrumb-item">{{ t('app.buttons.edit') }}</li>
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
                        @include('users.admin.config.homepage.include.inner_nav')
                    </div>
                    {{-- Inner nav end --}}
                    <div class="card stretch stretch-full col-md-10">
                        <div class="card-body lead-status">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold mb-0 me-4">
                                    <span class="d-block mb-2">{{ t('app.buttons.edit') }} ::
                                        {{ t('app.settings.homePageConfig') }}
                                    </span>
                                    {{-- <span class="fs-12 fw-normal text-muted text-truncate-1-line">Typically refers to
                                            adding a new potential customer or sales prospect</span> --}}
                                </h5>
                                {{-- editing per language starts --}}
                                <div class="d-flex flex-wrap justify-content-end gap-2">
                                    @foreach (activeLanguages()->where('status', 'active') as $language)
                                        <a href="{{ route('editSlider', ['id' => $data['slider']->id]) }}?language={{ $language->language_code }}"
                                            class="btn btn-sm edit-by-lang {{ $data['language'] == $language->language_code ? 'edit-by-lang-active' : '' }} d-flex align-items-center gap-1">
                                            <img src="{{ asset('system/images/lang/' . $language->flag) }}" alt=""
                                                width="22">
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
                                    action="{{ route('updateSliderSubmit', ['id' => $data['slider']->id]) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="language" value="{{ $data['language'] }}">

                                    @php
                                        $image = asset('system/images/icon_placeholder.png');

                                        $filePath = public_path(
                                            'assets/uploads/homepageSlider/' . $data['slider']->background_image,
                                        );

                                        if (!empty($data['slider']->background_image) && file_exists($filePath)) {
                                            $image = asset(
                                                'assets/uploads/homepageSlider/' . $data['slider']->background_image,
                                            );
                                        }
                                    @endphp

                                    <div class="mb-3 form-group upload-container">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col-md-6"><label for="passport-upload" class="preview-frame">
                                                    <img id="passport-preview" src="{{ $image }}" alt="">
                                                    <div class="overlay"><i class="fas fa-upload"></i></div>
                                                </label>
                                            </div>
                                            <div class="col-md-6"><input type="file" id="passport-upload"
                                                    accept=".jpg, .jpeg, .png, .webp" onchange="previewPassport()"
                                                    name="background_image">
                                            </div>
                                            <p class="text-red">{{ $errors->first('background_image') }}</p>
                                        </div>
                                    </div>

                                    <div class="row col-md-12">
                                        <div class="mb-4 col-md-12">
                                            <label class="form-label">{{ t('app.header') }} <span
                                                    class="text-danger">*</span></label>
                                            @php
                                                $lang = $data['language'];
                                                $field = 'header_' . $lang;
                                                $header = !empty($data['slider']->$field)
                                                    ? $data['slider']->$field
                                                    : $data['slider']->header;
                                            @endphp


                                            <input type="text" class="form-control" name="header"
                                                value="{{ $header }}" required>
                                            <p class="text-danger">{{ $errors->first('header') }}
                                            </p>
                                        </div>

                                        <div class="mb-4 col-md-12">
                                            <label class="form-label">{{ t('app.description') }} <span
                                                    class="text-danger">*</span></label>
                                            @php
                                                $lang = $data['language'];
                                                $field = 'description_' . $lang;
                                                $desc = !empty($data['slider']->$field)
                                                    ? $data['slider']->$field
                                                    : $data['slider']->description;
                                            @endphp

                                            <input type="text" class="form-control" name="description"
                                                value="{{ $desc }}" required>
                                            <p class="text-danger">{{ $errors->first('description') }}
                                            </p>
                                        </div>

                                        <div class="mb-4 col-md-12">
                                            <label class="form-label">{{ t('app.link') }}</label>
                                            <input type="url" class="form-control" name="link"
                                                value="{{ $data['slider']->link }}" placeholder="https://example.com">
                                            <p class="text-danger">{{ $errors->first('link') }}
                                            </p>
                                        </div>

                                        <div class="mb-4 col-md-12">
                                            <label class="form-label">{{ t('app.order') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="order"
                                                value="{{ $data['slider']->order }}" min="0" step="1"
                                                required>
                                            <p class="text-danger">{{ $errors->first('order') }}
                                            </p>
                                        </div>
                                    </div>

                                    <button type="submit"
                                        class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
                                </form>

                            </div>
                        </div>
                    </div>
                    {{-- Back Home --}}
                    <div class="row mb-4 justify-content-center">
                        <div class="col-auto">
                            <a class="btn btn-primary" href="{{ route('homePageSliderConfig') }}">
                                <i class="fas fa-chevron-left"></i> &nbsp;
                                {{ t('app.buttons.back') }}
                            </a>
                        </div>
                    </div>


                </div>


            </div>
        </div>
        <!-- [ Main Content ] end -->

        <script>
            function previewPassport() {
                const preview = document.getElementById('passport-preview');
                const fileInput = document.getElementById('passport-upload');
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
@endsection
