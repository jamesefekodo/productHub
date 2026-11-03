@extends('layouts.userLayout')
@section('title', t('app.user.myProfile'))
@section('content')

    <div class="desktop-nav-bg"></div>
    <div class="main-content-wrapper">

        <section class="section row col-md-12">
            {{-- inner nav start --}}
            <div class="col-md-2 mt-md-2">
                @include('users.customer.profile.include.inner_nav')
            </div>
            {{-- Inner nav end --}}
            <div class="col-md-10">
                <div class="my-page-header">{{ t('app.user.myProfile') }}</div>

                <div class="my-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-0 row col-md-12">
                            <div class="col-md-4 justify-content-center text-center p-2" style="overflow: hidden;">
                                @if ($data['user'] && $data['user']->photo)
                                    <img src="{{ asset('assets/uploads/users/photos/' . $data['user']->photo) }}"
                                        alt="{{ $data['user']->username }}"
                                        style="max-width: 100%; height: auto; object-fit: contain;">
                                @else
                                    <img src="{{ asset('system/images/default_profile_pic.jpg') }}" alt="Default Picture"
                                        style="max-width: 100%; height: auto; object-fit: contain;">
                                @endif
                            </div>
                            <div class="col-md-8 align-content-start">
                                <p class="m-0"><span class="fw-bold">{{ t('app.user.username') }}:</span>
                                    {{ $data['user']->username }}
                                </p>
                                <p class="m-0"><span class="fw-bold">{{ t('app.user.fullName') }}:</span>
                                    {{ $data['user']->first_name ?? '' }} {{ $data['user']->middle_name ?? '' }}
                                    {{ $data['user']->last_name ?? '' }}</p>
                                <p class="m-0"><span class="fw-bold">{{ t('app.user.username') }}:</span>
                                    @switch($data['user']->account_status)
                                        @case('1')
                                            <span class="badge bg-success-subtle text-success">{{ t('app.status.active') }}</span>
                                        @break

                                        @default
                                            <span class="badge bg-danger-subtle text-danger">{{ t('app.status.inactive') }}</span>
                                    @endswitch
                                </p>
                                <p class="m-0"><span class="fw-bold">{{ t('auth.email') }}1:</span>
                                    {{ $data['user']->email1 }}
                                    @switch($data['user']->email1_verification)
                                        @case('verified')
                                            <span class="badge bg-success-subtle text-success d-inline-block text-wrap"
                                                style="white-space: normal; word-break: break-word; max-width: 100%;">
                                                {{ t('app.status.verified') }} →
                                                {{ \Carbon\Carbon::parse($data['user']->email1_verified_at)->format('l, d F, Y h:i:s A') }}
                                            </span>
                                        @break

                                        @default
                                            <span class="badge bg-danger-subtle text-danger">{{ t('app.status.unverified') }}
                                            </span>
                                    @endswitch
                                </p>
                                @if ($data['user']->email2)
                                    <p class="m-0"><span class="fw-bold">{{ t('auth.email') }}2:</span>
                                        {{ $data['user']->email2 }}
                                        @switch($data['user']->email2_verification)
                                            @case('verified')
                                                <span class="badge bg-success-subtle text-success d-inline-block text-wrap"
                                                    style="white-space: normal; word-break: break-word; max-width: 100%;">
                                                    {{ t('app.status.verified') }} →
                                                    {{ \Carbon\Carbon::parse($data['user']->email2_verified_at)->format('l, d F, Y h:i:s A') }}
                                                </span>
                                            @break

                                            @default
                                                <span class="badge bg-danger-subtle text-danger">{{ t('app.status.unverified') }}
                                                </span>
                                        @endswitch
                                    </p>
                                @endif
                                <p class="m-0"><span class="fw-bold">{{ t('auth.phone') }}:</span>
                                    {{ $data['user']->phone1 }}
                                    @if ($data['user']->phone2)
                                        /{{ $data['user']->phone2 }}
                                    @endif
                                </p>
                                <p class="m-0"><span class="fw-bold">{{ t('app.user.gender') }}:</span>
                                    @switch($data['user']->gender)
                                        @case('male')
                                            {{ t('app.user.male') }}
                                        @break

                                        @case('female')
                                            {{ t('app.user.female') }}
                                        @break

                                        @default
                                    @endswitch

                                </p>
                                <p class="m-0"><span class="fw-bold">{{ t('app.user.dateOfBirth') }}:</span>
                                    @if ($data['user']->date_of_birth)
                                        {{ \Carbon\Carbon::parse($data['user']->date_of_birth)->format('l, d F, Y') }}
                                    @endif
                                </p>
                                <p class="m-0"><span class="fw-bold">{{ t('app.user.nationality') }}:</span>
                                    {{ $data['user']->nationality }}
                                </p>
                                <p class="m-0"><span class="fw-bold">{{ t('app.language.language') }}:</span>
                                    {{ $data['user']->language->language }}
                                </p>
                                <p class="m-0"><span class="fw-bold">{{ t('app.user.requireMfa') }}:</span>
                                    @switch($data['user']->require_login_mfa)
                                        @case(true)
                                            <span class="badge bg-success-subtle text-success">{{ t('app.status.true') }}
                                            </span>
                                        @break

                                        @default
                                            <span class="badge bg-danger-subtle text-danger">{{ t('app.status.false') }}
                                            </span>
                                    @endswitch
                                </p>
                                {{-- addresses --}}
                                <div class="">
                                    <p class="m-0"><span
                                            class="fw-bold">{{ t('app.settings.address.addresses') }}:</span>
                                        @if (!$data['user']->addresses->count() > 0)
                                            <span class="text-danger">
                                                {{ t('app.settings.address.noAddressFound') }}
                                            </span>

                                            <span onclick="addNewAddressFormModal('swal-size-md')"
                                                class="bagde bg-primary text-white">
                                                <i class="fas fa-plus p-1"></i></span>
                                            </span>
                                        @else
                                            <div class="col-md-12">
                                                <div class="row">
                                                    @foreach ($data['user']->addresses as $address)
                                                        <div class="col-md-6 mb-3">
                                                            <div class="card shadow-sm h-100">
                                                                <div class="card-body">
                                                                    {{ $address->more_details }},
                                                                    {{ $address->theCity->name }},
                                                                    {{ $address->theState->name }},
                                                                    {{ $address->theCountry->name }}
                                                                    @if ($address->is_primary)
                                                                        <span
                                                                            class="badge bg-primary-subtle text-primary">{{ t('app.settings.address.primaryAddress') }}</span>
                                                                    @endif
                                                                </div>

                                                                <div
                                                                    class="card-footer bg-white border-0 d-flex justify-content-between">
                                                                    <a class="btn btn-sm btn-outline-primary cursor-pointer"
                                                                        onclick="editAddressFormModal{{ $address->id }}()"
                                                                        id="edit-{{ $address->id }}">
                                                                        {{ t('app.buttons.edit') }}
                                                                    </a>

                                                                    <form
                                                                        action="{{ route('customerAddressDelete', $address->id) }}"
                                                                        method="POST" class="delete-address-form">
                                                                        @csrf
                                                                        @method('DELETE')

                                                                        <button type="submit"
                                                                            class="btn btn-sm btn-outline-danger">
                                                                            {{ t('app.buttons.delete') }}
                                                                        </button>
                                                                    </form>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <button type="button" onclick="addNewAddressFormModal('swal-size-md')"
                                                class="btn btn-primary d-inline-flex align-items-center gap-2 px-3 py-2 shadow-sm">
                                                <i class="fas fa-plus"></i>
                                                {{ t('app.settings.address.newAddress') }}
                                            </button>
                                        @endif
                                    </p>
                                </div>

                            </div>
                            <div class="d-flex justify-content-end mb-4">
                                <a href="{{ route('customerEditProfile') }}"
                                    class="btn btn-primary d-inline-flex align-items-center gap-2 px-3 py-2 shadow-sm text-white"><i
                                        class="fas fa-edit me-2"></i>{{ t('app.user.updateProfile') }} </a>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </section>
    </div>


    {{-- delete address --}}
    <script>
        $(document).on('submit', '.delete-address-form', function(e) {
            e.preventDefault(); // stop normal form submission

            let form = this;

            Swal.fire({
                title: "{{ t('app.confirm') }}",
                text: "{{ t('app.confirm') }}",
                icon: "{{ t('app.deleteConfirmText') }}",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // submit if confirmed
                }
            });
        });
    </script>

    {{-- modals start --}}
    @include('users.customer.profile.include.addNewAddress')

    @if (!$data['user']->addresses->isEmpty())
        @foreach ($data['user']->addresses as $address)
            @include('users.customer.profile.include.editAddressModal')
            {{-- ############ --}}
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    @if (session('editAddressId'))
                        const el = document.querySelector('[data-id="{{ session('editAddressId') }}"]');
                        if (el) editAddressFormModal(el);
                    @endif
                });
            </script>
            {{-- ############ --}}
        @endforeach
    @endif

    {{-- modals end --}}

@endsection
