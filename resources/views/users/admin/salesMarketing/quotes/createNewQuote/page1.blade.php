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
                                class="fs-3 text-uppercase">{{ $data['existingTemporaryQuote']->quote_number }}</p>

                        {{-- ############# --}}
                        <div class="card-body general-info">
                            <div class="card-body">
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

                                                <th scope="row" class="">{{ t('app.actions') }}</th>
                                            </tr>
                                        </thead>
                                        @if ($data['existingTemporaryQuote']->items->isEmpty())
                                            <tbody>
                                                <td colspan="8" class="text-center text-danger fw-bold fs-5">
                                                    {{ t('app.sales.noProductsHere') }}
                                                </td>
                                            </tbody>
                                        @else
                                            <tbody>
                                                @foreach ($data['existingTemporaryQuote']->items as $item)
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
                                                                    target="_blank" class="badge bg-primary text-white"> <i
                                                                        class="fas fa-eye"></i>
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
                                                        <td class="text-primary">
                                                            <a class="badge bg-primary m-1 p-2 text-white cursor-pointer"
                                                                onclick="editItemModal_{{ $item->id }}()">
                                                                <i class="feather-edit"></i> {{ t('app.buttons.update') }}
                                                            </a>
                                                            <a class="badge bg-danger m-1 p-2 text-white cursor-pointer"
                                                                onclick="deleteItemModal_{{ $item->id }}()">
                                                                <i class="feather-trash"></i> {{ t('app.buttons.remove') }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        @endif
                                    </table>
                                </div>
                                {{-- quote summation start --}}
                                @include('users.admin.salesMarketing.quotes.createNewQuote.include.quoteSummation')
                                {{-- quote summation end --}}
                                @include('users.admin.salesMarketing.quotes.createNewQuote.include.findProduct')
                            </div>
                            <div class="d-flex justify-content-end">
                                <a class="btn btn-primary"
                                    href="{{ route('createQuoteTaxDiscount', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}">
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



        {{-- update and delete scripts start --}}
        @if ($data['existingTemporaryQuote']->items->isNotEmpty())
            @foreach ($data['existingTemporaryQuote']->items as $item)
                <script>
                    function editItemModal_{{ $item->id }}(sizeClass = 'swal-size-md mx-5') {
                        Swal.fire({
                            title: '{{ t('app.product.updateQuantity') }}',
                            showCloseButton: true,
                            showConfirmButton: false,
                            customClass: {
                                popup: sizeClass
                            },
                            html: `
                    <form id="swalUpdateForm_{{ $item->id }}" method="POST" action="{{ route('adminUpdateTemporaryQuoteItem') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('item_id') }}</p>
                        <input type="hidden" name="quote_number" value="{{ $data['existingTemporaryQuote']->quote_number }}">
                        <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('quote_number') }}</p>
                        <div class="mb-3 mt-3">
                            <label class="form-label">{{ t('app.product.quantity') }}</label>
                            <input type="number" name="quantity" class="form-control text-center"
                                value="{{ $item->quantity }}" min="1" required>
                            <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('quantity') }}</p>
                        </div>
                        <div class="mb-3 mt-3">
                            <label class="form-label">{{ t('app.sales.unitPrice') }} (₦)</label>
                            <input type="number" name="unit_price" class="form-control text-center" value="{{ $item->unit_price }}" min="1" required>
                             <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('unit_price') }}</p>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button type="button" class="btn btn-secondary w-50" onclick="Swal.close()">
                                {{ t('app.buttons.cancel') }}
                            </button>
                            <button type="submit" class="btn btn-primary w-50">
                                {{ t('app.buttons.update') }}
                            </button>
                        </div>
                    </form>
                `
                        });
                    }

                    function deleteItemModal_{{ $item->id }}(sizeClass = 'swal-size-md mx-5') {
                        Swal.fire({
                            title: '{{ t('app.confirm') }}',
                            showCloseButton: true,
                            icon: 'warning',
                            showConfirmButton: false,
                            customClass: {
                                popup: sizeClass
                            },
                            html: `
                    <form id="swalDeleteForm_{{ $item->id }}" class="fs-4" method="POST" action="{{ route('adminDeleteTemporaryQuoteItem') }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
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
        {{-- update and delete scripts end --}}




    </div>
@endsection
