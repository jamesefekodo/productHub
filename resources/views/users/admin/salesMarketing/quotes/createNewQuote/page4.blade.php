@extends('layouts.adminLayout')
@section('title', t('app.sales.createNewQuote'))
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10"><a href="#">{{ t('app.product.products') }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><a
                                href="#">{{ t('app.sales.sales') }}/{{ t('app.sales.marketing') }}</a></li>
                    <li class="breadcrumb-item"><a href="#"><a href="#">{{ t('app.sales.createNewQuote') }}</a>
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
                        @include('users.admin.salesMarketing.quotes.include.inner_nav')
                    </div>
                    {{-- Inner nav end --}}
                    <div class="card stretch stretch-full col-md-10 pt-0 ps-0 pe-0">
                        <h3 class="inline-block bg-primary text-white p-2 text-center rounded">
                            {{ t('app.sales.createNewQuote') }}
                        </h3>
                        <div class="card-body lead-status">
                            @include('users.admin.salesMarketing.quotes.createNewQuote.include.createQuoteNavMenu')
                        </div>

                        {{-- ############# --}}
                        <p class="fs-5 mb-0 fw-bold mx-5">{{ t('app.sales.quoteNumber') }}: <span
                                class="fs-3 text-uppercase">{{ $data['existingTemporaryQuote']->quote_number }}
                        </p>
                        {{-- ############# --}}
                        <div class="card-body general-info">
                            <form id="swalForm" method="POST"
                                action="{{ route('updateQuoteNotesSubmit', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row col-md-12 ps-4">
                                    <input name="quote_number" type="hidden"
                                        value="{{ $data['existingTemporaryQuote']->quote_number }}">
                                    <div class="mb-5 col-md-12">
                                        <label class="fs-4">{{ t('app.sales.termsAndConditions') }} <span
                                                class="text-danger">*</span>:</label>
                                        <textarea class="form-control" id="quoteTAndCTemplate" name="terms_and_conditions" style="min-height: 400px;" required>
                                            {{ $data['quoteTAndCTemplate'] }}
                                        </textarea>
                                        <p class="text-danger">{{ $errors->first('terms_and_conditions') }}</p>
                                    </div>

                                    <div class="mb-5 col-md-12">
                                        <label class="fs-4">{{ t('app.sales.additionalNote') }}
                                            ({{ t('app.sales.forCustomer') }}):</label>
                                        <textarea class="form-control" id="customerNote" name="customer_note" style="min-height: 400px;">
                                            {{ $data['existingTemporaryQuote']->customer_note }}
                                        </textarea>
                                        <p class="text-danger">{{ $errors->first('customer_note') }}</p>
                                    </div>

                                    <div class="mb-5 col-md-12">
                                        <label class="fs-4">{{ t('app.sales.internalNote') }}
                                            ({{ t('app.sales.forStaff') }}):</label>
                                        <textarea class="form-control" id="internalNote" name="internal_note" style="min-height: 400px;">
                                            {{ $data['existingTemporaryQuote']->internal_note }}
                                        </textarea>
                                        <p class="text-danger">{{ $errors->first('internal_note') }}</p>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="btn btn-primary w-100 mt-2 mb-5">{{ t('app.buttons.update') }}</button>

                            </form>
                            {{-- quote summation start --}}
                            @include('users.admin.salesMarketing.quotes.createNewQuote.include.quoteSummation')
                            {{-- quote summation end --}}
                            <div class="d-flex justify-content-between">
                                <a class="btn btn-secondary"
                                    href="{{ route('createQuoteCustomer', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}">
                                    <i class="fas fa-chevron-left me-2"></i>
                                    {{ t('app.buttons.back') }}
                                </a>

                                <a class="btn btn-primary"
                                    href="{{ route('createQuoteFinish', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}">
                                    {{ t('app.buttons.proceed') }}
                                    <i class="fas fa-chevron-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    {{-- Back Home --}}
                    <div class="row mb-4 justify-content-center">
                        <div class="col-auto">
                            <a class="btn btn-primary" href="{{ route('quotes') }}">
                                <i class="fas fa-chevron-left"></i> &nbsp;
                                {{ t('app.buttons.back') }}
                            </a>
                        </div>
                    </div>


                    {{-- scripts start --}}

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


                    <script>
                        tinymce.init({
                            selector: '#customerNote',
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

                    <script>
                        tinymce.init({
                            selector: '#internalNote',
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


                    {{-- scripts end --}}
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->





    </div>
@endsection
