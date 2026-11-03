@extends('layouts.authLayout')
@section('title', t('auth.resetPassword'))
@section('content')
    <div class="outer-container">
        @include('utilities.pages.auth.include.slider')
        <div class="login-form">
            <div class="form-container">
                @include('utilities.pages.auth.include.headerLogo')


                <h2 class="m-2 text-center auth_header">{{ t('auth.resetPassword') }}</h2>
                <p class="m-2 text-center">{{ t('auth.enterNewPassword') }}</p>
                <form class="" action="{{ route('submitPasswordResetRequest', ['token' => $user->remember_token]) }}"
                    method="POST">
                    @csrf
                    <div class="password-container">
                        <input type="password" id="password" placeholder="Password" name="password" autocomplete="off"
                            value="{{ old('password') }}" required />
                        <i class="fa fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                    </div>
                    <p style="color: red">{{ $errors->first('password') }}</p>

                    <div class="password-container">
                        <input type="password" id="password2" placeholder="Confirm Password" name="confirm_password"
                            autocomplete="off" value="{{ old('confirm_password') }}" required />
                        <i class="fa fa-eye" id="togglePassword2" style="cursor: pointer;"></i>
                    </div>
                    {{-- password rules --}}
                    <div id="password-rules" class="mt-3">
                        <ul>
                            <div class="col-md-12">
                                <span
                                    class="text-bold text-danger text-sm">{{ t('auth.passwordRules.passwordRules') }}:</span>
                                <div class="row m-2">
                                    <div class="col-md-12">
                                        <li id="length-rule" class="rule">{{ t('auth.passwordRules.minChar') }}</li>
                                        <li id="lowercase-rule" class="rule">{{ t('auth.passwordRules.lowerCase') }}</li>
                                        <li id="uppercase-rule" class="rule">{{ t('auth.passwordRules.upperCase') }}</li>
                                        <li id="special-char-rule" class="rule">{{ t('auth.passwordRules.symbolRule') }}
                                        </li>
                                        <li id="number-rule" class="rule">{{ t('auth.passwordRules.numberRule') }}</li>
                                        <li id="match-rule" class="rule">
                                            {{ t('auth.passwordRules.samePass') }}</li>
                                    </div>
                                </div>
                            </div>
                        </ul>
                    </div>

                    <button type="submit">{{ t('auth.resetPassword') }}</button>
                </form>
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


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('password');
            const togglePassword2 = document.getElementById('togglePassword2');
            const passwordField2 = document.getElementById('password2');

            togglePassword.addEventListener('click', function() {
                const type = passwordField.type === 'password' ? 'text' : 'password';
                passwordField.type = type;
                this.classList.toggle('fa-eye-slash');
                this.classList.toggle('fa-eye');
            });

            togglePassword2.addEventListener('click', function() {
                const type = passwordField2.type === 'password' ? 'text' : 'password';
                passwordField2.type = type;
                this.classList.toggle('fa-eye-slash');
                this.classList.toggle('fa-eye');
            });

            passwordField.addEventListener('input', function() {
                validatePassword();
                matchPasswords();
            });

            passwordField2.addEventListener('input', function() {
                matchPasswords();
            });

            function validatePassword() {
                const passwordValue = passwordField.value;

                const lengthRule = document.getElementById('length-rule');
                lengthRule.style.color = passwordValue.length >= 8 ? '#00BD73' : '#E02626';

                const lowercaseRule = document.getElementById('lowercase-rule');
                lowercaseRule.style.color = /[a-z]/.test(passwordValue) ? '#00BD73' : '#E02626';

                const uppercaseRule = document.getElementById('uppercase-rule');
                uppercaseRule.style.color = /[A-Z]/.test(passwordValue) ? '#00BD73' : '#E02626';

                const specialCharRule = document.getElementById('special-char-rule');
                specialCharRule.style.color = /[^A-Za-z0-9]/.test(passwordValue) ? '#00BD73' : '#E02626';

                const numberRule = document.getElementById('number-rule');
                numberRule.style.color = /\d/.test(passwordValue) ? '#00BD73' : '#E02626';
            }

            function matchPasswords() {
                const passwordValue = passwordField.value;
                const confirmPasswordValue = passwordField2.value;
                const matchRule = document.getElementById('match-rule');

                matchRule.style.color = passwordValue === confirmPasswordValue ? '#00BD73' : '#E02626';
            }
        });
    </script>



    <style>
        #password-rules {
            padding: 0;
            margin: 0;
            list-style-type: none;
        }

        #password-rules ul {
            padding: 0;
            margin: 0;
        }

        #password-rules li {
            font-size: 12px;
            margin-bottom: 2px;
        }

        .rule {
            font-size: 14px;
            color: #E02626;
        }

        .password-container {
            margin-bottom: 15px;
        }

        #password-error,
        #confirm-password-error {
            font-size: 12px;
            color: #E02626;
            margin-top: 5px;
        }
    </style>
@endsection
