@extends('layouts.adminLayout')
@section('title', $data['pageTitle'])
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">{{ t('app.product.colours') }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ t('app.product.products') }}</a></li>
                    <li class="breadcrumb-item">{{ t('app.product.colours') }}</li>
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
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <button onclick="addColourFormModal('swal-size-md')" class="btn btn-primary">
                            <i class="feather-plus me-2"></i>
                            <span>{{ t('app.addNew') }}</span>
                        </button>
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
        <!-- [ Main Content ] start -->
        <div class="main-content">
            <div class="row col-md-12">

                <!-- [Latest Leads] start -->
                <div class="col-xxl-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">
                                {{ $data['pageTitle'] }}
                                {{-- <small class="text-muted fw-normal fs-6">
                                    ({{ $data['totalColours'] }})
                                </small> --}}
                            </h5>

                            {{-- search area start --}}

                            <div class="table-search">
                                <!-- Search form -->
                                <form method="GET" class="search-form" action="{{ route('coloursSearch') }}">
                                    @csrf
                                    <input type="text" name="searchKey" class="form-control form-control-sm"
                                        placeholder="{{ t('app.search') }}..." value="{{ $data['searchKey'] }}">
                                </form>
                                <!-- Toggle button (visible only on mobile) -->
                                <button type="button" class="btn btn-sm btn-light search-toggle">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>


                            {{-- search area end --}}
                        </div>
                        <div class="card-body custom-card-action p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr class="border-b">
                                            <th scope="row">#</th>
                                            <th scope="row">{{ t('app.name') }}</th>
                                            <th scope="row">{{ t('app.product.colour') }}</th>
                                            <th scope="row" class="">{{ t('app.actions') }}</th>
                                        </tr>
                                    </thead>
                                    @if ($data['colours']->isEmpty())
                                        <tbody>
                                            <td colspan="4" class="text-center text-danger fw-bold fs-5">
                                                {{ t('app.noRecordsFound') }}
                                            </td>
                                        </tbody>
                                    @else
                                        <tbody>
                                            @foreach ($data['colours'] as $colour)
                                                <tr>
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $locale = currentLocale();
                                                            $field = 'name_' . $locale;
                                                            $value = !empty($colour->$field)
                                                                ? $colour->$field
                                                                : $colour->name;
                                                        @endphp
                                                        {{ $value }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $hex = preg_match('/^#[0-9A-Fa-f]{6}$/', $colour->hex_code)
                                                                ? $colour->hex_code
                                                                : '#000000';
                                                        @endphp

                                                        <div style="display:flex; align-items:center; gap:8px;">

                                                            <span
                                                                style="width:60px; height:25px; background-color: {{ $hex }}; border:1px solid #ccc; display:inline-block;">
                                                            </span>
                                                            {{ $hex }}
                                                        </div>
                                                    </td>


                                                    <td>
                                                        <a href="{{ route('editColour', ['id' => $colour->id]) }}?language=en"
                                                            class="badge bg-primary text-white cursor-pointer me-3">
                                                            <i class="feather-edit"></i> {{ t('app.buttons.edit') }}
                                                        </a>
                                                        <form action="{{ route('deleteColour', ['id' => $colour->id]) }}"
                                                            method="POST" id="delete-form-{{ $colour->id }}"
                                                            style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>

                                                        <a href="javascript:void(0)" class="badge bg-danger text-white me-3"
                                                            onclick="event.preventDefault(); confirmDelete{{ $colour->id }}(); return false;">
                                                            <i class="feather-trash me-1"></i>
                                                            {{ t('app.buttons.delete') }}
                                                        </a>

                                                        <script>
                                                            function confirmDelete{{ $colour->id }}() {
                                                                Swal.fire({
                                                                    title: "{{ t('app.confirm') }}",
                                                                    text: "{{ t('app.deleteConfirmText') ?? 'Are you sure you want to delete this item?' }}",
                                                                    icon: 'warning',
                                                                    showCancelButton: true,
                                                                    confirmButtonColor: '#d33',
                                                                    cancelButtonColor: '#6c757d',
                                                                    confirmButtonText: "{{ t('app.buttons.yesProceed') ?? 'Yes, delete' }}",
                                                                    cancelButtonText: "{{ t('app.buttons.cancel') ?? 'Cancel' }}"
                                                                }).then(function(result) {
                                                                    // Check for result.value instead of result.isConfirmed
                                                                    if (result.value === true) {
                                                                        document.getElementById('delete-form-{{ $colour->id }}').submit();
                                                                    }
                                                                });
                                                            }
                                                        </script>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @endif
                                </table>
                            </div>
                        </div>

                        {{-- Navigation --}}
                        @php
                            $paginatedArray = $data['colours'];
                        @endphp
                        @include('utilities.pages.others.pagination')


                    </div>

                </div>
                {{-- Back Home --}}
                <div class="row mb-4 justify-content-center">
                    <div class="col-auto">
                        @switch($data['requestType'])
                            @case('search')
                                <a class="btn btn-primary" href="{{ route('colours') }}">
                                    <i class="fas fa-chevron-left"></i> &nbsp;
                                    {{ t('app.buttons.back') }}
                                </a>
                            @break

                            @default
                        @endswitch
                    </div>
                </div>


                <!-- [Latest Leads] end -->


                {{-- modals start --}}
                @include('users.admin.products.colours.include.addColourModal')
                {{-- modals end --}}
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>


@endsection
