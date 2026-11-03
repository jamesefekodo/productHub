@extends('layouts.userLayout')
@if ($data['requestType'] == 'search')
    @section('title', t('app.searchResultFor') . ' :: ' . $data['searchKey'])
@else
    @section('title', t('app.homePage'))
@endif
@section('content')

    <section class="hero">
        @if (!homepageSliders()->isEmpty())
            @foreach (homepageSliders() as $slider)
                @php
                    $image = $slider->background_image
                        ? asset('assets/uploads/homepageSlider/' . $slider->background_image)
                        : 'system/images/icon_placeholder.png';
                @endphp

                <div class="slide active" style="background-image: url('{{ $image }}');">
                    <div class="hero-box">
                        @php
                            $locale = currentLocale();
                            $field = 'header_' . $locale;
                            $header = !empty($slider->$field) ? $slider->$field : $slider->header;
                        @endphp
                        <h1>{{ $header }}</h1>
                        <p>@php
                            $locale = currentLocale();
                            $field = 'description_' . $locale;
                            $desc = !empty($slider->$field) ? $slider->$field : $slider->description;
                        @endphp
                            {{ $desc }}
                        </p>

                        @if ($slider->link)
                            <div class="btn-group d-flex w-100 justify-content-end">
                                @php
                                    $url = $slider->link;
                                    if (!preg_match('/^https?:\/\//', $url)) {
                                        $url = 'https://' . $url;
                                    }
                                @endphp

                                <div class="">
                                    <a href="{{ $url }}" class="btn btn-primary" rel="noopener noreferrer">
                                        <i class="fas fa-eye"></i> {{ t('app.buttons.view') }}
                                    </a>
                                </div>

                                {{-- <a class="btn btn-outline">Explore</a> --}}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
            <div class="indicators">
                @foreach (homepageSliders() as $index => $slider)
                    <div class="dot {{ $index === 0 ? 'active' : '' }}" onclick="setSlide({{ $index }})"></div>
                @endforeach

            </div>
        @endif
    </section>

    <section class="section">
        @if ($data['requestType'] !== 'search')
            @if (featuredCategories()->isNotEmpty())

                <h2 class="section-title">{{ t('app.product.featuredCategories') }}</h2>

                <div class="cat-carousel owl-carousel owl-theme">
                    @foreach (featuredCategories() as $category)
                        <a href="{{ route(routePrefix() . 'ViewCategory', ['slug' => $category->slug]) }}">
                            <div class="cat-card">
                                @php
                                    $imagePath = public_path(
                                        'assets/uploads/products/categories/icons/' . $category->image,
                                    );
                                    $imageUrl = file_exists($imagePath)
                                        ? asset('assets/uploads/products/categories/icons/' . $category->image)
                                        : asset('system/images/icon_placeholder.png');

                                    $locale = currentLocale();
                                    $field = 'category_' . $locale;
                                    $name = !empty($category->$field) ? $category->$field : $category->category;
                                @endphp
                                <img src="{{ $imageUrl }}" alt="{{ $name }}">
                                <div class="cat-overlay">
                                    <h3>{{ $name }}</h3>
                                    <p>{{ $category->products->count() }} {{ t('app.product.product') }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        @endif


        {{-- <div class="cat-grid">
            @if (activeCategories()->isNotEmpty())
                @foreach (activeCategories() as $category)
                    <div class="cat-card">
                        <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800">
                        <div class="cat-overlay">
                            <h3>Advanced Computing</h3>
                            <p>12k Products</p>
                        </div>
                    </div>
                @endforeach
            @endif

            <div class="cat-card">
                <img src="https://images.unsplash.com/photo-1537462715879-360eeb61a0ad?auto=format&fit=crop&w=800">
                <div class="cat-overlay">
                    <h3>Machinery</h3>
                    <p>850 Products</p>
                </div>
            </div>
            <div class="cat-card">
                <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800">
                <div class="cat-overlay">
                    <h3>Workspace</h3>
                    <p>2k Products</p>
                </div>
            </div>
        </div> --}}
        {{-- ########################## --}}

        @include('frontend.include.searchBar')
        {{-- ############################## --}}
        @if ($data['requestType'] !== 'search')
            @if (featuredProducts()->isNotEmpty())
                <h2 class="section-title mt-5">{{ t('app.product.featuredProducts') }}</h2>
                <div class="prod-grid">
                    @foreach (featuredProducts() as $product)
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
            @endif
        @endif



    </section>
    {{-- ############################################################################################# --}}
    <section class="main-rev-section">
        <div class="main-rev-container">

            <div class="main-rev-slider-wrap">
                <!-- No owl-carousel class here — initialized exclusively by ID in JS below -->
                <div id="mainRevOwlSlider">

                    <div class="main-rev-item">
                        <div class="main-rev-score">★★★★★</div>
                        <p class="main-rev-comment">"Excellent durability and precision. This exceeded all my expectations
                            for industrial use."</p>
                        <div class="main-rev-meta">
                            <div class="main-rev-avatar">JD</div>
                            <div>
                                <h4 class="main-rev-name">James Dalton</h4>
                                <span class="main-rev-status">Verified Purchase</span>
                            </div>
                        </div>
                    </div>

                    <div class="main-rev-item">
                        <div class="main-rev-score">★★★★☆</div>
                        <p class="main-rev-comment">"The integration was seamless. Docking one star only because shipping
                            took an extra day."</p>
                        <div class="main-rev-meta">
                            <div class="main-rev-avatar" style="background: #10b981;">MK</div>
                            <div>
                                <h4 class="main-rev-name">Maria Khan</h4>
                                <span class="main-rev-status">Verified Purchase</span>
                            </div>
                        </div>
                    </div>

                    <div class="main-rev-item">
                        <div class="main-rev-score">★★★★★</div>
                        <p class="main-rev-comment">"Top tier support team. They helped me configure the automation settings
                            in minutes."</p>
                        <div class="main-rev-meta">
                            <div class="main-rev-avatar" style="background: #f59e0b;">AL</div>
                            <div>
                                <h4 class="main-rev-name">Alex Lee</h4>
                                <span class="main-rev-status">Expert Reviewer</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>


    <section class="newsletter">
        <div>
            <h2>Stay Informed</h2>
            <p>Get the latest enterprise updates and pricing.</p>


        </div>
        <div class="news-input">
            <input type="email" placeholder="Enter your email">
            {{-- <button class="btn btn-primary">Join</button> --}}
        </div>
    </section>


@endsection
