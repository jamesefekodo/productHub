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
                            {{ t('app.product.quote') }} :: {{ $data['quote']->quote_number }}
                        </h3>


                        <div class="card-body general-info">
                            <div class="card-body">
                                <h4>{{ t('app.sales.quoteDetails') }}:</h4>
                                <div class="row col-md-12 mb-4">
                                    <div class="col-md-6">
                                        <p class="m-0 p-0"><span
                                                class="fw-bold">{{ t('app.sales.quoteRequestRef') }}:</span> <span
                                                class="text-uppercase">{{ $data['quote']->quoteRequest->slug }}</span> <a
                                                href="{{ route('viewQuote', ['slug' => $data['quote']->quoteRequest->slug]) }}"
                                                target="_blank" class="badge bg-soft-primary text-primary"><i
                                                    class="fas fa-eye me-2"></i></a>
                                        </p>
                                        <p class="m-0 p-0">
                                            <span class="fw-bold">{{ t('app.sales.customer') }}:</span>
                                            @php
                                                $customer = $data['quote']->theCustomer;

                                                $firstName =
                                                    $customer->first_name ?? ($data['quote']->customer_name ?? '');
                                                $middleName = $customer->middle_name ?? '';
                                                $lastName = $customer->last_name ?? '';
                                                $email = $customer->email1 ?? ($data['quote']->email ?? '');
                                                $phone = $customer->phone1 ?? ($data['quote']->phone ?? '');
                                            @endphp

                                            {{ trim($firstName . ' ' . $middleName . ' ' . $lastName) }}

                                            @if ($data['quote']->theCustomer)
                                                <a href="" target="_blank" class="me-2">
                                                    <span class="badge bg-soft-primary text-primary"><i
                                                            class="feather-user p-1"></i></span>
                                                </a>
                                            @endif
                                        </p>
                                        <p class="m-0 p-0">
                                            <span class="fw-bold">{{ t('auth.email') }}:</span>
                                            {{ $email }}
                                        </p>
                                        <p class="m-0 p-0">
                                            <span class="fw-bold">{{ t('auth.phone') }}:</span>
                                            {{ $phone }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="m-0 p-0">
                                            <span class="fw-bold">{{ t('app.sales.quoteNumber') }}:</span>
                                            <span class="text-uppercase">
                                                {{ $data['quote']->quote_number }}
                                            </span>
                                        </p>
                                        <p class="m-0 p-0">
                                            <span class="fw-bold">{{ t('app.createdBy') }}:</span>
                                            @if ($data['quote']->createdBy)
                                                {{ $data['quote']->createdBy->first_name
                                                    ? $data['quote']->createdBy->first_name . ' ' . $data['quote']->createdBy->last_name
                                                    : $data['quote']->createdBy->username }}
                                            @endif
                                            @if ($data['quote']->createdBy)
                                                <a href="" target="_blank" class="me-2 mb-1">
                                                    <span class="badge bg-soft-primary text-primary"><i
                                                            class="feather-user p-1"></i></span>
                                                </a>
                                            @endif |
                                            {{ \Carbon\Carbon::parse($data['quote']->created_at)->isoFormat('MMMM Do, YYYY [at] h:mm A') }}
                                        </p>
                                        <p class="m-0 p-0">
                                            <span class="fw-bold">{{ t('app.status.status') }}:</span>
                                            @switch($data['quote']->status)
                                                @case('draft')
                                                    <span
                                                        class="badge bg-soft-warning text-warning">{{ t('app.status.draft') }}</span>
                                                @break

                                                @case('submitted')
                                                    <span
                                                        class="badge bg-soft-warning text-warning">{{ t('app.status.submitted') }}</span>
                                                @break

                                                @case('quoted')
                                                    <span
                                                        class="badge bg-soft-primary text-primary">{{ t('app.status.quoted') }}</span>
                                                @break

                                                @case('approved')
                                                    <span
                                                        class="badge bg-soft-primary text-primary">{{ t('app.status.approved') }}</span>
                                                @break

                                                @case('rejected')
                                                    <span
                                                        class="badge bg-soft-danger text-danger">{{ t('app.status.rejected') }}</span>
                                                @break

                                                @case('expired')
                                                    <span
                                                        class="badge bg-soft-danger text-danger">{{ t('app.status.expired') }}</span>
                                                @break

                                                @case('converted')
                                                    <span
                                                        class="badge bg-soft-danger text-danger">{{ t('app.status.converted') }}</span>
                                                @break

                                                @default
                                                    {{ $data['quote']->status }}
                                            @endswitch
                                        </p>
                                    </div>

                                </div>

                                <h4>{{ t('app.product.products') }}:</h4>
                                <div class="table-responsive">
                                    <table class="table table-hover m-0">
                                        <thead>
                                            <tr class="border-b">
                                                <th scope="row">#</th>
                                                <th scope="row">{{ t('app.thumbnail') }}</th>
                                                <th scope="row">{{ t('app.product.product') }}</th>
                                                <th scope="row">{{ t('app.product.sku') }}</th>
                                                <th scope="row">{{ t('app.product.quantity') }}</th>
                                                <th scope="row">{{ t('app.sales.unitPrice') }}</th>
                                                <th scope="row">{{ t('app.sales.total') }}</th>
                                            </tr>
                                        </thead>
                                        @if ($data['quote']->items->isEmpty())
                                            <tbody>
                                                <td colspan="8" class="text-center text-danger fw-bold fs-5">
                                                    {{ t('app.sales.noProductsHere') }}
                                                </td>
                                            </tbody>
                                        @else
                                            <tbody>
                                                @foreach ($data['quote']->items as $item)
                                                    <tr>
                                                        <td>
                                                            {{ $loop->iteration }}
                                                        </td>
                                                        @if ($item->variant)
                                                            <td>
                                                                <img src="{{ optional($item->variant->mediaFile)->file_name
                                                                    ? asset('assets/uploads/products/products/media/images/' . $item->variant->mediaFile->file_name)
                                                                    : asset('system/images/icon_placeholder.png') }}"
                                                                    alt="{{ $item->variant->name }}" width="90">
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $locale = currentLocale();
                                                                    $field = 'name_' . $locale;
                                                                    $variantName = !empty($item->variant->$field)
                                                                        ? $item->variant->$field
                                                                        : $item->variant->name;
                                                                @endphp
                                                                {{ $variantName }}
                                                                <a href="{{ route('viewProductVariant', ['slug' => $item->variant->slug]) }}"
                                                                    target="_blank" class="badge bg-primary text-white">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                {{ $item->variant->sku }}
                                                            </td>
                                                        @else
                                                            <td>
                                                                @php
                                                                    $thumbnailImage = asset(
                                                                        'system/images/icon_placeholder.png',
                                                                    );

                                                                    if (!$item->product->mediaFiles->isEmpty()) {
                                                                        $primaryImage = $item->product->mediaFiles
                                                                            ->where('is_primary', true)
                                                                            ->first();

                                                                        if ($primaryImage) {
                                                                            $thumbnailImage = asset(
                                                                                'assets/uploads/products/products/media/images/' .
                                                                                    $primaryImage->file_name,
                                                                            );
                                                                        }
                                                                    }
                                                                @endphp

                                                                <img src="{{ $thumbnailImage }}"
                                                                    alt="{{ $item->product->name }}" width="90px">
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $locale = currentLocale();
                                                                    $field = 'name_' . $locale;
                                                                    $productName = !empty($item->product->$field)
                                                                        ? $item->product->$field
                                                                        : $item->product->name;
                                                                @endphp

                                                                {{ $productName }}
                                                                <a href="{{ route('viewProduct', ['slug' => $item->product->slug]) }}"
                                                                    target="_blank" class="badge bg-primary text-white">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                {{ $item->product->sku }}
                                                            </td>
                                                        @endif
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>₦{{ number_format($item->unit_price, 2) }}</td>
                                                        <td>
                                                            @php
                                                                $total = $item->unit_price * $item->quantity;
                                                            @endphp
                                                            ₦{{ number_format($total, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        @endif
                                    </table>
                                </div>
                                {{-- quote summation start --}}
                                @php
                                    $data['existingTemporaryQuote'] = $data['quote'];
                                @endphp
                                @include('users.admin.salesMarketing.quotes.createNewQuote.include.quoteSummation')
                                {{-- quote summation end --}}
                            </div>
                            <div class="d-flex justify-content-end">
                                <a class="btn btn-primary"
                                    href="{{ route('createQuoteTaxDiscount', ['quote_number' => $data['quote']->quote_number]) }}">
                                    {{ t('app.buttons.proceed') }}
                                    <i class="fas fa-chevron-right ms-2"></i> &nbsp;
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


            </div>
        </div>
        <!-- [ Main Content ] end -->


    </div>
@endsection
