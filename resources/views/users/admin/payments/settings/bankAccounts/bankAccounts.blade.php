@extends('layouts.adminLayout')
@section('title', t('app.payment.bankAccounts'))
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">{{ t('app.payment.bankAccounts') }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ t('app.payment.payment') }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ t('app.settings.settings') }}</a></li>
                    <li class="breadcrumb-item">{{ t('app.payment.bankAccounts') }}</li>
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
                        <button onclick="addBankAccountFormModal('swal-size-md')" class="btn btn-primary">
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
                                                <th scope="row">{{ t('app.payment.bankName') }}</th>
                                                <th scope="row">{{ t('app.payment.accountNumber') }}</th>
                                                <th scope="row">{{ t('app.payment.accountName') }}</th>
                                                <th scope="row">{{ t('app.payment.currency') }}</th>
                                                <th scope="row">
                                                    {{ t('app.status.status') }}
                                                </th>
                                                <th scope="row" class="">{{ t('app.actions') }}</th>
                                            </tr>
                                        </thead>
                                        @if ($data['bankAccounts']->isEmpty())
                                            <tbody>
                                                <td colspan="7" class="text-center text-danger fw-bold fs-5">
                                                    {{ t('app.noRecordsFound') }}
                                                </td>
                                            </tbody>
                                        @else
                                            <tbody>
                                                @foreach ($data['bankAccounts'] as $account)
                                                    <tr>
                                                        <td>
                                                            {{ $loop->iteration }}
                                                        </td>
                                                        <td>
                                                            {{ $account->bank_name }}
                                                        </td>
                                                        <td>
                                                            {{ $account->account_number }}
                                                        </td>
                                                        <td>
                                                            {{ $account->account_name }}
                                                        </td>
                                                        <td class="text-uppercase">
                                                            {{ $account->currency }}
                                                        </td>
                                                        <td>
                                                            @switch($account->is_active)
                                                                @case(true)
                                                                    <span
                                                                        class="badge bg-soft-success text-success">{{ t('app.status.active') }}</span>
                                                                @break

                                                                @case(false)
                                                                    <span
                                                                        class="badge bg-soft-danger text-danger">{{ t('app.status.inactive') }}</span>
                                                                @break

                                                                @default
                                                            @endswitch
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-primary text-white cursor-pointer"
                                                                onclick="editBankAccountFormModal{{ $account->id }}()"
                                                                id="edit-{{ $account->id }}">
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
                @include('users.admin.payments.settings.bankAccounts.include.addBankAccountModal')


                @if (!$data['bankAccounts']->isEmpty())
                    @foreach ($data['bankAccounts'] as $account)
                        @include('users.admin.payments.settings.bankAccounts.include.editBankAccountModal')

                        {{-- ############ --}}
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                @if (session('editBankAccountId'))
                                    const el = document.querySelector('[data-id="{{ session('editBankAccountId') }}"]');
                                    if (el) editBankAccountFormModal(el);
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
