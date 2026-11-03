@extends('layouts.adminLayout')
@section('title', t('app.addNew') . '::' . t('app.product.product'))
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10"><a href="#">{{ t('app.product.products') }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><a href="#">{{ t('app.product.products') }}</a></li>
                    <li class="breadcrumb-item"><a href="#"><a href="#">{{ t('app.product.products') }}</a>
                    </li>
                    <li class="breadcrumb-item">{{ t('app.addNew') }}</li>
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
        <div class="bg-white py-3 border-bottom rounded-0 p-md-0 mb-0">
            <div class="d-md-none d-flex">
                <a href="javascript:void(0)" class="page-content-left-open-toggle">
                    <i class="feather-align-left fs-20"></i>
                </a>
            </div>
        </div>
        <!-- [ Main Content ] start -->
        <div class="main-content">
            <div class="tab-content">
                <div class="row col-md-12">
                    <div class="card stretch stretch-full">
                        <div class="card-body lead-status">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold mb-0 me-4">
                                    <span class="d-block mb-2">{{ t('app.addNew') }} ::
                                        {{ t('app.product.product') }}
                                    </span>
                                    {{-- <span class="fs-12 fw-normal text-muted text-truncate-1-line">Typically refers to
                                            adding a new potential customer or sales prospect</span> --}}
                                </h5>
                            </div>

                        </div>

                        <div class="card-body general-info">
                            <div class="card-body">
                                <form id="swalForm" method="POST" action="{{ route('createNewProductSubmit') }}"
                                    enctype="multipart/form-data">
                                    @csrf


                                    @php
                                        $image = asset('system/images/icon_placeholder.png');
                                    @endphp
                                    <div class="mb-3 form-group upload-container">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col-md-6"><label for="passport-upload" class="preview-frame">
                                                    <img id="passport-preview" src="{{ $image }}" alt="">
                                                    <div class="overlay"><i class="fas fa-upload"></i></div>
                                                </label>
                                            </div>
                                            <div class="col-md-6"><input type="file" id="passport-upload"
                                                    accept=".jpg, .jpeg, .png, .webp" onchange="previewPassport()"
                                                    name="image">
                                            </div>
                                            <p class="text-red">{{ $errors->first('image') }}</p>
                                        </div>
                                    </div>

                                    <div class="row col-md-12">
                                        <div class="mb-4 col-md-6">
                                            <label class="form-label">{{ t('app.product.productName') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" required>
                                            <p class="text-danger">{{ $errors->first('name') }}
                                            </p>
                                        </div>

                                        <div class="mb-4 col-md-6">
                                            <label class="form-label">
                                                {{ t('app.product.category') }}
                                                <span class="text-danger">*</span>
                                            </label>

                                            <select class="form-select text-center" name="category" id="categorySelect"
                                                required>
                                                <option value="" disabled selected>
                                                    - {{ t('app.select') }} -
                                                </option>

                                                @if (($data['activeCategories'] ?? collect())->isNotEmpty())
                                                    @foreach ($data['activeCategories'] as $category)
                                                        <option value="{{ $category->slug }}"
                                                            {{ old('category') == $category->slug ? 'selected' : '' }}>

                                                            @php
                                                                $locale = currentLocale();
                                                                $field = 'category_' . $locale;
                                                                $value = !empty($category->$field)
                                                                    ? $category->$field
                                                                    : $category->category;
                                                            @endphp

                                                            {{ $value }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            <p class="text-danger">{{ $errors->first('category') }}</p>
                                        </div>


                                        <div class="mb-4 col-md-6">
                                            <label class="form-label">
                                                {{ t('app.product.subCategory') }}
                                                <span class="text-danger">*</span>
                                            </label>

                                            <select class="form-select text-center" name="sub_category"
                                                id="subCategorySelect" required>
                                                <option value="" disabled selected>
                                                    - {{ t('app.select') }} -
                                                </option>
                                            </select>

                                            <p class="text-danger">{{ $errors->first('sub_category') }}</p>
                                        </div>

                                        <div class="mb-4 col-md-6">
                                            <label class="form-label">
                                                {{ t('app.product.brand') }}
                                                <span class="text-danger">*</span>
                                            </label>

                                            <select class="form-select text-center" name="brand" id="categorySelect"
                                                required>
                                                <option value="" disabled selected>
                                                    - {{ t('app.select') }} -
                                                </option>

                                                @if (($data['brands'] ?? collect())->isNotEmpty())
                                                    @foreach ($data['brands'] as $brand)
                                                        <option value="{{ $brand->slug }}"
                                                            {{ old('brand') == $brand->slug ? 'selected' : '' }}>
                                                            {{ $brand->brand }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            <p class="text-danger">{{ $errors->first('brand') }}</p>
                                        </div>

                                        <div class="mb-4 col-md-12">
                                            <label class="form-label">{{ t('app.shortDescription') }}</label>
                                            <textarea class="form-control" name="short_description" style="min-height: 200px;">{{ old('short_description') }}</textarea>
                                            <p class="text-danger">{{ $errors->first('short_description') }}
                                            </p>
                                        </div>
                                        <div class="mb-4 col-md-12">
                                            <label class="form-label">{{ t('app.fullDescription') }}</label>
                                            <textarea class="form-control" id="fullDescription" name="full_description" style="min-height: 400px;">{{ old('full_description') }}</textarea>
                                            <p class="text-danger">{{ $errors->first('full_description') }}</p>
                                        </div>
                                        <div class="mb-4 col-md-6">
                                            <label class="form-label">{{ t('app.product.sku') }}</label>
                                            <input type="text" class="form-control" name="sku">
                                            <p class="text-danger">{{ $errors->first('sku') }}
                                            </p>
                                        </div>
                                        <div class="mb-4 col-md-6">
                                            <label class="form-label">{{ t('app.product.price') }} (₦)</label>
                                            <input type="text" class="form-control" name="price">
                                            <p class="text-danger">{{ $errors->first('price') }}
                                            </p>
                                        </div>

                                        <div class="section-divider">
                                            <span>{{ t('app.settings.seo.seo') }}</span>
                                        </div>
                                        <div class="mb-4 col-md-12">
                                            <label class="form-label">{{ t('app.settings.seo.metaTitle') }}</label>
                                            <input type="text" class="form-control" value="" name="meta_title">
                                            <p id="status-error" class="text-danger">{{ $errors->first('meta_title') }}
                                            </p>
                                        </div>
                                        <div class="mb-4 col-md-12">
                                            <label class="form-label">{{ t('app.settings.seo.metaKeywords') }}</label>
                                            <input type="text" class="form-control" value=""
                                                name="meta_keywords">
                                            <p id="status-error" class="text-danger">
                                                {{ $errors->first('meta_keywords') }}
                                            </p>
                                        </div>
                                        <div class="mb-4 col-md-12">
                                            <label class="form-label">{{ t('app.settings.seo.metaDescription') }}</label>
                                            <textarea class="form-control" name="meta_description" style="min-height: 150px;">{{ old('meta_description') }}</textarea>
                                            <p id="status-error" class="text-danger">
                                                {{ $errors->first('meta_description') }}
                                            </p>
                                        </div>
                                    </div>

                                    <button type="submit"
                                        class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>


                                </form>

                            </div>
                        </div>
                    </div>
                    {{-- Back Home --}}
                    <div class="row mb-4 justify-content-center">
                        <div class="col-auto">
                            <a class="btn btn-primary" href="{{ route('products') }}">
                                <i class="fas fa-chevron-left"></i> &nbsp;
                                {{ t('app.buttons.back') }}
                            </a>
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
