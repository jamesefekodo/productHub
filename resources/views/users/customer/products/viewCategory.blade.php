@extends('layouts.userLayout')
@section('title', t('app.product.category') . ' :: ' . $data['category']->category)
@section('content')

    <div class="desktop-nav-bg"></div>
    <div class="main-content-wrapper">

        <section class="section">
            @php
                $locale = currentLocale();
                $field = 'category_' . $locale;

                $catName =
                    isset($data['category']->$field) && $data['category']->$field
                        ? $data['category']->$field
                        : $data['category']->category;
            @endphp


            <div class="my-page-header">{{ $catName }}</div>

            {{-- featured product --}}
            @php
                $featuredProduct = featuredProducts()->where('category_id', $data['category']->id);
            @endphp
            @if ($featuredProduct->isNotEmpty())

                <h2 class="section-title text-center mt-5 mb-3">{{ t('app.product.featuredProducts') }}</h2>

                <div class="cat-carousel owl-carousel owl-theme">
                    @foreach ($featuredProduct as $product)
                        <a href="{{ route(routePrefix() . 'ViewProduct', ['slug' => $product->slug]) }}">
                            <div class="cat-card">
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
                                <div class="cat-overlay">
                                    @php
                                        $locale = currentLocale();
                                        $field = 'name_' . $locale;
                                        $prodName = !empty($product->$field) ? $product->$field : $product->name;
                                    @endphp


                                    <h3>{{ $prodName }}</h3>
                                    @if (displayPrice() == true)
                                        @if (!is_null($product->price) && $product->price != 0)
                                            <span class="price">₦{{ number_format($product->price, 2) }}</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- ################################# --}}

            {{-- ############################ --}}

            {{-- all categories --}}
            @if ($data['category']->activeSubCategories->isNotEmpty())
                <div class="my-4">
                    <h2 class="section-title text-center mt-5 mb-3">{{ t('app.product.subCategories') }}</h2>

                    <div class="cat-grid">
                        @foreach ($data['category']->activeSubCategories as $subCat)
                            <a href="{{ route(routePrefix() . 'ViewSubCategory', ['slug' => $subCat->slug]) }}">
                                <div class="cat-card">
                                    @php
                                        $imagePath = public_path(
                                            'assets/uploads/products/categories/subCategories/icons/' . $subCat->image,
                                        );
                                        $imageUrl = file_exists($imagePath)
                                            ? asset(
                                                'assets/uploads/products/categories/subCategories/icons/' .
                                                    $subCat->image,
                                            )
                                            : asset('system/images/icon_placeholder.png');

                                        $locale = currentLocale();
                                        $field = 'sub_category_' . $locale;
                                        $name = !empty($subCat->$field) ? $subCat->$field : $subCat->sub_category;
                                    @endphp
                                    <img src="{{ $imageUrl }}" alt="{{ $name }}">
                                    <div class="cat-overlay">
                                        <h3>{{ $name }}</h3>
                                        <p>@php
                                            $locale = currentLocale();
                                            $field = 'meta_description_' . $locale;
                                            $metaDesc = !empty($subCat->$field)
                                                ? $subCat->$field
                                                : $subCat->meta_description;

                                            $words = explode(' ', $metaDesc);
                                            $metaDescShort = implode(' ', array_slice($words, 0, 10));
                                            if (count($words) > 10) {
                                                $metaDescShort .= '...';
                                            }
                                        @endphp
                                            {{ $metaDescShort }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- all products --}}
            <div class="my-4">
                <h2 class="section-title text-center mt-5 mb-3">{{ t('app.product.allProducts') }}</h2>
                @if ($data['category']->activeProducts->count() > 0)
                    <div class="prod-grid">
                        @foreach ($data['category']->activeProducts as $product)
                            <a href="{{ route(routePrefix() . 'ViewProduct', ['slug' => $product->slug]) }}">
                                <div class="prod-card">
                                    @if ($product->is_featured == true)
                                        <span class="prod-tag">{{ t('app.product.featured') }}</span>
                                    @endif
                                    <div class="prod-img">
                                        @php
                                            $thumbnailImage = asset('system/images/icon_placeholder.png');

                                            if (!$product->mediaFiles->isEmpty()) {
                                                $primaryImage = $product->mediaFiles
                                                    ->where('is_primary', true)
                                                    ->first();

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
                    </div>
                @else
                    <div class="text-center py-5">
                        <h3 class="text-danger">
                            {{ t('notifications.noRecordsFound') }}
                        </h3>
                    </div>
                @endif
            </div>

        </section>
    </div>





@endsection
