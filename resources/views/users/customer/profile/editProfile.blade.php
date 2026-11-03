@extends('layouts.userLayout')
@section('title', t('app.buttons.edit') . ' :: ' . t('app.user.myProfile'))
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

                <div class="my-page-header">{{ t('app.buttons.edit') }} :: {{ t('app.user.myProfile') }}</div>

                <div class="my-4">
                    <div class="card shadow-sm border-0">
                        <form method="POST" action="{{ route('userUpdateProfileSubmit') }}"
                            enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <div class="card-body p-0 row col-md-12">
                                <div class="col-md-4">
                                    <div class="col-md-12 justify-content-center text-center p-2" style="overflow: hidden;">
                                        <img id="passport-preview"
                                            src="{{ $data['user'] && $data['user']->photo ? asset('assets/uploads/users/photos/' . $data['user']->photo) : asset('system/images/default_profile_pic.jpg') }}"
                                            alt="{{ $data['user'] ? $data['user']->username : 'Default Picture' }}"
                                            style="max-width: 100%; height: auto; object-fit: contain;">
                                    </div>

                                    <div class="col-md-12">
                                        <input type="file" id="passport-upload" accept=".jpg, .jpeg, .png, .webp"
                                            name="image" onchange="previewUserPhotos(this)">
                                        <p class="text-red">{{ $errors->first('image') }}</p>
                                    </div>
                                </div>

                                <div class="col-md-8 align-content-start">
                                    <div class="row col-md-12">
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">{{ t('app.user.firstName') }}
                                                <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="first_name"
                                                value="{{ $data['user']->first_name }}" required>
                                            <p class="text-red">{{ $errors->first('first_name') }}</p>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">{{ t('app.user.middleName') }}
                                                <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="middle_name"
                                                value="{{ $data['user']->middle_name }}" required>
                                            <p class="text-red">{{ $errors->first('middle_name') }}</p>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">{{ t('app.user.lastName') }}
                                                <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="last_name"
                                                value="{{ $data['user']->last_name }}" required>
                                            <p class="text-red">{{ $errors->first('last_name') }}</p>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ t('auth.email') }} 1
                                                <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email1"
                                                value="{{ $data['user']->email1 }}" required>
                                            <p class="text-red">{{ $errors->first('email1') }}</p>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ t('auth.email') }} 2</label>
                                            <input type="email" class="form-control" name="email2"
                                                value="{{ $data['user']->email2 }}">
                                            <p class="text-red">{{ $errors->first('email2') }}</p>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ t('auth.phone') }} 1
                                                <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="phone1"
                                                value="{{ $data['user']->phone1 }}" required>
                                            <p class="text-red">{{ $errors->first('phone1') }}</p>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ t('auth.phone') }} 2</label>
                                            <input type="text" class="form-control" name="phone2"
                                                value="{{ $data['user']->phone2 }}">
                                            <p class="text-red">{{ $errors->first('phone2') }}</p>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ t('app.user.gender') ?? 'Gender' }}</label>

                                            <select name="gender" class="form-select text-center">
                                                <option value="" disabled
                                                    {{ empty($data['user']->gender) ? 'selected' : '' }}>
                                                    - {{ t('app.select') ?? 'Select' }} -
                                                </option>

                                                <option value="male"
                                                    {{ isset($data['user']->gender) && strtolower($data['user']->gender) === 'male' ? 'selected' : '' }}>
                                                    {{ t('app.user.male') ?? 'Male' }}
                                                </option>

                                                <option value="female"
                                                    {{ isset($data['user']->gender) && strtolower($data['user']->gender) === 'female' ? 'selected' : '' }}>
                                                    {{ t('app.user.female') ?? 'Female' }}
                                                </option>
                                            </select>
                                            <p class="text-red">{{ $errors->first('gender') }}</p>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ t('app.user.dateOfBirth') }}</label>
                                            <input type="date" class="form-control" name="date_of_birth"
                                                value="{{ $data['user']->date_of_birth }}">
                                            <p class="text-red">{{ $errors->first('date_of_birth') }}</p>
                                        </div>
                                        <div class="mb-3 col-md-5">
                                            <label class="form-label">{{ t('app.user.nationality') }}</label>
                                            <input type="text" class="form-control" name="nationality"
                                                value="{{ $data['user']->nationality }}">
                                            <p class="text-red">{{ $errors->first('nationality') }}</p>
                                        </div>
                                        <div class="mb-3 col-md-5">
                                            <label class="form-label">{{ t('app.language.language') }}</label>

                                            <select name="preferred_language" class="form-select text-center">
                                                <option value="" disabled
                                                    {{ empty($data['user']->preferred_language) ? 'selected' : '' }}>
                                                    - {{ t('app.select') ?? 'Select' }} -
                                                </option>

                                                @foreach (activeLanguages() as $language)
                                                    <option value="{{ $language->language_code }}"
                                                        {{ (string) old('preferred_language', $data['user']->preferred_language ?? '') === (string) $language->language_code ? 'selected' : '' }}>
                                                        {{ $language->language }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <p class="text-red">{{ $errors->first('preferred_language') }}</p>
                                        </div>
                                        <div class="mb-3 col-md-2">
                                            <label class="form-label">{{ t('app.user.requireMfa') }}</label>

                                            <select name="require_login_mfa" class="form-select text-center">
                                                <option value="" disabled
                                                    {{ is_null($data['user']->require_login_mfa) ? 'selected' : '' }}>
                                                    - {{ t('app.select') ?? 'Select' }} -
                                                </option>

                                                <option value="1"
                                                    {{ $data['user']->require_login_mfa == 1 ? 'selected' : '' }}>
                                                    {{ t('app.status.active') }}
                                                </option>

                                                <option value="0"
                                                    {{ $data['user']->require_login_mfa == 0 ? 'selected' : '' }}>
                                                    {{ t('app.status.inactive') }}
                                                </option>

                                            </select>
                                            <p class="text-red">{{ $errors->first('require_login_mfa') }}</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="text-center p-3">
                                <button type="submit" class="btn btn-primary px-4 w-100">
                                    {{ t('app.buttons.update') }}
                                </button>
                            </div>
                        </form>



                    </div>
                </div>

            </div>
    </div>

    </section>
    </div>

    {{-- preview passport --}}
    <script>
        function previewUserPhotos(input) {
            const preview = document.getElementById('passport-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result; // update image preview
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                // fallback to default if input cleared
                preview.src = "{{ asset('system/images/default_profile_pic.jpg') }}";
            }
        }
    </script>
@endsection
