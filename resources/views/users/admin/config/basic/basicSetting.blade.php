@extends('layouts.adminLayout')
@section('title', t('app.settings.settings') . '::' . t('app.settings.basicSettings'))
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10"><a href="#">{{ t('app.settings.basicSettings') }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><a href="#">{{ t('app.settings.settings') }}</a></li>
                    <li class="breadcrumb-item">{{ t('app.settings.basicSettings') }}</li>
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
                </div>
                <div class="d-md-none d-flex align-items-center">
                    <a href="javascript:void(0)" class="page-header-right-open-toggle">
                        <i class="feather-align-right fs-20"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- [ page-header ] end -->
        {{-- <div class="bg-white py-3 border-bottom rounded-0 p-md-0 mb-0">
            <div class="d-md-none d-flex">
                <a href="javascript:void(0)" class="page-content-left-open-toggle">
                    <i class="feather-align-left fs-20"></i>
                </a>
            </div>
        </div> --}}
        <!-- [ Main Content ] start -->
        <div class="main-content">
            <div class="tab-content">
                <div class="row col-md-12">
                    {{-- app Info --}}
                    <div class="col-md-6">
                        <div class="card stretch-full">
                            <div class="card-body lead-status bg-soft-primary">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 class="w-100 text-center text-primary text-uppercase">
                                        {{ t('app.settings.appInfo') }}</h3>
                                </div>
                            </div>


                            <div class="card-body general-info">
                                <div class="card-body">
                                    <form id="swalForm" method="POST" action="{{ route('systemSettingsUpdateAppInfo') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="mb-4 col-md-12">
                                            <label class="form-label">{{ t('app.settings.appName') }}</label>
                                            <input type="text" class="form-control text-center" name="APP_NAME"
                                                value="{{ $data['appName'] }}">
                                            <p class="text-danger">{{ $errors->first('APP_NAME') }}
                                            </p>
                                        </div>
                                        <div class="mb-4 col-md-12">
                                            <label class="form-label">{{ t('app.settings.appUrl') }}</label>
                                            <input type="text" class="form-control text-center" name="APP_URL"
                                                value="{{ $data['appUrl'] }}">
                                            <p class="text-danger">{{ $errors->first('APP_URL') }}
                                            </p>
                                        </div>
                                        <div class="mb-4 col-md-12">
                                            <label class="form-label">{{ t('app.settings.timeZone') }}</label>
                                            <select class="form-select form-control text-center" name="timezone" required>
                                                <option disabled selected>-{{ t('app.select') }}-</option>
                                                <option value="Etc/GMT+12"
                                                    {{ $data['timezone'] == 'Etc/GMT+12' ? 'selected' : '' }}>
                                                    GMT-12:00
                                                </option>
                                                <option value="Etc/GMT+11"
                                                    {{ $data['timezone'] == 'Etc/GMT+11' ? 'selected' : '' }}>
                                                    GMT-11:00
                                                </option>
                                                <option value="Etc/GMT+10"
                                                    {{ $data['timezone'] == 'Etc/GMT+10' ? 'selected' : '' }}>
                                                    GMT-10:00
                                                </option>
                                                <option value="Etc/GMT+9"
                                                    {{ $data['timezone'] == 'Etc/GMT+9' ? 'selected' : '' }}>GMT-9:00
                                                </option>
                                                <option value="Etc/GMT+8"
                                                    {{ $data['timezone'] == 'Etc/GMT+8' ? 'selected' : '' }}>GMT-8:00
                                                </option>
                                                <option value="Etc/GMT+7"
                                                    {{ $data['timezone'] == 'Etc/GMT+7' ? 'selected' : '' }}>GMT-7:00
                                                </option>
                                                <option value="Etc/GMT+6"
                                                    {{ $data['timezone'] == 'Etc/GMT+6' ? 'selected' : '' }}>GMT-6:00
                                                </option>
                                                <option value="Etc/GMT+5"
                                                    {{ $data['timezone'] == 'Etc/GMT+5' ? 'selected' : '' }}>GMT-5:00
                                                </option>
                                                <option value="Etc/GMT+4"
                                                    {{ $data['timezone'] == 'Etc/GMT+4' ? 'selected' : '' }}>GMT-4:00
                                                </option>
                                                <option value="Etc/GMT+3"
                                                    {{ $data['timezone'] == 'Etc/GMT+3' ? 'selected' : '' }}>GMT-3:00
                                                </option>
                                                <option value="Etc/GMT+2"
                                                    {{ $data['timezone'] == 'Etc/GMT+2' ? 'selected' : '' }}>GMT-2:00
                                                </option>
                                                <option value="Etc/GMT+1"
                                                    {{ $data['timezone'] == 'Etc/GMT+1' ? 'selected' : '' }}>GMT-1:00
                                                </option>
                                                <option value="Etc/GMT"
                                                    {{ $data['timezone'] == 'Etc/GMT' ? 'selected' : '' }}>GMT</option>
                                                <option value="Etc/GMT-1"
                                                    {{ $data['timezone'] == 'Etc/GMT-1' ? 'selected' : '' }}>GMT+1:00
                                                </option>
                                                <option value="Etc/GMT-2"
                                                    {{ $data['timezone'] == 'Etc/GMT-2' ? 'selected' : '' }}>GMT+2:00
                                                </option>
                                                <option value="Etc/GMT-3"
                                                    {{ $data['timezone'] == 'Etc/GMT-3' ? 'selected' : '' }}>GMT+3:00
                                                </option>
                                                <option value="Etc/GMT-4"
                                                    {{ $data['timezone'] == 'Etc/GMT-4' ? 'selected' : '' }}>GMT+4:00
                                                </option>
                                                <option value="Etc/GMT-5"
                                                    {{ $data['timezone'] == 'Etc/GMT-5' ? 'selected' : '' }}>GMT+5:00
                                                </option>
                                                <option value="Etc/GMT-6"
                                                    {{ $data['timezone'] == 'Etc/GMT-6' ? 'selected' : '' }}>GMT+6:00
                                                </option>
                                                <option value="Etc/GMT-7"
                                                    {{ $data['timezone'] == 'Etc/GMT-7' ? 'selected' : '' }}>GMT+7:00
                                                </option>
                                                <option value="Etc/GMT-8"
                                                    {{ $data['timezone'] == 'Etc/GMT-8' ? 'selected' : '' }}>GMT+8:00
                                                </option>
                                                <option value="Etc/GMT-9"
                                                    {{ $data['timezone'] == 'Etc/GMT-9' ? 'selected' : '' }}>GMT+9:00
                                                </option>
                                                <option value="Etc/GMT-10"
                                                    {{ $data['timezone'] == 'Etc/GMT-10' ? 'selected' : '' }}>
                                                    GMT+10:00
                                                </option>
                                                <option value="Etc/GMT-11"
                                                    {{ $data['timezone'] == 'Etc/GMT-11' ? 'selected' : '' }}>
                                                    GMT+11:00
                                                </option>
                                                <option value="Etc/GMT-12"
                                                    {{ $data['timezone'] == 'Etc/GMT-12' ? 'selected' : '' }}>
                                                    GMT+12:00
                                                </option>
                                            </select>
                                            <p class="text-danger">{{ $errors->first('timezone') }}
                                            </p>
                                        </div>

                                        <button type="submit"
                                            class="btn btn-primary w-100 mt-2">{{ t('app.buttons.update') }}</button>


                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- app logo --}}
                    <div class="col-md-6">
                        <div class="card stretch-full">
                            <div class="card-body lead-status bg-soft-success">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 class="w-100 text-center text-success text-uppercase">
                                        {{ t('app.settings.appLogo') }}</h3>
                                </div>
                            </div>


                            <div class="card-body general-info">
                                <div class="card-body">
                                    <form id="swalForm" method="POST" action="{{ route('systemSettingsUpdateAppLogo') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="row col-md-12">
                                            {{-- logo -> full --}}
                                            <div class="mb-4 col-md-6">
                                                <div class="form-group upload-container row col-md-12">
                                                    <div class="col-md-12 d-flex justify-content-center align-items-center">
                                                        <label for="image-upload-1" class="preview-frame">
                                                            <img id="image-preview-1"
                                                                src="{{ asset('system/images/logo.png') }}" alt="image"
                                                                width="200px" height="200px" class="img-fluid">
                                                        </label>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label for="logo_1">{{ t('app.settings.logo') }}</label>
                                                        <input type="file" id="image-upload-1"
                                                            accept=".jpg, .jpeg, .png" onchange="previewImage1()"
                                                            name="image_1">
                                                        <p style="color: red">
                                                            {{ $errors->first('image_1') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- logo shor --}}
                                            <div class="mb-4 col-md-6">
                                                <div class="form-group upload-container row col-md-12">
                                                    <div
                                                        class="col-md-12 d-flex justify-content-center align-items-center">
                                                        <label for="image-upload-2" class="preview-frame">
                                                            <img id="image-preview-2"
                                                                src="{{ asset('system/images/logo_full.png') }}"
                                                                alt="image" width="200px" height="200px"
                                                                class="img-fluid">
                                                        </label>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label for="image_2">{{ t('app.settings.logo') }} (Full)</label>
                                                        <input type="file" id="image-upload-2"
                                                            accept=".jpg, .jpeg, .png" onchange="previewImage2()"
                                                            name="image_2">
                                                        <p style="color: red">
                                                            {{ $errors->first('image_2') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit"
                                            class="btn btn-success w-100 mt-2">{{ t('app.buttons.update') }}</button>


                                    </form>

                                </div>
                            </div>
                        </div>
                        {{-- ############################################################# --}}
                        <script>
                            function previewImage1() {
                                const preview = document.getElementById('image-preview-1');
                                const fileInput = document.getElementById('image-upload-1');
                                const file = fileInput.files[0];
                                const reader = new FileReader();

                                reader.onload = function() {
                                    preview.src = reader.result;
                                }

                                if (file) {
                                    reader.readAsDataURL(file);
                                } else {
                                    preview.src = 'placeholder.jpg';
                                }
                            }

                            function previewImage2() {
                                const preview = document.getElementById('image-preview-2');
                                const fileInput = document.getElementById('image-upload-2');
                                const file = fileInput.files[0];
                                const reader = new FileReader();

                                reader.onload = function() {
                                    preview.src = reader.result;
                                }

                                if (file) {
                                    reader.readAsDataURL(file);
                                } else {
                                    preview.src = 'placeholder.jpg';
                                }
                            }

                            function previewImage3() {
                                const preview = document.getElementById('image-preview-3');
                                const fileInput = document.getElementById('image-upload-3');
                                const file = fileInput.files[0];
                                const reader = new FileReader();

                                reader.onload = function() {
                                    preview.src = reader.result;
                                }

                                if (file) {
                                    reader.readAsDataURL(file);
                                } else {
                                    preview.src = 'placeholder.jpg';
                                }
                            }
                        </script>
                        {{-- ############################################################# --}}
                    </div>

                    {{-- email config --}}
                    <div class="col-md-6">
                        <div class="card stretch-full">
                            <div class="card-body lead-status bg-soft-warning">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 class="w-100 text-center text-warning text-uppercase">
                                        {{ t('app.settings.emailConfig') }}</h3>
                                </div>
                            </div>


                            <div class="card-body general-info">
                                <div class="card-body">
                                    <form id="swalForm" method="POST"
                                        action="{{ route('systemSettingsUpdateEmailConfig') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="mb-4 col-md-6">
                                                <label class="form-label">{{ t('app.settings.mailer') }}</label>
                                                <select class="form-control text-center" name="MAIL_MAILER"
                                                    id="MAIL_MAILER" required>
                                                    <option value="smtp"
                                                        {{ old('MAIL_MAILER', $data['MAIL_MAILER']) == 'smtp' ? 'selected' : '' }}>
                                                        SMTP</option>
                                                    <option value="sendmail"
                                                        {{ old('MAIL_MAILER', $data['MAIL_MAILER']) == 'sendmail' ? 'selected' : '' }}>
                                                        Sendmail</option>
                                                    <option value="mailgun"
                                                        {{ old('MAIL_MAILER', $data['MAIL_MAILER']) == 'mailgun' ? 'selected' : '' }}>
                                                        Mailgun</option>
                                                    <option value="ses"
                                                        {{ old('MAIL_MAILER', $data['MAIL_MAILER']) == 'ses' ? 'selected' : '' }}>
                                                        SES</option>
                                                </select>
                                                <p class="text-danger">{{ $errors->first('MAIL_MAILER') }}
                                                </p>
                                            </div>
                                            <div class="mb-4 col-md-6">
                                                <label class="form-label">{{ t('app.settings.host') }}</label>
                                                <input type="text" class="form-control text-center" name="MAIL_HOST"
                                                    value="{{ $data['MAIL_HOST'] }}">
                                                <p class="text-danger">{{ $errors->first('MAIL_HOST') }}
                                                </p>
                                            </div>
                                            <div class="mb-4 col-md-6">
                                                <label class="form-label">{{ t('app.settings.port') }}</label>
                                                <input type="text" class="form-control text-center" name="MAIL_PORT"
                                                    value="{{ $data['MAIL_PORT'] }}">
                                                <p class="text-danger">{{ $errors->first('MAIL_PORT') }}
                                                </p>
                                            </div>
                                            <div class="mb-4 col-md-6">
                                                <label class="form-label">{{ t('app.settings.username') }}</label>
                                                <input type="text" class="form-control text-center"
                                                    name="MAIL_USERNAME" value="{{ $data['MAIL_USERNAME'] }}">
                                                <p class="text-danger">{{ $errors->first('MAIL_USERNAME') }}
                                                </p>
                                            </div>
                                            <div class="mb-4 col-md-6">
                                                <label class="form-label">{{ t('auth.password') }}</label>
                                                <input type="text" class="form-control text-center"
                                                    name="MAIL_PASSWORD" value="{{ $data['MAIL_PASSWORD'] }}">
                                                <p class="text-danger">{{ $errors->first('MAIL_PASSWORD') }}
                                                </p>
                                            </div>
                                            <div class="mb-4 col-md-6">
                                                <label class="form-label">{{ t('app.settings.mailEncryption') }}</label>
                                                <input type="text" class="form-control text-center"
                                                    name="MAIL_ENCRYPTION" value="{{ $data['MAIL_ENCRYPTION'] }}">
                                                <p class="text-danger">{{ $errors->first('MAIL_ENCRYPTION') }}
                                                </p>
                                            </div>
                                            <div class="mb-4 col-md-12">
                                                <label class="form-label">{{ t('app.settings.senderAddress') }}</label>
                                                <input type="text" class="form-control text-center"
                                                    name="MAIL_FROM_ADDRESS" value="{{ $data['MAIL_FROM_ADDRESS'] }}">
                                                <p class="text-danger">{{ $errors->first('MAIL_FROM_ADDRESS') }}
                                                </p>
                                            </div>
                                        </div>

                                        <button type="submit"
                                            class="btn btn-warning w-100 mt-2">{{ t('app.buttons.update') }}</button>


                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- others --}}
                    <div class="col-md-6">
                        <div class="card stretch-full">
                            <div class="card-body lead-status bg-soft-danger">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 class="w-100 text-center text-danger text-uppercase">
                                        {{ t('app.others') }}</h3>
                                </div>
                            </div>


                            <div class="card-body general-info">
                                <div class="card-body row col-md-12">
                                    <div class="col-md-6 border p-5">
                                        <form id="swalForm" method="POST"
                                            action="{{ route('systemSettingsUpdateMaintenanceStatus') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="d-flex align-items-center gap-2">
                                                <h4 class="mb-0">{{ t('auth.maintenanceMode') }}:</h4>

                                                @php
                                                    $isOn = $data['maintenanceModeStatus'];
                                                @endphp

                                                <span
                                                    class="badge {{ $isOn ? 'bg-danger' : 'bg-success' }} text-white fs-5 px-4 py-2 rounded-pill">
                                                    {{ $isOn ? t('app.status.enabled') : t('app.status.disabled') }}
                                                </span>


                                            </div>

                                            <div class="mb-4 mt-4 col-md-12">
                                                <label class="form-label">{{ t('app.status.changeStatus') }}</label>
                                                <select class="form-select text-center" name="maintenance_mode" required>

                                                    <option disabled
                                                        {{ is_null($data['maintenanceModeStatus']) ? 'selected' : '' }}>
                                                        - {{ t('app.select') }} -
                                                    </option>

                                                    <option value="1"
                                                        {{ $data['maintenanceModeStatus'] == 1 ? 'selected' : '' }}>
                                                        {{ t('app.status.enabled') }}
                                                    </option>

                                                    <option value="0"
                                                        {{ $data['maintenanceModeStatus'] == 0 ? 'selected' : '' }}>
                                                        {{ t('app.status.disabled') }}
                                                    </option>

                                                </select>

                                                <p class="text-danger">{{ $errors->first('maintenance_mode') }}
                                                </p>
                                            </div>

                                            <button type="submit"
                                                class="btn btn-danger w-100 mt-2">{{ t('app.buttons.update') }}</button>


                                        </form>
                                    </div>
                                    <div class="col-md-6 border p-5">
                                        <form id="swalForm" method="POST"
                                            action="{{ route('systemSettingsUpdatePriceDisplayStatus') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="d-flex align-items-center gap-2">
                                                <h4 class="mb-0">{{ t('app.product.displayProductPrice') }}:</h4>

                                                @php
                                                    $isOn = $data['displayPriceStatus'];
                                                @endphp

                                                <span
                                                    class="badge {{ $isOn ? 'bg-warning' : 'bg-primary' }} text-white fs-5 px-4 py-2 rounded-pill">
                                                    {{ $isOn ? t('app.status.enabled') : t('app.status.disabled') }}
                                                </span>


                                            </div>

                                            <div class="mb-4 mt-4 col-md-12">
                                                <label class="form-label">{{ t('app.status.changeStatus') }}</label>
                                                <select class="form-select text-center" name="display_price" required>

                                                    <option disabled
                                                        {{ is_null($data['displayPriceStatus']) ? 'selected' : '' }}>
                                                        - {{ t('app.select') }} -
                                                    </option>

                                                    <option value="1"
                                                        {{ $data['displayPriceStatus'] == 1 ? 'selected' : '' }}>
                                                        {{ t('app.status.enabled') }}
                                                    </option>

                                                    <option value="0"
                                                        {{ $data['displayPriceStatus'] == 0 ? 'selected' : '' }}>
                                                        {{ t('app.status.disabled') }}
                                                    </option>

                                                </select>

                                                <p class="text-danger">{{ $errors->first('display_price') }}
                                                </p>
                                            </div>

                                            <button type="submit"
                                                class="btn btn-danger w-100 mt-2">{{ t('app.buttons.update') }}</button>


                                        </form>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>
        <!-- [ Main Content ] end -->

        <script>
            function previewPassport() {
                const preview = document.getElementById('passport-preview');
                const fileInput = document.getElementById('passport-upload');
                const file = fileInput.files[0];
                const reader = new FileReader();

                reader.onload = function() {
                    preview.src = reader.result;
                }

                if (file) {
                    reader.readAsDataURL(file);
                } else {
                    preview.src = 'placeholder.jpg';
                }
            }
        </script>

        {{-- fetch sub categories --}}
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                let categorySelect = document.getElementById('categorySelect');
                let subCategorySelect = document.getElementById('subCategorySelect');

                categorySelect.addEventListener('change', function() {

                    let slug = this.value;

                    subCategorySelect.innerHTML =
                        '<option disabled selected>- {{ t('app.select') }} -</option>';

                    if (!slug) return;

                    fetch('/get-subcategories/' + slug)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error("Network response not ok");
                            }
                            return response.json();
                        })
                        .then(data => {

                            let locale = "{{ currentLocale() }}";
                            let field = "sub_category_" + locale;

                            data.forEach(sub => {

                                let name = sub[field] && sub[field] !== "" ?
                                    sub[field] :
                                    sub.sub_category;

                                let option = document.createElement('option');
                                option.value = sub.slug;
                                option.textContent = name;

                                subCategorySelect.appendChild(option);
                            });

                        })
                        .catch(error => {
                            console.error("Error loading subcategories:", error);
                        });

                });

            });
        </script>

        {{-- <script>
            ClassicEditor
                .create(document.getElementById('fullDescription'), {
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'strikethrough', '|',
                        'fontFamily', 'fontSize', 'fontColor', 'fontBackgroundColor', '|',
                        'alignment', '|',
                        'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'link', 'insertTable', 'blockQuote', 'horizontalLine', 'codeBlock', '|',
                        'undo', 'redo'
                    ],
                    table: {
                        contentToolbar: [
                            'tableColumn',
                            'tableRow',
                            'mergeTableCells'
                        ]
                    },
                    placeholder: "Write full product description here...",
                })
                .then(editor => {
                    const editable = editor.ui.view.editable.element;
                    editable.style.minHeight = '400px';
                    editable.style.maxHeight = '500px';
                    editable.style.overflowY = 'auto';
                })
                .catch(error => {
                    console.error(error);
                });
        </script> --}}

        <script>
            tinymce.init({
                selector: '#fullDescription',
                height: 500,
                menubar: true,
                license_key: 'gpl',

                // Plugins: remove image, media
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'charmap',
                    'preview', 'anchor', 'searchreplace', 'visualblocks', 'code',
                    'fullscreen', 'insertdatetime', 'table', 'codesample',
                    'help', 'wordcount', 'emoticons'
                ],

                // Toolbar: remove image/media buttons
                toolbar: [
                    'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough |',
                    'alignleft aligncenter alignright alignjustify |',
                    'bullist numlist outdent indent | forecolor backcolor |',
                    'link table | charmap emoticons codesample |',
                    'removeformat fullscreen preview code help'
                ].join(''),

                font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',
                font_family_formats: 'Arial=arial,helvetica,sans-serif;' +
                    'Courier New=courier new,courier,monospace;' +
                    'Georgia=georgia,palatino;' +
                    'Tahoma=tahoma,arial,helvetica,sans-serif;' +
                    'Times New Roman=times new roman,times;' +
                    'Verdana=verdana,geneva;',

                content_style: `
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            padding: 10px;
        }
    `,

                setup: function(editor) {
                    // Ensure content is synced to textarea for Laravel
                    editor.on('change', function() {
                        editor.save();
                    });
                }
            });

            // Ensure content is saved before form submit
            document.querySelector('form').addEventListener('submit', function() {
                tinymce.triggerSave();
            });
        </script>






    </div>
@endsection
