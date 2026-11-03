@extends('layouts.adminLayout')
@section('title', t('app.settings.address.contactDetails'))
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">{{ t('app.settings.address.contactDetails') }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ t('app.settings.settings') }}</a></li>
                    <li class="breadcrumb-item">{{ t('app.settings.address.contactDetails') }}</li>
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
                        {{--
                        <a href="{{ route('addNewSlider') }}" class="btn btn-primary">
                            <i class="feather-plus me-2"></i>
                            <span>{{ t('app.addNew') }}</span>
                        </a> --}}
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
                <div class="col-md-12 row">
                    {{-- inner nav start --}}
                    <div class="col-md-2">
                        @include('users.admin.config.homepage.include.inner_nav')
                    </div>
                    {{-- Inner nav end --}}
                    <div class="card stretch stretch-full col-md-10">
                        <div class="card-header">
                            <h5 class="card-title">
                                {{ t('app.settings.address.contacts') }}
                            </h5>
                        </div>
                        <div class="card-body custom-card-action p-1 pb-3 row col-md-12">
                            <div class="col-md-6">
                                <p><span class="fw-bold">{{ t('auth.phone') }}:
                                    </span>
                                    {{ $data['systemSettings']->phone ?? '-' }}
                                </p>
                                <p><span class="fw-bold">{{ t('auth.email') }}:
                                    </span>
                                    {{ $data['systemSettings']->email ?? '-' }}
                                </p>
                                <p><span class="fw-bold">{{ t('app.settings.address.address') }}:
                                    </span>
                                    {{ $data['systemSettings']->address ?? '-' }}
                                </p>
                                <div class="d-flex">
                                    <span onclick="editContactFormModal('swal-size-md')"
                                        class="btn btn-primary text-white ps-1">
                                        <i class="feather-edit ms-1"></i>
                                        <span>{{ t('app.buttons.edit') }}</span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-decoration-underline">{{ t('app.settings.address.whatsappSupport') }}</h5>
                                @if ($data['whatsAppSupportLines']->count() > 0)
                                    @foreach ($data['whatsAppSupportLines'] as $line)
                                        <li>
                                            {{ $line->name }} ==> {{ $line->number }} ==> @switch($line->status)
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
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $line->number) }}"
                                                class="ms-3" target="_blank" rel="noopener noreferrer"
                                                title="{{ t('app.settings.address.chatOnWhatsApp') }}">
                                                <i class="fab fa-whatsapp"
                                                    style="color: #25D366; font-size: 18px; margin-left: 8px;"></i>
                                            </a>

                                            <span onclick="editWhatsAppSupportFormModal{{ $line->id }}()"
                                                class="badge bg-soft-primary text-primary ps-1" style="cursor: pointer;"
                                                id="edit-{{ $line->id }}">
                                                <i class="feather-plus ms-1"></i>
                                                <span>{{ t('app.buttons.edit') }}</span>
                                            </span>

                                        </li>
                                    @endforeach
                                @else
                                    <h5 class="text-danger text-center">{{ t('notifications.noRecordsFound') }}</h5>
                                @endif
                                <div class="d-flex m-3">
                                    <span onclick="addWhatsAppSupportFormModal('swal-size-md')"
                                        class="btn btn-primary text-white ps-1">
                                        <i class="feather-plus ms-1"></i>
                                        <span>{{ t('app.addNew') }}</span>
                                    </span>
                                </div>

                            </div>

                        </div>


                    </div>

                </div>
                <!-- [Latest Leads] end -->

                {{-- Modal start --}}
                @include('users.admin.config.homepage.include.editContactModal')
                @include('users.admin.config.homepage.include.addWhatsAppSupportModal')



                @if ($data['whatsAppSupportLines']->count() > 0)
                    @foreach ($data['whatsAppSupportLines'] as $line)
                        @include('users.admin.config.homepage.include.editWhatsAppSupportModal')
                    @endforeach

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            @if (session('editLineId'))
                                editWhatsAppSupportFormModal({{ session('editLineId') }});
                            @endif
                        });
                    </script>
                @endif



                {{-- Modal end --}}

            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
@endsection
