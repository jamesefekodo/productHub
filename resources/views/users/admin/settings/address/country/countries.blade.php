@extends('layouts.adminLayout')
@section('title', $data['pageTitle'])
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">{{ t('app.settings.address.countries') }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ t('app.settings.settings') }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ t('app.settings.address.address') }}</a></li>
                    <li class="breadcrumb-item">{{ t('app.settings.address.countries') }}</li>
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
                        <button onclick="addCountryFormModal('swal-size-md')" class="btn btn-primary">
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
                    @include('users.admin.settings.address.country.include.inner_nav')
                </div>

                {{-- Inner nav end --}}
                <!-- [Latest Leads] start -->
                <div class="col-md-10">
                    <div class="col-xxl-12">
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">
                                    {{ $data['pageTitle'] }}
                                    {{-- <small class="text-muted fw-normal fs-6">
                                        ({{ $data['totalCountries'] }})
                                    </small> --}}
                                </h5>

                                {{-- search area start --}}

                                <div class="table-search">
                                    <!-- Search form -->
                                    <form method="GET" class="search-form" action="{{ route('countriesSearch') }}">
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
                                                <th scope="row">{{ t('app.settings.address.countries') }}</th>
                                                <th scope="row">{{ t('app.settings.address.phoneCode') }}</th>
                                                <th scope="row">
                                                    {{ t('app.settings.address.states') }}/{{ t('app.settings.address.provinces') }}
                                                </th>
                                                <th scope="row">
                                                    {{ t('app.settings.address.lga') }}/{{ t('app.settings.address.cities') }}
                                                </th>
                                                <th scope="row">{{ t('app.status.status') }}</th>
                                                <th scope="row" class="">{{ t('app.actions') }}</th>
                                            </tr>
                                        </thead>
                                        @if ($data['countries']->isEmpty())
                                            <tbody>
                                                <td colspan="7" class="text-center text-danger fw-bold fs-5">
                                                    {{ t('app.noRecordsFound') }}
                                                </td>
                                            </tbody>
                                        @else
                                            <tbody>
                                                @foreach ($data['countries'] as $country)
                                                    <tr>
                                                        <td>
                                                            {{ $loop->iteration }}
                                                        </td>
                                                        <td>
                                                            {{ $country->name }}
                                                        </td>
                                                        <td>
                                                            {{ $country->phone_code }}
                                                        </td>
                                                        <td>
                                                            {{ $country->states->count() }}
                                                        </td>
                                                        <td>
                                                            {{ $country->cities->count() }}
                                                        </td>
                                                        <td>
                                                            @switch($country->status)
                                                                @case('active')
                                                                    <span
                                                                        class="badge bg-soft-success text-success">{{ t('app.status.active') }}</span>
                                                                @break

                                                                @case('inactive')
                                                                    <span
                                                                        class="badge bg-soft-danger text-danger">{{ t('app.status.inactive') }}</span>
                                                                @break

                                                                @default
                                                            @endswitch
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-primary text-white cursor-pointer"
                                                                onclick="editCountryFormModal{{ $country->id }}()"
                                                                id="edit-{{ $country->id }}">
                                                                <i class="feather-edit"></i> {{ t('app.buttons.edit') }}
                                                            </span>

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
                                $paginatedArray = $data['countries'];
                            @endphp
                            @include('utilities.pages.others.pagination')


                        </div>

                    </div>
                </div>
                {{-- Back Home --}}
                <div class="row mb-4 justify-content-center">
                    <div class="col-auto">
                        @switch($data['requestType'])
                            @case('search')
                                <a class="btn btn-primary" href="{{ route('countries') }}">
                                    <i class="fas fa-chevron-left"></i> &nbsp;
                                    {{ t('app.buttons.back') }}
                                </a>
                            @break

                            @default
                        @endswitch
                    </div>
                </div>


                <!-- [Latest Leads] end -->

                {{-- <button onclick="openFormModal('swal-size-sm')">Small</button>
                <button onclick="openFormModal('swal-size-md')">Medium</button>
                <button onclick="openFormModal('swal-size-lg')">Large</button>
                <button onclick="openFormModal('swal-size-xl', 'Big Form')">Extra Large</button> --}}


                {{-- modals start --}}
                @include('users.admin.settings.address.country.include.addCountryModal')

                @if (!$data['countries']->isEmpty())
                    @foreach ($data['countries'] as $country)
                        @include('users.admin.settings.address.country.include.editCountryModal')

                        {{-- ############ --}}
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                @if (session('editCountryId'))
                                    const el = document.querySelector('[data-id="{{ session('editCountryId') }}"]');
                                    if (el) editCountryFormModal(el);
                                @endif
                            });
                        </script>
                        {{-- ############ --}}
                    @endforeach
                @endif
                {{-- modals end --}}
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>




    {{-- ################################# --}}


    {{-- filter script start --}}
        @include('users.admin.settings.address.states.include.addressFilter')
    {{-- filter script end --}}


@endsection
