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
                            <!-- Left group: Discounts -->
                            <div class="d-flex gap-2 mb-2 mb-md-0">
                                <button href="" onclick="addDiscountToQuoteModal('swal-size-md')"
                                    class="btn btn-outline-primary">{{ t('app.sales.addDiscount') }}</button>

                                <a href="{{ route('createQuoteLoadDefaultDiscount', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}"
                                    class="btn btn-outline-success">{{ t('app.sales.loadDefaultDiscounts') }}</a>
                            </div>

                            <!-- Right group: Taxes -->
                            <div class="d-flex gap-2">
                                <button onclick="addTaxToQuoteModal('swal-size-md')"
                                    class="btn btn-outline-primary">{{ t('app.sales.addTax') }}</button>
                                <a href="{{ route('createQuoteLoadDefaultTaxes', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}"
                                    class="btn btn-outline-success">{{ t('app.sales.loadDefaultTaxes') }}</a>
                            </div>

                        </div>

                        {{-- ############# --}}
                        <div class="card-body general-info">
                            <div class="row col-md-12">
                                <div class="col-md-6">
                                    <div class="card border-light">
                                        <div class="card-body m-1">
                                            <h4 class="text-primary ps-3">{{ t('app.payment.discounts') }}</h4>
                                            <div class="table-responsive">
                                                <table class="table table-hover m-0">
                                                    <thead>
                                                        <tr class="border-b">
                                                            <th scope="row">#</th>
                                                            <th scope="row">{{ t('app.payment.discount') }}</th>
                                                            <th scope="row" class="">{{ t('app.actions') }}</th>
                                                        </tr>
                                                    </thead>
                                                    @if ($data['existingTemporaryQuote']->discounts->isEmpty())
                                                        <tbody>
                                                            <td colspan="3" class="text-center text-danger fw-bold fs-5">
                                                                {{ t('notifications.noRecordsFound') }}
                                                            </td>
                                                        </tbody>
                                                    @else
                                                        <tbody>
                                                            @foreach ($data['existingTemporaryQuote']->discounts as $discount)
                                                                <tr>
                                                                    <td>
                                                                        {{ $loop->iteration }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $discount->discount->discount_name }}
                                                                        =>
                                                                        @switch($discount->discount->discount_method)
                                                                            @case('percentage')
                                                                                {{ $discount->discount->discount_rate }}%
                                                                            @break

                                                                            @case('fixed_amount')
                                                                                ₦{{ number_format($discount->discount->discount_rate, 2) }}
                                                                            @break

                                                                            @default
                                                                        @endswitch
                                                                    </td>
                                                                    <td>
                                                                        <a class="badge bg-danger m-1 p-2 text-white cursor-pointer"
                                                                            onclick="deleteTaxModal_{{ $discount->id }}()">
                                                                            <i class="feather-trash"></i>
                                                                            {{ t('app.buttons.remove') }}
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
                                <div class="col-md-6">
                                    <div class="card border-light">
                                        <div class="card-body">
                                            <h4 class="text-primary ps-3">{{ t('app.payment.taxes') }}</h4>
                                            <div class="table-responsive">
                                                <table class="table table-hover m-0">
                                                    <thead>
                                                        <tr class="border-b">
                                                            <th scope="row">#</th>
                                                            <th scope="row">{{ t('app.payment.tax') }}</th>
                                                            <th scope="row" class="">{{ t('app.actions') }}</th>
                                                        </tr>
                                                    </thead>
                                                    @if ($data['existingTemporaryQuote']->taxes->isEmpty())
                                                        <tbody>
                                                            <td colspan="3" class="text-center text-danger fw-bold fs-5">
                                                                {{ t('notifications.noRecordsFound') }}
                                                            </td>
                                                        </tbody>
                                                    @else
                                                        <tbody>
                                                            @foreach ($data['existingTemporaryQuote']->taxes as $tax)
                                                                <tr>
                                                                    <td>
                                                                        {{ $loop->iteration }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $tax->tax->tax_name }}
                                                                        =>
                                                                        @switch($tax->tax->tax_method)
                                                                            @case('percentage')
                                                                                {{ $tax->tax->tax_rate }}%
                                                                            @break

                                                                            @case('fixed_amount')
                                                                                ₦{{ number_format($tax->tax->tax_rate, 2) }}
                                                                            @break

                                                                            @default
                                                                        @endswitch
                                                                    </td>
                                                                    <td>
                                                                        <a class="badge bg-danger m-1 p-2 text-white cursor-pointer"
                                                                            onclick="deleteTaxModal_{{ $tax->id }}()">
                                                                            <i class="feather-trash"></i>
                                                                            {{ t('app.buttons.remove') }}
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
                            </div>

                            {{-- quote summation start --}}
                            @include('users.admin.salesMarketing.quotes.createNewQuote.include.quoteSummation')
                            {{-- quote summation end --}}
                            <div class="d-flex justify-content-between">
                                <a class="btn btn-secondary"
                                    href="{{ route('viewDraftedQuote', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}">
                                    <i class="fas fa-chevron-left me-2"></i>
                                    {{ t('app.buttons.back') }}
                                </a>

                                <a class="btn btn-primary"
                                    href="{{ route('createQuoteCustomer', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}">
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


                </div>


                {{-- modals start --}}
                {{-- discounts --}}
                @include('users.admin.salesMarketing.quotes.createNewQuote.include.addDiscountToQuoteModal')

                @if ($data['existingTemporaryQuote']->discounts->isNotEmpty())
                    @foreach ($data['existingTemporaryQuote']->discounts as $discount)
                        <script>
                            function deleteTaxModal_{{ $discount->id }}(sizeClass = 'swal-size-md mx-5') {
                                Swal.fire({
                                    title: '{{ t('app.confirm') }}',
                                    icon: 'warning',
                                    showCloseButton: true,
                                    showConfirmButton: false,
                                    customClass: {
                                        popup: sizeClass
                                    },
                                    html: `
                    <form id="swalDeleteForm_{{ $discount->id }}" class="fs-4" method="POST" action="{{ route('adminDeleteTemporaryQuoteDiscount') }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="discount" value="{{ $discount->id }}">
                        <input type="hidden" name="quote_number" value="{{ $data['existingTemporaryQuote']->quote_number }}">

                        <p class="text-muted">{{ t('app.deleteConfirmText') }}</p>
                        <div class="d-flex gap-2 mt-3">
                            <button type="button" class="btn btn-secondary w-50 px-3" onclick="Swal.close()">
                                {{ t('app.buttons.cancel') }}
                            </button>
                            <button type="submit" class="btn btn-danger w-50 px-3">
                                {{ t('app.buttons.delete') }}
                            </button>
                        </div>
                    </form>
                `
                                });
                            }
                        </script>
                    @endforeach
                @endif
                {{-- ######################### --}}
                {{-- taxes --}}
                @include('users.admin.salesMarketing.quotes.createNewQuote.include.addTaxToQuoteModal')

                @if ($data['existingTemporaryQuote']->taxes->isNotEmpty())
                    @foreach ($data['existingTemporaryQuote']->taxes as $tax)
                        <script>
                            function deleteTaxModal_{{ $tax->id }}(sizeClass = 'swal-size-md mx-5') {
                                Swal.fire({
                                    title: '{{ t('app.confirm') }}',
                                    icon: 'warning',
                                    showCloseButton: true,
                                    showConfirmButton: false,
                                    customClass: {
                                        popup: sizeClass
                                    },
                                    html: `
                    <form id="swalDeleteForm_{{ $tax->id }}" class="fs-4" method="POST" action="{{ route('adminDeleteTemporaryQuoteTax') }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="tax" value="{{ $tax->id }}">
                        <input type="hidden" name="quote_number" value="{{ $data['existingTemporaryQuote']->quote_number }}">

                        <p class="text-muted">{{ t('app.deleteConfirmText') }}</p>
                        <div class="d-flex gap-2 mt-3">
                            <button type="button" class="btn btn-secondary w-50 px-3" onclick="Swal.close()">
                                {{ t('app.buttons.cancel') }}
                            </button>
                            <button type="submit" class="btn btn-danger w-50 px-3">
                                {{ t('app.buttons.delete') }}
                            </button>
                        </div>
                    </form>
                `
                                });
                            }
                        </script>
                    @endforeach
                @endif

                {{-- modal end --}}
            </div>
        </div>
        <!-- [ Main Content ] end -->





    </div>
@endsection
