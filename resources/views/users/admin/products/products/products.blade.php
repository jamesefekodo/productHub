@extends('layouts.adminLayout')
@section('title', $data['pageTitle'])
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">{{ t('app.product.products') }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ t('app.product.products') }}</a></li>
                    <li class="breadcrumb-item">{{ t('app.product.products') }}</li>
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
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0);" class="dropdown-item filter-option" data-filter="available">
                                    <span class="wd-7 ht-7 bg-success rounded-circle d-inline-block me-3"></span>
                                    <span>{{ t('app.status.available') }}</span>
                                </a>
                                <a href="javascript:void(0);" class="dropdown-item filter-option" data-filter="unavailable">
                                    <span class="wd-7 ht-7 bg-danger rounded-circle d-inline-block me-3"></span>
                                    <span>{{ t('app.status.notAvailable') }}</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0);" class="dropdown-item filter-option" data-filter="featured">
                                    <span class="wd-7 ht-7 bg-warning rounded-circle d-inline-block me-3"></span>
                                    <span>{{ t('app.status.featured') }}</span>
                                </a>
                                <a href="javascript:void(0);" class="dropdown-item filter-option"
                                    data-filter="not-featured">
                                    <span class="wd-7 ht-7 bg-secondary rounded-circle d-inline-block me-3"></span>
                                    <span>{{ t('app.status.notFeatured') }}</span>
                                </a>
                            </div>
                        </div>
                        <a href="{{ route('addNewProducts') }}" class="btn btn-primary">
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
                <div class="col-xxl-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">
                                {{ $data['pageTitle'] }}
                                {{-- <small class="text-muted fw-normal fs-6">
                                    ({{ $data['totalProducts'] }})
                                </small> --}}
                            </h5>

                            {{-- search area start --}}

                            <div class="table-search">
                                <!-- Search form -->
                                <form method="GET" class="search-form" action="{{ route('productsSearch') }}">
                                    @csrf
                                    <input type="text" name="searchKey" class="form-control form-control-sm"
                                        placeholder="{{ t('app.search') }}..." value="{{ $data['searchKey'] }}">
                                </form>
                                <!-- Toggle button (visible only on mobile) -->
                                <button type="button" class="btn btn-sm btn-light search-toggle">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>


                            {{-- search area end --}}
                        </div>
                        <div class="card-body custom-card-action p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr class="border-b">
                                            <th scope="row">#</th>
                                            <th scope="row">{{ t('app.thumbnail') }}</th>
                                            <th scope="row">{{ t('app.product.sku') }}</th>
                                            <th scope="row">{{ t('app.product.product') }}</th>
                                            <th scope="row">{{ t('app.product.brand') }}</th>
                                            <th scope="row">{{ t('app.product.category') }}</th>
                                            <th scope="row">{{ t('app.product.ratings') }}</th>
                                            <th scope="row">{{ t('app.product.price') }}</th>
                                            <th scope="row">{{ t('app.published') }}</th>
                                            <th scope="row">{{ t('app.product.availability') }}</th>
                                            <th scope="row">{{ t('app.product.featured') }}</th>
                                            <th scope="row" class="">{{ t('app.actions') }}</th>
                                        </tr>
                                    </thead>
                                    @if ($data['products']->isEmpty())
                                        <tbody>
                                            <td colspan="11" class="text-center text-danger fw-bold fs-5">
                                                {{ t('app.noRecordsFound') }}
                                            </td>
                                        </tbody>
                                    @else
                                        <tbody>
                                            @foreach ($data['products'] as $product)
                                                <tr>
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $thumbnailImage = asset(
                                                                'system/images/icon_placeholder.png',
                                                            );

                                                            if (!$product->mediaFiles->isEmpty()) {
                                                                $primaryImage = $product->mediaFiles
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

                                                        <img src="{{ $thumbnailImage }}" alt="{{ $product->name }}"
                                                            width="90px">
                                                    </td>
                                                    <td class="text-uppercase">
                                                        {{ $product->sku }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $locale = currentLocale();
                                                            $field = 'name_' . $locale;
                                                            $value = !empty($product->$field)
                                                                ? $product->$field
                                                                : $product->name;
                                                        @endphp

                                                        {{ $value }}
                                                    </td>
                                                    <td>

                                                        @if ($product->brand)
                                                            @php
                                                                $logoPath = public_path(
                                                                    'assets/uploads/products/brands/logo/' .
                                                                        $product->brand->logo,
                                                                );
                                                            @endphp
                                                            @if ($product->brand->logo && file_exists($logoPath))
                                                                <img src="{{ asset('assets/uploads/products/brands/logo/' . $product->brand->logo) }}"
                                                                    width="100">
                                                            @else
                                                                <img src="{{ asset('system/images/logo_placeholder.jpg') }}"
                                                                    width="100">
                                                            @endif
                                                        @else
                                                            -
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @php
                                                            $locale = currentLocale();
                                                            $field = 'category_' . $locale;
                                                            $value = !empty($product->category->$field)
                                                                ? $product->category->$field
                                                                : $product->category->category;
                                                        @endphp

                                                        {{ $value }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $rating = 3.75;
                                                        @endphp

                                                        <div class="rating">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($rating >= $i)
                                                                    <i class="fas fa-star text-warning"></i>
                                                                @elseif ($rating >= $i - 0.5)
                                                                    <i class="fas fa-star-half-alt text-warning"></i>
                                                                @else
                                                                    <i class="far fa-star text-muted"></i>
                                                                @endif
                                                            @endfor
                                                            <span class="ms-2">{{ number_format($rating, 2) }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if (!is_null($product->price) && $product->price != 0)
                                                            ₦{{ number_format($product->price, 2) }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $product->is_active ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}"
                                                            data-type="active"
                                                            data-value="{{ $product->is_active ? 1 : 0 }}">
                                                            {{ $product->is_active ? t('app.status.true') : t('app.status.false') }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span
                                                            class="badge {{ $product->is_available ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}"
                                                            data-type="available"
                                                            data-value="{{ $product->is_available ? 1 : 0 }}">
                                                            {{ $product->is_available ? t('app.status.available') : t('app.status.notAvailable') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $product->is_featured ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}"
                                                            data-type="featured"
                                                            data-value="{{ $product->is_featured ? 1 : 0 }}">
                                                            {{ $product->is_featured ? t('app.status.true') : t('app.status.false') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('viewProduct', ['slug' => $product->slug]) }}"
                                                            class="btn btn-primary d-inline-flex align-items-center gap-1">
                                                            <i class="feather-eye"></i>
                                                            {{ t('app.buttons.view') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @endif
                                </table>
                            </div>
                        </div>

                        {{-- Navigation --}}
                        @php
                            $paginatedArray = $data['products'];
                        @endphp
                        @include('utilities.pages.others.pagination')


                    </div>

                </div>
                {{-- Back Home --}}
                <div class="row mb-4 justify-content-center">
                    <div class="col-auto">
                        @switch($data['requestType'])
                            @case('search')
                                <a class="btn btn-primary" href="{{ route('products') }}">
                                    <i class="fas fa-chevron-left"></i> &nbsp;
                                    {{ t('app.buttons.back') }}
                                </a>
                            @break

                            @default
                        @endswitch
                    </div>
                </div>


                <!-- [Latest Leads] end -->

            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>

    {{-- filter script start --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-option');
            const tableRows = document.querySelectorAll('table tbody tr');

            function filterTable(status) {
                tableRows.forEach(row => {
                    if (row.querySelector('td[colspan]')) return;

                    const badges = row.querySelectorAll('.badge[data-type]');
                    if (!badges.length) {
                        row.style.display = '';
                        return;
                    }

                    let rowActive = false;
                    let rowAvailable = false;
                    let rowFeatured = false;

                    badges.forEach(badge => {
                        const type = badge.dataset.type;
                        const value = badge.dataset.value === '1';

                        if (type === 'active') rowActive = value;
                        if (type === 'available') rowAvailable = value;
                        if (type === 'featured') rowFeatured = value;
                    });

                    let show = false;

                    switch (status) {
                        case 'all':
                            show = true;
                            break;
                        case 'active':
                            show = rowActive;
                            break;
                        case 'inactive':
                            show = !rowActive;
                            break;
                        case 'available':
                            show = rowAvailable;
                            break;
                        case 'unavailable':
                            show = !rowAvailable;
                            break;
                        case 'featured':
                            show = rowFeatured;
                            break;
                        case 'not-featured':
                            show = !rowFeatured;
                            break;
                    }

                    row.style.display = show ? '' : 'none';
                });

                filterButtons.forEach(btn => {
                    if (btn.getAttribute('data-filter') === status) {
                        btn.classList.add('active', 'fw-bold');
                        btn.style.backgroundColor = '#f0f4ff';
                    } else {
                        btn.classList.remove('active', 'fw-bold');
                        btn.style.backgroundColor = '';
                    }
                });
            }

            filterButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    filterTable(this.getAttribute('data-filter'));
                });
            });

            filterTable('all');
        });
    </script>
    {{-- filter script end --}}
@endsection
