@extends('layouts.adminLayout')
@section('title', $data['pageTitle'])
@section('content')


    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">{{ t('app.product.quoteRequests') }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a
                            href="#">{{ t('app.sales.sales') }}/{{ t('app.sales.marketing') }}</a></li>
                    <li class="breadcrumb-item">{{ t('app.product.quotes') }}</li>
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
                {{-- inner nav start --}}
                <div class="col-md-2">
                    @include('users.admin.salesMarketing.quotes.include.inner_nav')
                </div>
                {{-- Inner nav end --}}
                <!-- [Latest Leads] start -->
                <div class="col-md-10">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">
                                {{ $data['pageTitle'] }}
                                {{-- <small class="text-muted fw-normal fs-6">
                                    ({{ $data['totalCategories'] }})
                                </small> --}}
                            </h5>

                            {{-- search area start --}}

                            <div class="table-search">
                                <!-- Search form -->
                                <form method="GET" class="search-form" action="{{ route('quoteRequestsSearch') }}">
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
                                            <th scope="row">{{ t('app.sales.ref') }}</th>
                                            <th scope="row">{{ t('app.sales.customer') }}</th>
                                            <th scope="row">{{ t('app.dateSubmitted') }}</th>
                                            <th scope="row">{{ t('app.status.status') }}</th>
                                            <th scope="row" class="">{{ t('app.actions') }}</th>
                                        </tr>
                                    </thead>
                                    @if ($data['quotes']->isEmpty())
                                        <tbody>
                                            <td colspan="7" class="text-center text-danger fw-bold fs-5">
                                                {{ t('app.noRecordsFound') }}
                                            </td>
                                        </tbody>
                                    @else
                                        <tbody>
                                            @foreach ($data['quotes'] as $quote)
                                                <tr>
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td class="text-uppercase">
                                                        <span @class([
                                                            'notification-dot' => strtolower(trim($quote->status)) === 'submitted',
                                                        ])></span>
                                                        {{ $quote->slug }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $customer = $quote->customer;

                                                            $firstName = $customer->first_name ?? ($quote->name ?? '');
                                                            $middleName = $customer->middle_name ?? '';
                                                            $lastName = $customer->last_name ?? '';
                                                            $email = $customer->email1 ?? ($quote->email ?? '');
                                                            $phone = $customer->phone1 ?? ($quote->phone ?? '');
                                                        @endphp

                                                        {{ trim($firstName . ' ' . $middleName . ' ' . $lastName) }}

                                                        @if ($quote->customer)
                                                            <a href="" target="_blank" class="me-2">
                                                                <span class="badge bg-soft-primary text-primary"><i
                                                                        class="feather-user p-1"></i></span>
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($quote->submitted_at)->isoFormat('MMMM Do, YYYY [at] h:mm A') }}
                                                    </td>
                                                    <td>
                                                        @switch($quote->status)
                                                            @case('draft')
                                                                <span
                                                                    class="badge bg-soft-warning text-warning">{{ t('app.status.draft') }}</span>
                                                            @break

                                                            @case('submitted')
                                                                <span
                                                                    class="badge bg-soft-warning text-warning">{{ t('app.status.submitted') }}</span>
                                                            @break

                                                            @case('quoted')
                                                                <span
                                                                    class="badge bg-soft-primary text-primary">{{ t('app.status.quoted') }}</span>
                                                            @break

                                                            @case('approved')
                                                                <span
                                                                    class="badge bg-soft-primary text-primary">{{ t('app.status.approved') }}</span>
                                                            @break

                                                            @case('rejected')
                                                                <span
                                                                    class="badge bg-soft-danger text-danger">{{ t('app.status.rejected') }}</span>
                                                            @break

                                                            @case('expired')
                                                                <span
                                                                    class="badge bg-soft-danger text-danger">{{ t('app.status.expired') }}</span>
                                                            @break

                                                            @case('converted')
                                                                <span
                                                                    class="badge bg-soft-danger text-danger">{{ t('app.status.converted') }}</span>
                                                            @break

                                                            @default
                                                                {{ $quote->status }}
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('viewQuote', ['slug' => $quote->slug]) }}"
                                                            class="btn btn-primary d-inline-flex align-items-center gap-1">
                                                            <i class="feather-eye"></i>
                                                            {{ t('app.buttons.view') }}
                                                        </a>
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
                            $paginatedArray = $data['quotes'];
                        @endphp
                        @include('utilities.pages.others.pagination')


                    </div>

                </div>
                {{-- Back Home --}}
                <div class="row mb-4 justify-content-center">
                    <div class="col-auto">
                        @switch($data['requestType'])
                            @case('search')
                                <a class="btn btn-primary" href="{{ route('quoteRequests') }}">
                                    <i class="fas fa-chevron-left"></i> &nbsp;
                                    {{ t('app.buttons.back') }}
                                </a>
                            @break

                            @default
                        @endswitch
                    </div>
                </div>

            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>

    {{-- filter script start --}}
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
    {{-- filter script end --}}
@endsection
