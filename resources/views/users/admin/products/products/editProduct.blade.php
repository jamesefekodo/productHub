@extends('layouts.adminLayout')
@section('title', t('app.buttons.edit') . '::' . $data['product']->name)
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10"><a href="#">{{ $data['product']->name }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><a href="#">{{ t('app.product.products') }}</a></li>
                    <li class="breadcrumb-item"><a href="#"><a href="#">{{ t('app.product.products') }}</a>
                    </li>
                    <li class="breadcrumb-item">{{ t('app.buttons.edit') }}</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    {{-- <div class="d-flex d-md-none">
                        <a href="javascript:void(0)" class="page-header-right-close-toggle">
                            <i class="feather-arrow-left me-2"></i>
                            <span>{{ t('app.buttons.back') }}</span>
                        </a>
                    </div> --}}
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
                                    <span class="d-block mb-2">{{ t('app.buttons.edit') }} ::
                                        @php
                                            $locale = currentLocale();
                                            $field = 'name_' . $locale;
                                            $value = !empty($data['product']->$field)
                                                ? $data['product']->$field
                                                : $data['product']->name;
                                        @endphp

                                        {{ $value }}
                                    </span>
                                    {{-- <span class="fs-12 fw-normal text-muted text-truncate-1-line">Typically refers to
                                            adding a new potential customer or sales prospect</span> --}}
                                </h5>
                                {{-- editing per language starts --}}
                                <div class="d-flex flex-wrap justify-content-end gap-2">
                                    @foreach (activeLanguages()->where('status', 'active') as $language)
                                        <a href="{{ route('editProduct', ['slug' => $data['product']->slug]) }}?language={{ $language->language_code }}"
                                            class="btn btn-sm edit-by-lang {{ $data['language'] == $language->language_code ? 'edit-by-lang-active' : '' }} d-flex align-items-center gap-1">
                                            <img src="{{ asset('system/images/lang/' . $language->flag) }}" alt=""
                                                width="22">
                                            {{ $language->language }}
                                        </a>
                                    @endforeach
                                </div>
                                {{-- editing per language ends --}}
                            </div>

                        </div>

                        <div class="card-body general-info">
                            <div class="card-body">
                                <form id="swalForm" method="POST"
                                    action="{{ route('updateProductSubmit', ['slug' => $data['product']->slug]) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="language" value="{{ $data['language'] }}">

                                    <div class="row col-md-12">
                                        <div class="mb-4 col-md-6">
                                            <label class="form-label">{{ t('app.product.productName') }} <span
                                                    class="text-danger">*</span></label>
                                            @php
                                                $lang = $data['language'];
                                                $field = 'name_' . $lang;
                                                $value = !empty($data['product']->$field)
                                                    ? $data['product']->$field
                                                    : $data['product']->name;
                                            @endphp

                                            {{ $value }}
                                            <input type="text" class="form-control" name="name"
                                                value="{{ $value }}">
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

                                                @if (($data['allCategories'] ?? collect())->isNotEmpty())
                                                    @foreach ($data['allCategories'] as $category)
                                                        <option value="{{ $category->slug }}"
                                                            {{ $data['product']->category->slug == $category->slug ? 'selected' : '' }}>

                                                            @php
                                                                $lang = $data['language'];
                                                                $field = 'category_' . $lang;
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

                                            <select class="form-select text-center" name="brand" required>
                                                <option value="" disabled selected>
                                                    - {{ t('app.select') }} -
                                                </option>

                                                @if (($data['brands'] ?? collect())->isNotEmpty())
                                                    @foreach ($data['brands'] as $brand)
                                                        <option value="{{ $brand->slug }}"
                                                            {{ $data['product']->brand_id == $brand->id ? 'selected' : '' }}>
                                                            {{ $brand->brand }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            <p class="text-danger">{{ $errors->first('brand') }}</p>
                                        </div>

                                        <div class="mb-4 col-md-12">
                                            <label class="form-label">{{ t('app.shortDescription') }}</label>
                                            @php
                                                $lang = $data['language'];
                                                $field = 'short_description_' . $lang;
                                                $shortDesc = !empty($data['product']->$field)
                                                    ? $data['product']->$field
                                                    : $data['product']->short_description;
                                            @endphp
                                            <textarea class="form-control" name="short_description" style="min-height: 200px;">{{ $shortDesc }}</textarea>
                                            <p class="text-danger">{{ $errors->first('short_description') }}
                                            </p>
                                        </div>
                                        <div class="mb-4 col-md-12">
                                            <label class="form-label">{{ t('app.shortDescription') }}</label>
                                            @php
                                                $lang = $data['language'];
                                                $field = 'description_' . $lang;
                                                $shortDesc = !empty($data['product']->$field)
                                                    ? $data['product']->$field
                                                    : $data['product']->description;
                                            @endphp
                                            <textarea class="form-control" id="shortDescription" name="description" style="min-height: 400px;">{{ $shortDesc }}</textarea>
                                            <p class="text-danger">{{ $errors->first('description') }}</p>
                                        </div>
                                        <div class="mb-4 col-md-6">
                                            <label class="form-label">{{ t('app.product.sku') }}</label>
                                            <input type="text" class="form-control" name="sku"
                                                value="{{ $data['product']->sku }}">
                                            <p class="text-danger">{{ $errors->first('sku') }}
                                            </p>
                                        </div>
                                        <div class="mb-4 col-md-6">
                                            <label class="form-label">{{ t('app.product.price') }} (₦)</label>
                                            <input type="text" class="form-control" name="price"
                                                value="{{ $data['product']->price }}">
                                            <p class="text-danger">{{ $errors->first('price') }}
                                            </p>
                                        </div>

                                        <div class="section-divider">
                                            <span>{{ t('app.settings.seo.seo') }}</span>
                                        </div>
                                        <div class="mb-4 col-md-12">
                                            <label class="form-label">{{ t('app.settings.seo.metaTitle') }}</label>
                                            @php
                                                $lang = $data['language'];
                                                $field = 'meta_title_' . $lang;
                                                $metaTitle = !empty($data['product']->$field)
                                                    ? $data['product']->$field
                                                    : $data['product']->meta_title;
                                            @endphp
                                            <input type="text" class="form-control" name="meta_title"
                                                value="{{ $metaTitle }}">
                                            <p id="status-error" class="text-danger">{{ $errors->first('meta_title') }}
                                            </p>
                                        </div>
                                        <div class="mb-4 col-md-12">
                                            <label class="form-label">{{ t('app.settings.seo.metaKeywords') }}</label>
                                            @php
                                                $lang = $data['language'];
                                                $field = 'meta_keywords_' . $lang;
                                                $metaKeywords = !empty($data['product']->$field)
                                                    ? $data['product']->$field
                                                    : $data['product']->meta_keywords;
                                            @endphp
                                            <input type="text" class="form-control" value="{{ $metaKeywords }}"
                                                name="meta_keywords">
                                            <p id="status-error" class="text-danger">
                                                {{ $errors->first('meta_keywords') }}
                                            </p>
                                        </div>
                                        <div class="mb-4 col-md-12">
                                            <label class="form-label">{{ t('app.settings.seo.metaDescription') }}</label>
                                            @php
                                                $lang = $data['language'];
                                                $field = 'meta_description_' . $lang;
                                                $metaDescription = !empty($data['product']->$field)
                                                    ? $data['product']->$field
                                                    : $data['product']->meta_description;
                                            @endphp
                                            <textarea class="form-control" name="meta_description" style="min-height: 150px;">{{ $metaDescription }}</textarea>
                                            <p id="status-error" class="text-danger">
                                                {{ $errors->first('meta_description') }}
                                            </p>
                                        </div>
                                        <div class="mb-4 col-md-4">
                                            <label class="form-label">
                                                {{ t('app.published') }}
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select text-center" name="is_active" required>

                                                <option value="" disabled>
                                                    - {{ t('app.select') }} -
                                                </option>

                                                <option value="1"
                                                    {{ $data['product']->is_active ? 'selected' : '' }}>
                                                    {{ t('app.status.true') }}
                                                </option>

                                                <option value="0"
                                                    {{ !$data['product']->is_active ? 'selected' : '' }}>
                                                    {{ t('app.status.false') }}
                                                </option>

                                            </select>

                                            <p class="text-danger">{{ $errors->first('is_active') }}</p>
                                        </div>
                                        <div class="mb-4 col-md-4">
                                            <label class="form-label">
                                                {{ t('app.product.featured') }}
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select text-center" name="is_featured" required>

                                                <option value="" disabled>
                                                    - {{ t('app.select') }} -
                                                </option>

                                                <option value="1"
                                                    {{ $data['product']->is_featured ? 'selected' : '' }}>
                                                    {{ t('app.status.true') }}
                                                </option>

                                                <option value="0"
                                                    {{ !$data['product']->is_featured ? 'selected' : '' }}>
                                                    {{ t('app.status.false') }}
                                                </option>

                                            </select>

                                            <p class="text-danger">{{ $errors->first('is_featured') }}</p>
                                        </div>
                                        <div class="mb-4 col-md-4">
                                            <label class="form-label">
                                                {{ t('app.product.availability') }}
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select text-center" name="is_available" required>

                                                <option value="" disabled>
                                                    - {{ t('app.select') }} -
                                                </option>

                                                <option value="1"
                                                    {{ $data['product']->is_available ? 'selected' : '' }}>
                                                    {{ t('app.status.true') }}
                                                </option>

                                                <option value="0"
                                                    {{ !$data['product']->is_available ? 'selected' : '' }}>
                                                    {{ t('app.status.false') }}
                                                </option>

                                            </select>

                                            <p class="text-danger">{{ $errors->first('is_available') }}</p>
                                        </div>
                                    </div>

                                    <button type="submit"
                                        class="btn btn-primary w-100 mt-2">{{ t('app.buttons.update') }}</button>


                                </form>

                            </div>
                        </div>
                    </div>
                    {{-- Back Home --}}
                    <div class="row mb-4 justify-content-center">
                        <div class="col-auto">
                            <a class="btn btn-primary" href="{{ route('viewProduct', ['slug' => $data['product']->slug]) }}">
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

                const currentCategorySlug = "{{ $data['product']->category->slug ?? '' }}";
                const currentSubCategorySlug = "{{ $data['product']->subCategory->slug ?? '' }}";

                function loadSubCategories(categorySlug, selectSlug = null) {
                    subCategorySelect.innerHTML = '<option disabled selected>- {{ t('app.select') }} -</option>';

                    if (!categorySlug) return;

                    fetch('/get-subcategories/' + categorySlug)
                        .then(response => {
                            if (!response.ok) throw new Error("Network response not ok");
                            return response.json();
                        })
                        .then(data => {
                            let lang = "{{ $data['language'] }}";
                            let field = "sub_category_" + lang;

                            data.forEach(sub => {
                                let name = (sub[field] && sub[field] !== "") ? sub[field] : sub
                                    .sub_category;

                                let option = document.createElement('option');
                                option.value = sub.slug;
                                option.textContent = name;

                                if (selectSlug && sub.slug === selectSlug) {
                                    option.selected = true;
                                }

                                subCategorySelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error("Error loading subcategories:", error));
                }

                // On page load
                if (currentCategorySlug) {
                    loadSubCategories(currentCategorySlug, currentSubCategorySlug);
                }

                // On category change
                categorySelect.addEventListener('change', function() {
                    loadSubCategories(this.value);
                });

            });
        </script>


        <script>
            tinymce.init({
                selector: '#shortDescription',
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
