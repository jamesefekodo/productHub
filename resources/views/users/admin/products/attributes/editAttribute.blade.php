@extends('layouts.adminLayout')
@section('title', t('app.buttons.edit') . '::' . $data['attribute']->name)
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10"><a href="#">{{ t('app.product.attributes') }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><a href="#">{{ t('app.product.products') }}</a></li>
                    <li class="breadcrumb-item"><a href="#"><a href="#">{{ t('app.product.attributes') }}</a>
                    </li>
                    <li class="breadcrumb-item">{{ t('app.buttons.view') }}</li>
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
                                        {{ t('app.product.attribute') }}
                                        @php
                                            $lang = $data['language'];
                                            $field = 'name_' . $lang;
                                            $value = !empty($data['attribute']->$field)
                                                ? $data['attribute']->$field
                                                : $data['attribute']->name;
                                        @endphp
                                        ({{ $value }})
                                    </span>
                                    {{-- <span class="fs-12 fw-normal text-muted text-truncate-1-line">Typically refers to
                                            adding a new potential customer or sales prospect</span> --}}
                                </h5>
                                {{-- editing per language starts --}}
                                <div class="d-flex flex-wrap justify-content-end gap-2">
                                    @foreach (activeLanguages()->where('status', 'active') as $language)
                                        <a href="{{ route('editAttribute', ['id' => $data['attribute']->id]) }}?language={{ $language->language_code }}"
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
                                {{-- Delete forms - OUTSIDE main form --}}
                                @if ($data['attribute']->attributeValues->count() > 0)
                                    @foreach ($data['attribute']->attributeValues as $attrValue)
                                        <form action="{{ route('deleteAttributeValue', ['id' => $attrValue->id]) }}"
                                            method="POST" id="delete-value-form-{{ $attrValue->id }}"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endforeach
                                @endif

                                {{-- Main form --}}
                                <form id="swalForm" method="POST"
                                    action="{{ route('updateAttributeSubmit', ['id' => $data['attribute']->id]) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="language" value="{{ $data['language'] }}">

                                    <div class="row col-md-12">
                                        <div class="mb-4 col-md-8">
                                            <label class="form-label">{{ t('app.name') }} <span
                                                    class="text-danger">*</span></label>
                                            @php
                                                $lang = $data['language'];
                                                $field = 'name_' . $lang;
                                                $value = !empty($data['attribute']->$field)
                                                    ? $data['attribute']->$field
                                                    : $data['attribute']->name;
                                            @endphp
                                            <input type="text" class="form-control" value="{{ $value }}"
                                                name="name" required>
                                            <p class="text-danger">{{ $errors->first('name') }}</p>
                                        </div>
                                        <div class="mb-4 col-md-4">
                                                <label class="form-label">{{ t('app.status.status') }} <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select text-center" name="is_active" required>
                                                    <option value="" disabled>- Select -</option>
                                                    <option value=1
                                                        {{ $data['attribute']->is_active == 1 ? 'selected' : '' }}>
                                                        {{ t('app.status.active') }}
                                                    </option>
                                                    <option value=0
                                                        {{ $data['attribute']->is_active == 0 ? 'selected' : '' }}>
                                                        {{ t('app.status.inactive') }}
                                                    </option>
                                                </select>
                                                <p id="status-error" class="text-danger">{{ $errors->first('status') }}
                                                </p>
                                            </div>

                                        <p class="m-2 text-bold text-decoration-underline">
                                            {{ t('app.product.attributeValues') }}
                                        </p>

                                        {{-- Existing Values --}}
                                        @if ($data['attribute']->attributeValues->count() > 0)
                                            @foreach ($data['attribute']->attributeValues as $attrValue)
                                                <div class="mb-4 col-md-6 attribute-value-item">
                                                    @php
                                                        $lang = $data['language'];
                                                        $field = 'value_' . $lang;
                                                        $toDisplay = !empty($attrValue->$field)
                                                            ? $attrValue->$field
                                                            : $attrValue->value;
                                                    @endphp
                                                    <div class="input-group">
                                                        <input type="text" class="form-control"
                                                            value="{{ $toDisplay }}"
                                                            name="value[{{ $attrValue->id }}]">

                                                        {{-- Delete button (no form here) --}}
                                                        <button type="button" class="btn btn-outline-danger"
                                                            onclick="event.preventDefault(); confirmDeleteValue{{ $attrValue->id }}(); return false;">
                                                            &times;
                                                        </button>
                                                    </div>
                                                </div>

                                                <script>
                                                    function confirmDeleteValue{{ $attrValue->id }}() {
                                                        Swal.fire({
                                                            title: "{{ t('app.confirm') }}",
                                                            text: "{{ t('app.deleteConfirmText') ?? 'Are you sure you want to delete this value?' }}",
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#d33',
                                                            cancelButtonColor: '#6c757d',
                                                            confirmButtonText: "{{ t('app.buttons.yesProceed') ?? 'Yes, delete' }}",
                                                            cancelButtonText: "{{ t('app.buttons.cancel') ?? 'Cancel' }}"
                                                        }).then(function(result) {
                                                            if (result.value === true) {
                                                                document.getElementById('delete-value-form-{{ $attrValue->id }}').submit();
                                                            }
                                                        });
                                                    }
                                                </script>
                                            @endforeach
                                        @endif

                                        {{-- Container for new values --}}
                                        <div id="new-values-container"></div>

                                        {{-- Add New Value Button --}}
                                        <div class="mb-3">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                id="add-new-value-btn">
                                                <i class="feather-plus me-1"></i>
                                                {{ t('app.product.addAnotherValue') ?? 'Add New Value' }}
                                            </button>
                                        </div>

                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const newContainer = document.getElementById('new-values-container');
                                                const addButton = document.getElementById('add-new-value-btn');

                                                if (addButton && newContainer) {
                                                    addButton.addEventListener('click', function() {
                                                        const newValueDiv = document.createElement('div');
                                                        newValueDiv.className = 'mb-4 col-md-12 attribute-value-item';
                                                        newValueDiv.innerHTML = `
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control"
                                                                            name="new_value[]"
                                                                            placeholder="{{ t('app.value') ?? 'Enter value' }}">
                                                                        <button type="button"
                                                                                class="btn btn-outline-danger remove-new-value"
                                                                                onclick="this.closest('.attribute-value-item').remove();">
                                                                            &times;
                                                                        </button>
                                                                    </div>
                                                                `;
                                                        newContainer.appendChild(newValueDiv);
                                                    });
                                                }
                                            });
                                        </script>
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
                            <a class="btn btn-primary" href="{{ route('attributes') }}">
                                <i class="fas fa-chevron-left"></i> &nbsp;
                                {{ t('app.buttons.back') }}
                            </a>
                        </div>
                    </div>


                </div>


            </div>
        </div>
        <!-- [ Main Content ] end -->


    </div>

@endsection
