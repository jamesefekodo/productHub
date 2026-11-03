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
                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 w-100 ps-5 pe-5">
                            <!-- Left group:  -->
                            <div class="d-flex gap-2 mb-2 mb-md-0">
                                <button onclick="addRegisteredCustomerFormModal('swal-size-md')"
                                    class="btn btn-outline-primary">{{ t('app.sales.updateCustomer') }}
                                    ({{ t('app.user.registeredUser') }})</button>

                                <button onclick="addNonRegisteredCustomerFormModal('swal-size-md')" c
                                    class="btn btn-outline-success">{{ t('app.sales.updateCustomer') }}
                                    ({{ t('app.user.nonUser') }})</button>
                            </div>

                        </div>

                        {{-- ############# --}}
                        <div class="card-body general-info">
                            <div class="card">
                                <div class="row g-0">

                                    <div class="col-md-5 text-center p-2" style="overflow: hidden;">
                                        @if (
                                            $data['existingTemporaryQuote'] &&
                                                $data['existingTemporaryQuote']->theCustomer &&
                                                $data['existingTemporaryQuote']->theCustomer->photo)
                                            <img src="{{ asset('assets/uploads/users/photos/' . $data['existingTemporaryQuote']->theCustomer->photo) }}"
                                                alt="{{ $data['existingTemporaryQuote']->theCustomer->username }}"
                                                style="max-width:100%; height:auto; object-fit:contain;">
                                        @else
                                            <img src="{{ asset('system/images/default_profile_pic.jpg') }}"
                                                alt="Default Picture"
                                                style="max-width:100%; height:auto; object-fit:contain;">
                                        @endif
                                    </div>

                                    <div class="col-md-7 d-flex align-items-start p-2">
                                        <div>
                                            <h4 class="text-primary ps-3">{{ t('app.sales.customer') }}</h4>
                                            <p><span class="fw-bold">{{ t('app.sales.customer') }}:</span>
                                                @php
                                                    $customer = $data['existingTemporaryQuote']->theCustomer;

                                                    $firstName =
                                                        $customer->first_name ??
                                                        ($data['existingTemporaryQuote']->customer_name ?? '');
                                                    $middleName = $customer->middle_name ?? '';
                                                    $lastName = $customer->last_name ?? '';
                                                    $email = $customer->email1 ?? ($quote->email ?? '');
                                                    $phone = $customer->phone1 ?? ($quote->phone ?? '');
                                                @endphp

                                                {{ trim($firstName . ' ' . $middleName . ' ' . $lastName) }}

                                                @if ($data['existingTemporaryQuote']->theCustomer)
                                                    <a href="" target="_blank" class="me-2">
                                                        <span class="badge bg-soft-primary text-primary"><i
                                                                class="feather-user p-1"></i></span>
                                                    </a>
                                                @endif
                                            </p>
                                            <p>
                                                <span class="fw-bold">{{ t('auth.phone') }}:</span>

                                                {{ $data['existingTemporaryQuote']->theCustomer
                                                    ? $data['existingTemporaryQuote']->theCustomer->phone1 .
                                                        ($data['existingTemporaryQuote']->theCustomer->phone2
                                                            ? ' / ' . $data['existingTemporaryQuote']->theCustomer->phone2
                                                            : '')
                                                    : $data['existingTemporaryQuote']->customer_phone }}
                                            </p>
                                            <p>
                                                <span class="fw-bold">{{ t('auth.email') }}:</span>

                                                {{ $data['existingTemporaryQuote']->theCustomer
                                                    ? $data['existingTemporaryQuote']->theCustomer->email1 .
                                                        ($data['existingTemporaryQuote']->theCustomer->email2
                                                            ? ' / ' . $data['existingTemporaryQuote']->theCustomer->email2
                                                            : '')
                                                    : $data['existingTemporaryQuote']->customer_email }}
                                            </p>
                                            <p>
                                                <span class="fw-bold">{{ t('app.settings.address.address') }}:</span>

                                                @if ($data['existingTemporaryQuote']->theCustomer && $data['existingTemporaryQuote']->theCustomer->primaryAddress)
                                                    {{ $data['existingTemporaryQuote']->theCustomer->primaryAddress->more_details }},
                                                    {{ $data['existingTemporaryQuote']->theCustomer->primaryAddress->theCity->name }},
                                                    {{ $data['existingTemporaryQuote']->theCustomer->primaryAddress->theState->name }},
                                                    {{ $data['existingTemporaryQuote']->theCustomer->primaryAddress->theCountry->name }}
                                                @else
                                                    {{ $data['existingTemporaryQuote']->customer_address }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{-- quote summation start --}}
                            @include('users.admin.salesMarketing.quotes.createNewQuote.include.quoteSummation')
                            {{-- quote summation end --}}
                            <div class="d-flex justify-content-between">
                                <a class="btn btn-secondary"
                                    href="{{ route('createQuoteTaxDiscount', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}">
                                    <i class="fas fa-chevron-left me-2"></i>
                                    {{ t('app.buttons.back') }}
                                </a>

                                <a class="btn btn-primary"
                                    href="{{ route('createQuoteNotes', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}">
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


                    {{-- modal start --}}
                    @include('users.admin.salesMarketing.quotes.createNewQuote.include.updateRegisteredCustomer')
                     @include('users.admin.salesMarketing.quotes.createNewQuote.include.updateNonRegisteredCustomer')

                    {{-- modal end --}}
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->





    </div>
@endsection
