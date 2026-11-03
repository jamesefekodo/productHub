@extends('layouts.adminLayout')
@section('title', $data['pageTitle'])
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">{{ t('app.product.attributes') }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ t('app.product.products') }}</a></li>
                    <li class="breadcrumb-item">{{ t('app.product.attributes') }}</li>
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
                        <div class="dropdown">
                            <a class="btn btn-icon btn-light-brand" data-bs-toggle="dropdown" data-bs-offset="0, 10"
                                data-bs-auto-close="outside">
                                <i class="feather-filter"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:void(0);" class="dropdown-item filter-option active fw-bold"
                                    data-filter="all">
                                    <span class="wd-7 ht-7 bg-primary rounded-circle d-inline-block me-3"></span>
                                    <span>{{ t('app.all') }}</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0);" class="dropdown-item filter-option" data-filter="active">
                                    <span class="wd-7 ht-7 bg-success rounded-circle d-inline-block me-3"></span>
                                    <span>{{ t('app.status.active') }}</span>
                                </a>
                                <a href="javascript:void(0);" class="dropdown-item filter-option" data-filter="inactive">
                                    <span class="wd-7 ht-7 bg-danger rounded-circle d-inline-block me-3"></span>
                                    <span>{{ t('app.status.inactive') }}</span>
                                </a>
                            </div>
                        </div>
                        <button onclick="addAttributeFormModal('swal-size-md')" class="btn btn-primary">
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
                                    ({{ $data['totalAttributes'] }})
                                </small> --}}
                            </h5>

                            {{-- search area start --}}

                            <div class="table-search">
                                <!-- Search form -->
                                <form method="GET" class="search-form" action="{{ route('attributesSearch') }}">
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
                                            <th scope="row">{{ t('app.values') }}</th>
                                            <th scope="row">{{ t('app.status.status') }}</th>
                                            <th scope="row" class="">{{ t('app.actions') }}</th>
                                        </tr>
                                    </thead>
                                    @if ($data['attributes']->isEmpty())
                                        <tbody>
                                            <td colspan="5" class="text-center text-danger fw-bold fs-5">
                                                {{ t('app.noRecordsFound') }}
                                            </td>
                                        </tbody>
                                    @else
                                        <tbody>
                                            @foreach ($data['attributes'] as $attr)
                                                <tr>
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $locale = currentLocale();
                                                            $field = 'name_' . $locale;
                                                            $value = !empty($attr->$field)
                                                                ? $attr->$field
                                                                : $attr->name;
                                                        @endphp
                                                        {{ $value }}
                                                    </td>
                                                    <td>
                                                        @if ($attr->attributeValues->count() > 0)
                                                            @foreach ($attr->attributeValues as $value)
                                                                <span class="badge text-black" style="background:#c9c9c9">
                                                                    @php
                                                                        $locale = currentLocale();
                                                                        $field = 'value_' . $locale;
                                                                        $attributeValue = !empty($value->$field)
                                                                            ? $value->$field
                                                                            : $value->value;
                                                                    @endphp

                                                                    {{ $attributeValue }}
                                                                </span>
                                                            @endforeach
                                                        @else
                                                            -
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @switch($attr->is_active)
                                                            @case(true)
                                                                <span
                                                                    class="badge bg-soft-success text-success">{{ t('app.status.active') }}</span>
                                                            @break

                                                            @case(false)
                                                                <span
                                                                    class="badge bg-soft-danger text-danger">{{ t('app.status.inactive') }}</span>
                                                            @break

                                                            @default
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('editAttribute', ['id' => $attr->id]) }}?language=en"
                                                            class="badge bg-primary text-white cursor-pointer me-3">
                                                            <i class="feather-edit"></i> {{ t('app.buttons.edit') }}
                                                        </a>
                                                        <form action="{{ route('deleteAttribute', ['id' => $attr->id]) }}"
                                                            method="POST" id="delete-form-{{ $attr->id }}"
                                                            style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>

                                                        <a href="javascript:void(0)" class="badge bg-danger text-white me-3"
                                                            onclick="event.preventDefault(); confirmDelete{{ $attr->id }}(); return false;">
                                                            <i class="feather-trash me-1"></i>
                                                            {{ t('app.buttons.delete') }}
                                                        </a>

                                                        <script>
                                                            function confirmDelete{{ $attr->id }}() {
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
                                                                        document.getElementById('delete-form-{{ $attr->id }}').submit();
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
                            $paginatedArray = $data['attributes'];
                        @endphp
                        @include('utilities.pages.others.pagination')


                    </div>

                </div>
                {{-- Back Home --}}
                <div class="row mb-4 justify-content-center">
                    <div class="col-auto">
                        @switch($data['requestType'])
                            @case('search')
                                <a class="btn btn-primary" href="{{ route('attributes') }}">
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
                @include('users.admin.products.attributes.include.addAttributeModal')
                {{-- modals end --}}
            </div>
        </div>
        <!-- [ Main Content ] end -->

        {{-- filter start --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                console.log('Filter script loaded');

                // Get all filter buttons
                const filterButtons = document.querySelectorAll('.filter-option');
                const tableRows = document.querySelectorAll('table tbody tr');

                console.log('Found filter buttons:', filterButtons.length);
                console.log('Found table rows:', tableRows.length);

                // Filter function
                function filterTable(status) {
                    console.log('Filtering by:', status);

                    let visibleCount = 0;

                    tableRows.forEach(row => {
                        // Skip "no records" row
                        if (row.querySelector('td[colspan]')) {
                            return;
                        }

                        // Get status badge
                        const statusBadge = row.querySelector('.badge');

                        if (!statusBadge) {
                            row.style.display = '';
                            return;
                        }

                        // Determine row status
                        let rowStatus = 'inactive';
                        if (statusBadge.classList.contains('bg-soft-success') ||
                            statusBadge.classList.contains('text-success')) {
                            rowStatus = 'active';
                        }

                        // Show/hide row
                        if (status === 'all' || rowStatus === status) {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    console.log('Visible rows:', visibleCount);

                    // Update button states
                    filterButtons.forEach(btn => {
                        if (btn.getAttribute('data-filter') === status) {
                            btn.classList.add('active', 'fw-bold');
                            btn.style.backgroundColor = '#f0f4ff';
                        } else {
                            btn.classList.remove('active', 'fw-bold');
                            btn.style.backgroundColor = '';
                        }
                    });
                }

                // Add click event to each filter button
                filterButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        console.log('Filter clicked');

                        const filterValue = this.getAttribute('data-filter');
                        console.log('Filter value:', filterValue);

                        filterTable(filterValue);
                    });
                });

                // Set "All" as default
                console.log('Setting default filter to: all');
                filterTable('all');
            });
        </script>

        {{-- filter ends --}}
    </div>


@endsection
