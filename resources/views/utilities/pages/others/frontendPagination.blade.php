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



<style>
    /* Pagination */
    .pagination_container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
        color: #666;
        padding: 1px 25px;
    }

    .pagination-count {
        font-weight: bold;
    }

    .pagination-links {
        list-style: none;
        display: flex;
        margin: 0;
        padding: 0;
    }

    .pagination-links li {
        margin-right: 5px;
    }

    .pagination-links li a {
        display: inline-block;
        padding: 5px 10px;
        border: 1px solid #ddd;
        border-radius: 3px;
        color: #007bff;
        text-decoration: none;
    }

    .pagination-links li.active a {
        background-color: #007bff;
        color: #fff;
    }

    .pagination-links li.disabled a {
        pointer-events: none;
        color: #999;
        background-color: #eee;
    }
</style>
