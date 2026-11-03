@extends('layouts.adminLayout')
@section('title', t('app.payment.paymentMethods'))
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">{{ t('app.payment.paymentMethods') }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ t('app.payment.payment') }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ t('app.settings.settings') }}</a></li>
                    <li class="breadcrumb-item">{{ t('app.payment.paymentMethods') }}</li>
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
                    @include('users.admin.payments.settings.paymentMethods.include.inner_nav')
                </div>

                {{-- Inner nav end --}}
                <!-- [Latest Leads] start -->
                <div class="col-md-10">
                    <div class="col-xxl-12">
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">
                                    {{ t('app.payment.paymentMethods') }}
                                </h5>
                            </div>
                            <div class="card-body custom-card-action p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr class="border-b">
                                                <th scope="row">#</th>
                                                <th scope="row">{{ t('app.payment.paymentMethod') }}</th>
                                                <th scope="row">{{ t('app.logo') }}</th>
                                                <th scope="row">{{ t('app.version') }}</th>
                                                <th scope="row">
                                                    {{ t('app.status.status') }}
                                                </th>
                                                <th scope="row" class="">{{ t('app.actions') }}</th>
                                            </tr>
                                        </thead>
                                        @if ($data['paymentMethods']->isEmpty())
                                            <tbody>
                                                <td colspan="6" class="text-center text-danger fw-bold fs-5">
                                                    {{ t('app.noRecordsFound') }}
                                                </td>
                                            </tbody>
                                        @else
                                            <tbody>
                                                @foreach ($data['paymentMethods'] as $method)
                                                    <tr>
                                                        <td>
                                                            {{ $loop->iteration }}
                                                        </td>
                                                        <td>
                                                            {{ $method->name }}
                                                        </td>
                                                        <td>
                                                            @if ($method->logo)
                                                                <img src="{{ asset('system/images/paymentMethods/logo/' . $method->logo) }}"
                                                                    alt="" width="50px">
                                                            @else
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($method->version)
                                                            <span class="badge bg-soft-warning text-warning">
                                                                {{ $method->version }}
                                                            </span>
                                                            @else
                                                            -
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @switch($method->status)
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
                                                                onclick="editPaymentMethodFormModal{{ $method->id }}()"
                                                                id="edit-{{ $method->id }}">
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

                        </div>

                    </div>
                </div>


                <!-- [Latest Leads] end -->

                {{-- <button onclick="openFormModal('swal-size-sm')">Small</button>
                <button onclick="openFormModal('swal-size-md')">Medium</button>
                <button onclick="openFormModal('swal-size-lg')">Large</button>
                <button onclick="openFormModal('swal-size-xl', 'Big Form')">Extra Large</button> --}}


                {{-- modals start --}}

                @if (!$data['paymentMethods']->isEmpty())
                    @foreach ($data['paymentMethods'] as $method)
                        @include('users.admin.payments.settings.paymentMethods.include.editPaymentMethodModal')

                        {{-- ############ --}}
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                @if (session('editMethodId'))
                                    const el = document.querySelector('[data-id="{{ session('editMethodId') }}"]');
                                    if (el) editPaymentMethodFormModal(el);
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
    {{-- @include('users.admin.settings.address.states.include.addressFilter') --}}
    {{-- filter script end --}}


@endsection
