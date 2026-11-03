<section class="main-search-section">
    <div class="main-search-container">
        <div class="main-search-glass-wrapper">
            <form action="{{ route(routePrefix() . 'SearchProduct') }}" method="GET">
                <div class="main-search-input-group">
                    <i class="fas fa-search main-search-icon"></i>

                    <input type="text" id="mainSearchInput" class="main-search-input-field"
                        placeholder="{{ t('app.product.productSearchPlaceholder') }}" value="{{ $data['searchKey'] }}"
                        name="searchKey" autocomplete="off">

                    <div class="main-search-actions">
                        <button type="button" id="mainSearchClearBtn" class="main-search-action-btn main-search-hide"
                            title="Clear">
                            <i class="fas fa-times"></i>
                        </button>

                        <div class="main-search-divider"></div>

                        <button type="submit" id="mainSearchSubmitBtn" class="main-search-action-btn" title="Search">
                            <i class="fas fa-magnifying-glass"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Suggestions -->
        <div id="mainSearchSuggestions" class="main-search-suggestions-box main-search-hide">
            <div class="main-search-suggestion-header">
                {{ t('app.suggestions') }}
            </div>
            <div id="mainSearchSuggestionList"></div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const input = document.getElementById('mainSearchInput');
        const suggestionBox = document.getElementById('mainSearchSuggestions');
        const suggestionList = document.getElementById('mainSearchSuggestionList');
        const clearBtn = document.getElementById('mainSearchClearBtn');

        input.addEventListener('input', function() {

            let query = this.value.trim();

            if (query.length < 2) {
                suggestionBox.classList.add('main-search-hide');
                suggestionList.innerHTML = '';
                clearBtn.classList.add('main-search-hide');
                return;
            }

            clearBtn.classList.remove('main-search-hide');

            fetch(`{{ route('products.search') }}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {

                    suggestionList.innerHTML = '';

                    if (!data.length) {
                        suggestionBox.classList.add('main-search-hide');
                        return;
                    }

                    data.forEach(product => {

                        let name = product.name ||
                            product.name_zh ||
                            product.name_fr ||
                            'Unnamed Product';

                        suggestionList.innerHTML += `
                        <div class="main-search-suggestion-item"
                             data-name="${name}">
                            ${name}
                        </div>
                    `;
                    });

                    suggestionBox.classList.remove('main-search-hide');
                })
                .catch(error => {
                    console.error('Search error:', error);
                });
        });

        // Autocomplete behavior (NO redirect)
        suggestionList.addEventListener('click', function(e) {
            if (e.target.classList.contains('main-search-suggestion-item')) {
                input.value = e.target.dataset.name;
                suggestionBox.classList.add('main-search-hide');
            }
        });

        clearBtn.addEventListener('click', function() {
            input.value = '';
            suggestionList.innerHTML = '';
            suggestionBox.classList.add('main-search-hide');
            clearBtn.classList.add('main-search-hide');
        });

    });
</script>


{{-- ################################################ --}}
{{-- search results  --}}
@if ($data['requestType'] == 'search')
    @if ($data['requestType'])
        <h2 class="section-title mt-5">{{ t('app.searchResultFor') . ' :: ' . $data['searchKey'] }} </h2>
        @if ($data['products']->isNotEmpty())
            <div class="prod-grid">
                @foreach ($data['products'] as $product)
                    <a href="{{ route(routePrefix() . 'ViewProduct', ['slug' => $product->slug]) }}">
                        <div class="prod-card">
                            @if ($product->is_featured == true)
                                <span class="prod-tag">{{ t('app.product.featured') }}</span>
                            @endif
                            <div class="prod-img">
                                @php
                                    $thumbnailImage = asset('system/images/icon_placeholder.png');

                                    if (!$product->mediaFiles->isEmpty()) {
                                        $primaryImage = $product->mediaFiles->where('is_primary', true)->first();

                                        if ($primaryImage) {
                                            $thumbnailImage = asset(
                                                'assets/uploads/products/products/media/images/' .
                                                    $primaryImage->file_name,
                                            );
                                        }
                                    }
                                @endphp

                                <img src="{{ $thumbnailImage }}" alt="{{ $product->name }}">
                            </div>
                            <div class="prod-info">
                                <h4>
                                    @php
                                        $locale = currentLocale();
                                        $field = 'name_' . $locale;
                                        $value = !empty($product->$field) ? $product->$field : $product->name;
                                    @endphp

                                    {{ $value }}
                                </h4>

                                @if (displayPrice() == true)
                                    @if (!is_null($product->price) && $product->price != 0)
                                        <span class="price">₦{{ number_format($product->price, 2) }}</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
                {{-- <div class="prod-card">
                    <div class="prod-img">🔋</div>
                    <div class="prod-info">
                        <h4>High-Cap Power Cell</h4>
                        <span class="price">$150.00</span>
                    </div>
                </div> --}}
            </div>
        @else
            <div class="text-center py-5">
                <h3 class="text-danger">
                    {{ t('notifications.noRecordsFound') }}
                </h3>
            </div>
        @endif

    @endif

    {{-- Pagination --}}
    @php
        $paginatedArray = $data['products'];
    @endphp
    @include('utilities.pages.others.frontendPagination')

    <div class="w-100 justify-content-center text-center mb-4">
        <a class="btn btn-success text-white" href="{{ url('/') }}">
            <i class="fas fa-chevron-left ms-2"></i> {{ t('app.buttons.backHome') }}
        </a>
    </div>

@endif
