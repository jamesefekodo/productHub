<nav id="navbar">
    <div class="logo">
        <a href="{{ config('app.url') }}">
            <img src="{{ systemWideLogo() }}" alt="logo" class="responsive-logo" />
            {{-- {{ config('app.name') }} --}}
        </a>
    </div>

    <ul class="nav-links" id="navLinks">
        <i class="fas fa-times mobile-close" onclick="toggleMobileMenu()"></i>

        <li class="nav-item"><a href="{{ config('app.url') }}"
                class="{{ request()->routeIs('homepage') ? 'active-link' : '' }}">{{ t('app.home') }}</a></li>

        <li class="nav-item" onclick="this.classList.toggle('active')">
            <a href="javascript:void(0)"
                class="{{ Str::startsWith(request()->path(), ['category', 'categories', 'products', 'product']) ? 'active-link' : '' }}">{{ t('app.product.categories') }}
                <i class="fas fa-chevron-down" style="font-size: 10px; margin-left: 5px; transition: 0.3s;"></i>
            </a>
            <div class="mega-menu">
                @if (activeCategories()->isNotEmpty())
                    @foreach (activeCategories() as $category)
                        <a href="{{ route(routePrefix() . 'ViewCategory', ['slug' => $category->slug]) }}"
                            class="mega-option">
                            {{-- <i class="fas fa-microchip"></i> --}}
                            <div class="row colmd-12">
                                <div class="col-md-4">
                                    @php
                                        $imagePath = 'assets/uploads/products/categories/icons/' . $category->image;
                                    @endphp

                                    <img src="{{ $category->image && file_exists(public_path($imagePath))
                                        ? asset($imagePath)
                                        : asset('system/images/icon_placeholder.png') }}"
                                        class="img-fluid" alt="{{ $category->name }}">
                                </div>
                                <div class="col-md-8">
                                    <strong>
                                        @php
                                            $locale = currentLocale();
                                            $field = 'category_' . $locale;
                                            $catName = !empty($category->$field)
                                                ? $category->$field
                                                : $category->category;
                                        @endphp
                                        {{ $catName }}
                                    </strong>
                                    <p>
                                        @php
                                            $locale = currentLocale();
                                            $field = 'meta_description_' . $locale;
                                            $catDesc = !empty($category->$field)
                                                ? $category->$field
                                                : $category->meta_description;

                                            $catDesc = strip_tags($catDesc);
                                        @endphp

                                        {{ \Illuminate\Support\Str::limit($catDesc, 25, '...') }}
                                    </p>

                                </div>
                            </div>


                            <div>

                            </div>
                        </a>
                    @endforeach
                @endif
                {{-- <a href="#" class="mega-option">
                    <i class="fas fa-microchip"></i>
                    <div><strong>Electronics</strong>
                        <p>Pro-grade components</p>
                    </div>
                </a> --}}
            </div>
        </li>

        <li class="nav-item" onclick="this.classList.toggle('active')">
            <a href="javascript:void(0)"
                class="{{ Str::startsWith(request()->path(), ['quote']) ? 'active-link' : '' }}">{{ t('app.product.quotes') }}
                <i class="fas fa-chevron-down" style="font-size: 10px; margin-left: 5px; transition: 0.3s;"></i>
            </a>
            <div class="mega-menu" style="width: 300px; grid-template-columns: 1fr;">
                <a href="{{ route(routePrefix() . 'NewQuoteList') }}"
                    class="mega-option {{ Str::startsWith(request()->path(), ['quote']) ? 'active-sub-menu' : '' }}">
                    <div><strong>{{ t('app.draft') }}
                            @if (draftedQuote())
                                @if (draftedQuote())
                                    <span
                                        class="badge bg-danger text-white">{{ draftedQuote()->items->count() }}</span>
                                @endif
                            @endif
                        </strong>
                    </div>
                </a>
                <a href="#" class="mega-option">
                    <div><strong>{{ t('app.others') }}</strong>
                    </div>
                </a>
            </div>
        </li>
        <li class="nav-item"><a href="#">About</a></li>

        <li class="nav-item"><a href="#">Blog</a></li>

        <li class="nav-item"><a href="#">Contact</a></li>


        <li class="nav-item ms-3" onclick="this.classList.toggle('active')">
            @php
                $currentLocale = app()->getLocale();
                $currentLanguage = collect(activeLanguages())->firstWhere('language_code', $currentLocale);
            @endphp
            <a href="javascript:void(0)"><img src="{{ asset('system/images/lang/' . $currentLanguage->flag) }}"
                    alt="" width="25px" class="me-2">
                {{ $currentLanguage->language ?? strtoupper($currentLocale) }}
                <i class="fas fa-chevron-down" style="font-size: 10px; margin-left: 5px; transition: 0.3s;"></i>
            </a>
            <div class="mega-menu" style="width: 300px; grid-template-columns: 1fr;">
                @foreach (activeLanguages() as $language)
                    <a href="?lang={{ $language->language_code }}"
                        class="mega-option {{ $currentLocale === $language->language_code ? 'active' : '' }} text-center">
                        <img src="{{ asset('system/images/lang/' . $language->flag) }}" alt="" width="25px"
                            class="me-2">
                        {{ $language->language }}
                    </a>
                @endforeach

            </div>
        </li>
    </ul>



    <div class="nav-right">
        <div class="nav-search">
            <form action="{{ route(routePrefix() . 'SearchProduct') }}">
                <input type="text" name="searchKey" placeholder="{{ t('app.product.productSearchPlaceholder') }}"
                    value="{{ $data['searchKey'] ?? '' }}">
                <i class="fas fa-search"
                    style="position: absolute; right: 10px; top: 10px; font-size: 12px; opacity: 0.5;"></i>
                <button type="submit"></button>
            </form>
        </div>
        <div class="cart-trigger" onclick="toggleCart()">
            <i class="fas fa-shopping-bag"></i>
            @if (draftedQuote())
                <span class="cart-badge">{{ draftedQuote()->items->count() }}</span>
            @endif
        </div>

        {{-- ####################################################### --}}
        @if (auth()->check())
            @php
                $user = auth()->user();
                $initials = '';

                if ($user->first_name && $user->last_name) {
                    $initials = strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1));
                } elseif ($user->username) {
                    $initials = strtoupper(substr($user->username, 0, 1));
                } else {
                    $initials = 'U';
                }
            @endphp
            <div class="nav-item dropdown stable-hover">
                <a class="nav-link d-flex align-items-center gap-2 py-2" href="#" id="userDropdown" role="button"
                    data-bs-toggle="dropdown">
                    <div class="avatar-box">
                        @if ($user->photo)
                            <img src="{{ asset('assets/uploads/users/photos/' . $user->photo) }}" class="rounded-circle border"
                                width="38" height="38" style="object-fit: cover;">
                        @else
                            <div class="avatar-initials">
                                {{ $initials }}
                            </div>
                        @endif
                    </div>
                    <div class="d-none d-lg-block">
                        <span
                            class="d-block fw-bold text-black small line-height-1">{{ $user->first_name ?? $user->username }}</span>
                        {{-- <span class="text-muted smallest">Profile <i class="bi bi-chevron-down"></i></span> --}}
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 m-0" aria-labelledby="userDropdown">
                    <li class="px-3 py-3 bg-light">
                        <div class="fw-bold text-dark truncate">{{ $user->first_name }} {{ $user->last_name }}</div>
                        <div class="text-muted smallest truncate">{{ $user->email }}</div>
                    </li>
                    <li>
                        <hr class="dropdown-divider m-0">
                    </li>
                    <li><a class="dropdown-item py-2 mt-1 text-center" href="{{ route('customerMyProfile') }}"><i class="bi bi-person me-2"></i>{{ t('app.user.profile') }}</a></li>
                    <li><a class="dropdown-item py-2 text-center" href="/settings"><i class="bi bi-gear me-2"></i> Settings</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="{{ route('logoutRequest') }}" method="GET">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 bg-danger text-white text-center fw-medium">
                                <i class="bi bi-box-arrow-right me-2"></i> {{ t('auth.logout') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        @else
            <div class="login-menu"><a href="{{ route($loginRoute) }}">
                    {{ t('auth.login') }}
                </a>
            </div>
        @endif
        {{-- ####################################################### --}}

        <div class="menu-toggle" onclick="toggleMobileMenu()">
            <i class="fas fa-bars"></i>
        </div>
    </div>

</nav>
