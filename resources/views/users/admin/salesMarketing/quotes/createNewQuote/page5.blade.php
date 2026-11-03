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
                                action="{{ route('updateQuoteDatesSubmit', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row col-md-12 ps-4">
                                    <div class="mb-5 col-md-6 form-group">
                                        <label class="fs-">{{ t('app.issueDate') }} <span
                                                class="text-danger">*</span>:</label>
                                        <input type="datetime-local" name="issue_date" class="col-md-12 text-center"
                                            value="{{ \Carbon\Carbon::parse($data['existingTemporaryQuote']->issue_date)->format('Y-m-d\TH:i') }}"
                                            required>
                                        <p class="text-danger">{{ $errors->first('issue_date') }}</p>
                                    </div>
                                    <div class="mb-5 col-md-6 form-group">
                                        <label class="fs-">{{ t('app.validUntil') }} <span
                                                class="text-danger">*</span>:</label>
                                        <input type="datetime-local" name="valid_until" class="col-md-12 text-center"
                                            value="{{ $data['existingTemporaryQuote']->valid_until
                                                ? \Carbon\Carbon::parse($data['existingTemporaryQuote']->valid_until)->format('Y-m-d\TH:i')
                                                : '' }}"
                                            required>
                                        <p class="text-danger">{{ $errors->first('valid_until') }}</p>
                                    </div>
                                </div>


                                <button type="submit"
                                    class="btn btn-primary w-100 mt-2 mb-5">{{ t('app.buttons.update') }}</button>

                            </form>
                            {{-- ################################################# --}}
                            <div class="mt-5 mb-5">
                                <a class="btn btn-success btn-lg w-100 fs-4 py-3"
                                    href="{{ route('createQuoteFinalizeAndSend', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}">
                                    <i class="fas fa-check me-2"></i>
                                    {{ t('app.buttons.saveAndSendQuote') }}
                                </a>
                            </div>
                            {{-- preview start --}}
                            @if ($data['existingTemporaryQuote']->issue_date || $data['existingTemporaryQuote']->valid_until)
                                <div class="pdf-preview-wrapper">

                                    {{-- Toolbar --}}
                                    <div class="pdf-preview-toolbar">
                                        <span>{{ t('app.sales.quotePreview') }}</span>
                                    </div>

                                    {{-- PDF iframe --}}
                                    <div class="pdf-preview-body">
                                        <iframe id="pdf-preview-frame"
                                            src="{{ route('createPreviewTemporaryQuote', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}#toolbar=0&navpanes=0&scrollbar=0"
                                            width="100%" height="100%" style="border:none">
                                        </iframe>
                                    </div>

                                </div>

                                <script>
                                    function reloadPreview() {
                                        const frame = document.getElementById('pdf-preview-frame');
                                        frame.src = frame.src; // reload iframe
                                    }
                                </script>
                            @endif
                            {{-- preview end --}}

                            <div class="mt-5 mb-5">
                                <a class="btn btn-success btn-lg w-100 fs-4 py-3"
                                    href="{{ route('createQuoteFinalizeAndSend', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}">
                                    <i class="fas fa-check me-2"></i>
                                    {{ t('app.buttons.saveAndSendQuote') }}
                                </a>
                            </div>
                            {{-- quote summation start --}}
                            @include('users.admin.salesMarketing.quotes.createNewQuote.include.quoteSummation')
                            {{-- quote summation end --}}
                            <div class="d-flex justify-content-between">
                                <a class="btn btn-secondary"
                                    href="{{ route('createQuoteNotes', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}">
                                    <i class="fas fa-chevron-left me-2"></i>
                                    {{ t('app.buttons.back') }}
                                </a>

                                {{-- <a class="btn btn-primary"
                                        href="{{ route('createQuoteFinish', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}">
                                        {{ t('app.buttons.proceed') }}
                                        <i class="fas fa-chevron-right ms-2"></i>
                                    </a> --}}
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

                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->





    </div>
@endsection
