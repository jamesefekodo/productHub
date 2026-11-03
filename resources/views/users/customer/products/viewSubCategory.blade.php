@extends('layouts.userLayout')
@section('title', t('app.product.category') . ' :: ' . $data['subCategory']->sub_category)
@section('content')

    <div class="desktop-nav-bg"></div>
    <div class="main-content-wrapper">

        <section class="section">
            {{-- category --}}
            @php
                $locale = currentLocale();
                $field = 'category_' . $locale;

                $catName =
                    isset($data['subCategory']->theCategory->$field) && $data['subCategory']->theCategory->$field
                        ? $data['subCategory']->theCategory->$field
                        : $data['subCategory']->theCategory->category;
            @endphp

            {{-- sub category --}}

            @php
                $locale = currentLocale();
                $field = 'sub_category_' . $locale;

                $subCatName =
                    isset($data['subCategory']->$field) && $data['subCategory']->$field
                        ? $data['subCategory']->$field
                        : $data['subCategory']->sub_category;
            @endphp


            {{-- breadcomb start --}}
            <div aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a
                            href="{{ route(routePrefix() . 'ViewCategory', ['slug' => $data['subCategory']->theCategory->slug]) }}">{{ $catName }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $subCatName }}
                    </li>
                </ol>
            </div>

            {{-- breadcomb end --}}


            <div class="my-page-header">{{ $subCatName }}</div>

            {{-- featured product --}}
            @php
                $featuredProduct = featuredProducts()->where('sub_category_id', $data['subCategory']->id);
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


            {{-- all products --}}
            <div class="my-4">
                <h2 class="section-title text-center mt-5 mb-3">{{ t('app.product.allProducts') }}</h2>
                @if ($data['subCategory']->activeProducts->isNotEmpty())
                    <div class="prod-grid">
                        @foreach ($data['subCategory']->activeProducts as $product)
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
