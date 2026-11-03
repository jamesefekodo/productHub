@extends('layouts.adminLayout')
@section('title', t('app.buttons.edit') . '::' . $data['colour']->name)
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10"><a href="#">{{ t('app.product.colours') }}</h5>
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
                                        {{ t('app.product.colour') }}
                                        @php
                                            $lang = $data['language'];
                                            $field = 'name_' . $lang;
                                            $value = !empty($data['colour']->$field)
                                                ? $data['colour']->$field
                                                : $data['colour']->name;
                                        @endphp
                                        ({{ $value }})
                                    </span>
                                    {{-- <span class="fs-12 fw-normal text-muted text-truncate-1-line">Typically refers to
                                            adding a new potential customer or sales prospect</span> --}}
                                </h5>
                                {{-- editing per language starts --}}
                                <div class="d-flex flex-wrap justify-content-end gap-2">
                                    @foreach (activeLanguages()->where('status', 'active') as $language)
                                        <a href="{{ route('editColour', ['id' => $data['colour']->id]) }}?language={{ $language->language_code }}"
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
                                {{-- Main form --}}
                                <form id="swalForm" method="POST"
                                    action="{{ route('updateColourSubmit', ['id' => $data['colour']->id]) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="language" value="{{ $data['language'] }}">

                                    <div class="row col-md-12">
                                        <div class="mb-4 col-md-6">
                                            <label class="form-label">{{ t('app.name') }} <span
                                                    class="text-danger">*</span></label>
                                            @php
                                                $lang = $data['language'];
                                                $field = 'name_' . $lang;
                                                $value = !empty($data['colour']->$field)
                                                    ? $data['colour']->$field
                                                    : $data['colour']->name;
                                            @endphp
                                            <input type="text" class="form-control" value="{{ $value }}"
                                                name="name" required>
                                            <p class="text-danger">{{ $errors->first('name') }}</p>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ t('app.product.colourCode') }}</label>
                                            @php
                                                $hexValue = $data['colour']->hex_code ?? '#000000';
                                            @endphp
                                            <table style="width: 100%;">
                                                <tr>
                                                    <td style="width: 80%;">
                                                        <input type="text" name="hex_code"
                                                            class="form-control text-center" value="{{ $hexValue }}"
                                                            placeholder="#000000" maxlength="7" required
                                                            style="border: 3px solid {{ $hexValue }};"
                                                            onkeyup="window.syncColors(this, 'text')"
                                                            onchange="window.syncColors(this, 'text')">
                                                    </td>
                                                    <td style="width: 20%; padding-left: 10px;">
                                                        <input type="color" value="{{ $hexValue }}"
                                                            style="width: 100%; height: 38px; cursor: pointer;"
                                                            onchange="window.syncColors(this, 'picker')"
                                                            oninput="window.syncColors(this, 'picker')">
                                                    </td>
                                                </tr>
                                            </table>
                                            <p style="color: red; font-size: 12px; margin-top: 5px;">
                                                {{ $errors->first('hex_code') }}
                                            </p>
                                        </div>
                                    </div>

                                    <button type="submit"
                                        class="btn btn-primary w-100 mt-2">{{ t('app.buttons.update') }}</button>
                                </form>

                                <script>
                                    window.syncColors = function(element, type) {
                                        const form = element.closest('form');
                                        if (!form) return;

                                        const textInput = form.querySelector('input[name="hex_code"]');
                                        const colorInput = form.querySelector('input[type="color"]');

                                        if (!textInput || !colorInput) return;

                                        if (type === 'picker') {
                                            textInput.value = colorInput.value.toUpperCase();
                                            textInput.style.borderColor = colorInput.value;
                                        } else {
                                            let val = textInput.value.trim();

                                            if (val && !val.startsWith('#')) {
                                                val = '#' + val;
                                                textInput.value = val;
                                            }

                                            if (/^#[0-9A-Fa-f]{6}$/i.test(val)) {
                                                colorInput.value = val;
                                                textInput.style.borderColor = val;
                                            }
                                        }
                                    };
                                </script>

                            </div>
                        </div>
                    </div>
                    {{-- Back Home --}}
                    <div class="row mb-4 justify-content-center">
                        <div class="col-auto">
                            <a class="btn btn-primary" href="{{ route('colours') }}">
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
