@extends('layouts.authLayout')
@section('title', t('auth.authenticateLogin'))
@section('content')
    <div class="outer-container">
        @include('utilities.pages.auth.include.slider')
        <div class="login-form">
            <div class="form-container">
                @include('utilities.pages.auth.include.headerLogo')


                <h2 class="m-2 text-center auth_header">{{ t('auth.authenticateLogin') }}</h2>
                <p class="m-2 text-center">{{ t('auth.enterOtp') }}</p>
                <form class="" action="{{ route('verifyLoginOtpSubmit') }}" method="POST">
                    @csrf
                    <input type="number" name="login_otp" placeholder="123456"
                        min="100000" max="999999" required />
                    <p style="color: red">{{ $errors->first('login_otp') }}</p>

                    <button type="submit">{{ t('app.buttons.submit') }}</button>
                </form>

                <div class="form-options my-4">
                    <div
                        class="d-flex flex-column flex-md-row justify-content-center justify-content-md-between gap-2 my-4">
                        <a href="{{ route('resendLoginOtp') }}"

                            class="btn btn-primary text-white w-100 w-md-auto">
                            {{ t('auth.resendCode') }}
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
