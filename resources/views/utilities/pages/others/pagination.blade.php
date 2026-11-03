<div class="card-footer">
    <ul class="list-unstyled d-flex align-items-center gap-2 mb-0 pagination-common-style">

        {{-- Previous --}}
        <li class="{{ $paginatedArray->previousPageUrl() ? '' : 'disabled' }}">
            <a href="{{ $paginatedArray->previousPageUrl() ?: 'javascript:void(0)' }}">
                <i class="bi bi-arrow-left"></i>
            </a>
        </li>

        {{-- Navigation Info --}}
        <li class="px-2">
            <span>
                Showing {{ $paginatedArray->count() }} results
            </span>
        </li>

        {{-- Next --}}
        <li class="{{ $paginatedArray->hasMorePages() ? '' : 'disabled' }}">
            <a href="{{ $paginatedArray->nextPageUrl() ?: 'javascript:void(0)' }}">
                <i class="bi bi-arrow-right"></i>
            </a>
        </li>

    </ul>
</div>
