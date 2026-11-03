@extends('layouts.adminLayout')
@section('title', t('app.sales.termsAndConditionsTemplate'))
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">{{ t('app.sales.termsAndConditionsTemplate') }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a
                            href="#">{{ t('app.sales.sales') }}/{{ t('app.sales.marketing') }}</a></li>
                    <li class="breadcrumb-item">{{ t('app.sales.termsAndConditionsTemplate') }}</li>
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
                        <div class="dropdown">
                            <a class="btn btn-icon btn-light-brand" data-bs-toggle="dropdown" data-bs-offset="0, 10"
                                data-bs-auto-close="outside">
                                <i class="feather-filter"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:void(0);" class="dropdown-item filter-option active fw-bold"
                                    data-filter="all">
                                    <span class="wd-7 ht-7 bg-primary rounded-circle d-inline-block me-3"></span>
                                    <span>{{ t('app.all') }}</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0);" class="dropdown-item filter-option" data-filter="active">
                                    <span class="wd-7 ht-7 bg-success rounded-circle d-inline-block me-3"></span>
                                    <span>{{ t('app.status.active') }}</span>
                                </a>
                                <a href="javascript:void(0);" class="dropdown-item filter-option" data-filter="inactive">
                                    <span class="wd-7 ht-7 bg-danger rounded-circle d-inline-block me-3"></span>
                                    <span>{{ t('app.status.inactive') }}</span>
                                </a>
                            </div>
                        </div>
                        <button onclick="createNewQuoteWithRequestNoModal('swal-size-md')" class="btn btn-primary">
                            <i class="feather-plus me-2"></i>
                            <span>{{ t('app.addNew') }}</span>
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
        <!-- [ Main Content ] start -->
        <div class="main-content">
            <div class="row col-md-12">
                {{-- inner nav start --}}
                <div class="col-md-2">
                    @include('users.admin.salesMarketing.quotes.include.inner_nav')
                </div>
                {{-- Inner nav end --}}
                <!-- [Latest Leads] start -->
                <div class="col-md-10">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">
                                {{ t('app.sales.termsAndConditionsTemplate') }}
                            </h5>

                        </div>
                        <div class="card-body p-4">

                            <form id="swalForm" method="POST" action="{{ route('updateQuoteTermsAndConditionsSubmit') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row col-md-12">
                                    <div class="mb-4 col-md-12">
                                        {{-- <label class="form-label">{{ t('app.sales.termsAndConditionsTemplate') }}</label> --}}
                                        <textarea class="form-control" id="quoteTAndCTemplate" name="quote_t_and_c_template" style="min-height: 400px;"
                                            required>{{ $data['quoteTAndCTemplate'] }}</textarea>
                                        <p class="text-danger">{{ $errors->first('quote_t_and_c_template') }}</p>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="btn btn-primary w-100 mt-2">{{ t('app.buttons.update') }}</button>

                            </form>

                        </div>


                    </div>

                </div>

            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>


    <script>
        tinymce.init({
            selector: '#quoteTAndCTemplate',
            height: 500,
            menubar: true,
            license_key: 'gpl',

            // Plugins: remove image, media
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'charmap',
                'preview', 'anchor', 'searchreplace', 'visualblocks', 'code',
                'fullscreen', 'insertdatetime', 'table', 'codesample',
                'help', 'wordcount', 'emoticons'
            ],

            // Toolbar: remove image/media buttons
            toolbar: [
                'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough |',
                'alignleft aligncenter alignright alignjustify |',
                'bullist numlist outdent indent | forecolor backcolor |',
                'link table | charmap emoticons codesample |',
                'removeformat fullscreen preview code help'
            ].join(''),

            font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',
            font_family_formats: 'Arial=arial,helvetica,sans-serif;' +
                'Courier New=courier new,courier,monospace;' +
                'Georgia=georgia,palatino;' +
                'Tahoma=tahoma,arial,helvetica,sans-serif;' +
                'Times New Roman=times new roman,times;' +
                'Verdana=verdana,geneva;',

            content_style: `
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            padding: 10px;
        }
    `,

            setup: function(editor) {
                // Ensure content is synced to textarea for Laravel
                editor.on('change', function() {
                    editor.save();
                });
            }
        });

        // Ensure content is saved before form submit
        document.querySelector('form').addEventListener('submit', function() {
            tinymce.triggerSave();
        });
    </script>
@endsection
