@extends('layouts.authLayout')
@section('title', t('auth.maintenanceMode'))
@section('content')
    <div class="outer-container">
        @include('utilities.pages.auth.include.slider')
        <div class="login-form">
            <div class="form-container">
                @include('utilities.pages.auth.include.headerLogo')


                <h2 class="m-2 text-center auth_header">{{ t('auth.maintenanceMode') }} <i class="fas fa-tools"></i>
                </h2>
                <h3 class="m-2 text-center">{{ t('auth.maintenanceModeMessage') }}</h3>

                <div class="form-options my-4">
                    <div class="d-flex flex-column flex-md-row justify-content-center justify-content-md-between gap-2 my-4">
                        <a href="{{ route('homepage') }}" class="btn btn-primary text-white w-100 w-md-auto">
                            {{ t('app.refresh') }}
                        </a>

                        <a href="{{ route('logoutRequest') }}" class="btn btn-danger text-white w-100 w-md-auto">
                            {{ t('auth.logout') }}
                        </a>
                    </div>
                    <div class="d-flex justify-content-end">
                        <div class="text-center">
                            @include('utilities.pages.others.changeLanguage')
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection
