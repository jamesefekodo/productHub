@extends('layouts.adminLayout')
@section('title', t('app.settings.homePageConfig'))
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">{{ t('app.settings.homePageSlider') }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ t('app.settings.settings') }}</a></li>
                    <li class="breadcrumb-item">{{ t('app.settings.homePageConfig') }}</li>
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

                        <a href="{{ route('addNewSlider') }}" class="btn btn-primary">
                            <i class="feather-plus me-2"></i>
                            <span>{{ t('app.addNew') }}</span>
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
        <!-- [ Main Content ] start -->
        <div class="main-content">
            <div class="row col-md-12">
                <!-- [Latest Leads] start -->
                <div class="col-md-12 row">
                    {{-- inner nav start --}}
                    <div class="col-md-2">
                        @include('users.admin.config.homepage.include.inner_nav')
                    </div>
                    {{-- Inner nav end --}}
                    <div class="card stretch stretch-full col-md-10">
                        <div class="card-header">
                            <h5 class="card-title">
                                {{ t('app.settings.homePageSlider') }}
                            </h5>
                        </div>
                        <div class="card-body custom-card-action p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr class="border-b">
                                            <th scope="row">#</th>
                                            <th scope="row">{{ t('app.image') }}</th>
                                            <th scope="row">{{ t('app.header') }}</th>
                                            <th scope="row">{{ t('app.description') }}</th>
                                            <th scope="row">{{ t('app.link') }}</th>
                                            <th scope="row" class="">{{ t('app.actions') }}</th>
                                        </tr>
                                    </thead>
                                    @if ($data['homePageSlider']->isEmpty())
                                        <tbody>
                                            <td colspan="6" class="text-center text-danger fw-bold fs-5">
                                                {{ t('app.noRecordsFound') }}
                                            </td>
                                        </tbody>
                                    @else
                                        <tbody>
                                            @foreach ($data['homePageSlider'] as $slider)
                                                <tr>
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $image = asset('system/images/icon_placeholder.png');

                                                            $filePath = public_path(
                                                                'assets/uploads/homepageSlider/' .
                                                                    $slider->background_image,
                                                            );

                                                            if (
                                                                !empty($slider->background_image) &&
                                                                file_exists($filePath)
                                                            ) {
                                                                $image = asset(
                                                                    'assets/uploads/homepageSlider/' .
                                                                        $slider->background_image,
                                                                );
                                                            }
                                                        @endphp

                                                        <img src="{{ $image }}" alt="Slider Image" width="300">

                                                    </td>
                                                    <td class="">
                                                        @php
                                                            $locale = currentLocale();
                                                            $field = 'header_' . $locale;
                                                            $header = !empty($slider->$field)
                                                                ? $slider->$field
                                                                : $slider->header;
                                                        @endphp

                                                        {{ $header }}
                                                    </td>
                                                    <td class="">
                                                        @php
                                                            $locale = currentLocale();
                                                            $field = 'description_' . $locale;
                                                            $desc = !empty($slider->$field)
                                                                ? $slider->$field
                                                                : $slider->description;
                                                        @endphp

                                                        {{ $desc }}
                                                    </td>
                                                    <td class="">
                                                        @php
                                                            $url = $slider->link;
                                                            if (!preg_match('/^https?:\/\//', $url)) {
                                                                $url = 'https://' . $url;
                                                            }
                                                        @endphp

                                                        <a href="{{ $url }}" target="_blank"
                                                            rel="noopener noreferrer">
                                                            {{ $slider->link }}
                                                        </a>

                                                    </td>
                                                    <td>
                                                        <a href="{{ route('editSlider', ['id' => $slider->id]) }}?language=en"
                                                            class="btn btn-primary d-inline-flex align-items-center gap-1">
                                                            <i class="feather-edit"></i>
                                                            {{ t('app.buttons.edit') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @endif
                                </table>
                            </div>
                        </div>


                    </div>

                </div>


                <!-- [Latest Leads] end -->

            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
@endsection
