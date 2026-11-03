@extends('layouts.authLayout')
@section('title', t('auth.forgotPassword'))
@section('content')
    <div class="outer-container">
        @include('utilities.pages.auth.include.slider')
        <div class="login-form">
            <div class="form-container">
                @include('utilities.pages.auth.include.headerLogo')


                <h2 class="m-2 text-center auth_header">{{ t('auth.forgotPassword') }}</h2>
                {{-- <p class="m-2 text-center">{{ t('auth.signInMessage') }}</p> --}}
                <form class="" action="{{ route('requestPasswordResetLink') }}" method="POST">
                    @csrf
                    <input type="email" placeholder="{{ t('auth.email') }}" name="email" required />
                    <p style="color: red">{{ $errors->first('email') }}</p>

                    <button type="submit">{{ t('auth.sendPassResetLink') }}</button>
                </form>
                @include('utilities.pages.auth.include.socialiteAuth')

                <div class="form-options my-4">
                    <p class="">{{ t('auth.haveAnAccount') }} <a
                            href="{{ route('loginPage') }}">{{ t('auth.login') }}</a></p>
                    <p class=""><a
                            href="
                        {{ route('homepage') }}
                         ">{{ t('app.homePage') }}</a>
                    </p>

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
