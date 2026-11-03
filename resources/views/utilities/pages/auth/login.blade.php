@extends('layouts.authLayout')
@section('title', t('auth.login'))
@section('content')
    <div class="outer-container">
        @include('utilities.pages.auth.include.slider')
        <div class="login-form">
            <div class="form-container">
                @include('utilities.pages.auth.include.headerLogo')


                <h2 class="m-2 text-center auth_header">{{ t('auth.welcomeBack') }}</h2>
                <p class="m-2 text-center">{{ t('auth.signInMessage') }}</p>
                <form class="" action="{{ route('submitLoginRequest') }}" method="POST">
                    @csrf
                    <input type="text" placeholder="{{ t('auth.usernameEmail') }}" name="login_id" required />
                    <p style="color: red">{{ $errors->first('login_id') }}</p>

                    <div class="password-container">
                        <input type="password" id="password" placeholder="********" name="password" autocomplete="off"
                            value="{{ old('password') }}" required />
                        <i class="fa fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                    </div>
                    <p style="color: red">{{ $errors->first('password') }}</p>

                    <button type="submit">{{ t('auth.login') }}</button>
                </form>
                @include('utilities.pages.auth.include.socialiteAuth')

                <div class="form-options my-4">
                    <p class="">{{ t('auth.haveNoAccount') }} <a
                            href="{{ route('registrationPage') }}">{{ t('auth.register') }}</a></p>
                    <p class=""><a
                            href="{{ route('forgotPassword') }}">{{ t('auth.forgotPassword') }}</a>
                    </p>
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
    <script>
        // Get the elements
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        // Toggle password visibility
        togglePassword.addEventListener('click', function() {
            // Toggle the type between "password" and "text"
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;

            // Toggle the eye icon (between eye and eye-slash)
            if (passwordField.type === 'password') {
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            } else {
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            }
        });
    </script>



@endsection
