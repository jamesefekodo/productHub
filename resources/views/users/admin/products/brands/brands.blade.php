@extends('layouts.adminLayout')
@section('title', $data['pageTitle'])
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">{{ t('app.product.brands') }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ t('app.product.products') }}</a></li>
                    <li class="breadcrumb-item">{{ t('app.product.brands') }}</li>
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
                        <button onclick="addBandFormModal('swal-size-md')" class="btn btn-primary">
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

                <!-- [Latest Leads] start -->
                <div class="col-xxl-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">
                                {{ $data['pageTitle'] }}
                                {{-- <small class="text-muted fw-normal fs-6">
                                    ({{ $data['totalBrands'] }})
                                </small> --}}
                            </h5>

                            {{-- search area start --}}

                            <div class="table-search">
                                <!-- Search form -->
                                <form method="GET" class="search-form" action="{{ route('brandsSearch') }}">
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
                                            <th scope="row">{{ t('app.logo') }}</th>
                                            <th scope="row">{{ t('app.name') }}</th>
                                            <th scope="row">
                                                {{ t('app.description') }}
                                            </th>
                                            <th scope="row">
                                                {{ t('app.product.products') }}
                                            </th>
                                            <th scope="row" class="">{{ t('app.actions') }}</th>
                                        </tr>
                                    </thead>
                                    @if ($data['brands']->isEmpty())
                                        <tbody>
                                            <td colspan="6" class="text-center text-danger fw-bold fs-5">
                                                {{ t('app.noRecordsFound') }}
                                            </td>
                                        </tbody>
                                    @else
                                        <tbody>
                                            @foreach ($data['brands'] as $brand)
                                                <tr>
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>

                                                    <td>
                                                        @php
                                                            $logoPath = public_path(
                                                                'assets/uploads/products/brands/logo/' . $brand->logo,
                                                            );
                                                        @endphp

                                                        @if ($brand->logo && file_exists($logoPath))
                                                            <img src="{{ asset('assets/uploads/products/brands/logo/' . $brand->logo) }}"
                                                                width="100">
                                                        @else
                                                            <img src="{{ asset('system/images/logo_placeholder.jpg') }}"
                                                                width="100">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $brand->brand }}
                                                    </td>
                                                    <td>
                                                        {{ \Illuminate\Support\Str::limit($brand->description, 50, '...') }}
                                                    </td>
                                                    <td>
                                                        0
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('viewBrand', ['slug' => $brand->slug]) }}"
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
                            $paginatedArray = $data['brands'];
                        @endphp
                        @include('utilities.pages.others.pagination')


                    </div>

                </div>
                {{-- Back Home --}}
                <div class="row mb-4 justify-content-center">
                    <div class="col-auto">
                        @switch($data['requestType'])
                            @case('search')
                                <a class="btn btn-primary" href="{{ route('brands') }}">
                                    <i class="fas fa-chevron-left"></i> &nbsp;
                                    {{ t('app.buttons.back') }}
                                </a>
                            @break

                            @default
                        @endswitch
                    </div>
                </div>


                <!-- [Latest Leads] end -->


                {{-- modals start --}}
                @include('users.admin.products.brands.include.addBrandModal')
                {{-- modals end --}}
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>


@endsection
