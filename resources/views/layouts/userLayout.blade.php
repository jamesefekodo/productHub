<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="keyword" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ systemLogo() }}">

    <title>
        @yield('title') | {{ config('app.name') }}
    </title>

    @yield('style')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('system/styles/userStyle.css') }}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    @yield('scripts')
</head>

<body>
    @php
        $loginRoute = 'loginPage';
    @endphp

    <div class="top-bar">
        {{-- <span>Global Shipping Available</span>
        <span>Support: 1-800-PRO-HUB</span> --}}
    </div>


    @include('layouts.include.userNavBar')
    <div class="">
        @yield('content')
    </div>

    <footer
        style="
        background: #000;
        color: #fff;
        padding: 20px;
        text-align: center;
        font-size: 13px;
        opacity: 0.8;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
    ">
        <p>&copy; 2026 ProductHub Global. All rights reserved.</p>
    </footer>


    <div class="float-icon f-left">
        <i class="fas fa-fingerprint"></i>
    </div>
    <div class="float-icon f-right"><i class="fab fa-accessible-icon"></i></div>

    <div id="cart">

        <div class="cart-header">
            <h2>{{ t('app.product.quoteList') }}</h2>
        </div>

        <div class="cart-body">
            @if (draftedQuote())
                @foreach (draftedQuote()->items as $item)
                    @if ($item->variant)
                        @php
                            $locale = currentLocale();
                            $field = 'name_' . $locale;
                            $variantName = !empty($item->variant->$field)
                                ? $item->variant->$field
                                : $item->variant->name;
                        @endphp
                        <p><span ms-2>{{ $loop->iteration }}.</span> {{ $variantName }} ->
                            {{ $item->quantity }}</p>
                    @else
                        @php
                            $locale = currentLocale();
                            $field = 'name_' . $locale;
                            $productName = !empty($item->product->$field)
                                ? $item->product->$field
                                : $item->product->name;
                        @endphp
                        <p><span ms-2>{{ $loop->iteration }}.</span> {{ $productName }} ->
                            {{ $item->quantity }}</p>
                    @endif
                    <hr>
                @endforeach
            @else
                <h3 class="text-danger text-center opacity-50">
                    {{ t('app.product.emptyQuoteList') }}!
                </h3>
            @endif
        </div>

        <div class="cart-footer">
            <div class="row col-md-12">
                <div class="col-md-8">
                    <a href="{{ route(routePrefix() . 'NewQuoteList') }}"
                        class="btn btn-success text-white w-100 mb-2">
                        {{ t('app.buttons.proceed') }}
                        <i class="fas fa-chevron-right ms-2"></i>
                    </a>
                </div>
                <div class="col-md-4">
                    <button onclick="toggleCart()" class="btn btn-danger w-100">
                        {{ t('app.buttons.close') }}
                    </button>
                </div>
            </div>



        </div>

    </div>


    <script>
        const nav = document.getElementById('navbar');
        const navLinks = document.getElementById('navLinks');
        const desktopNav = document.querySelector('.desktop-nav-bg');

        // Scroll event
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
                if (desktopNav) desktopNav.classList.add('hide');
            } else {
                nav.classList.remove('scrolled');
                if (desktopNav) desktopNav.classList.remove('hide');
            }
        });

        // Mobile menu toggle
        function toggleMobileMenu() {
            navLinks.classList.toggle('active');
        }

        // Cart toggle
        function toggleCart() {
            document.getElementById('cart').classList.toggle('active');
        }

        // Hero Slider Logic
        let current = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');

        if (slides.length > 0) {
            function setSlide(n) {
                slides[current].classList.remove('active');
                if (dots.length > 0) dots[current].classList.remove('active');
                current = n;
                slides[current].classList.add('active');
                if (dots.length > 0) dots[current].classList.add('active');
            }

            setInterval(() => {
                let next = (current + 1) % slides.length;
                setSlide(next);
            }, 7000);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function() {
            if ($('.cat-carousel').length) {
                $('.cat-carousel').owlCarousel({
                    loop: true, // Endless loop
                    margin: 20,
                    nav: true, // Show nav buttons
                    dots: false, // Show dots
                    autoplay: true, // Auto play
                    autoplayTimeout: 3500, // 3.5 seconds
                    autoplayHoverPause: true, // Pause on hover
                    autoplaySpeed: 800, // Transition speed
                    smartSpeed: 800, // Smart speed
                    navText: [
                        '<i class="fas fa-chevron-left"></i>',
                        '<i class="fas fa-chevron-right"></i>'
                    ],
                    responsive: {
                        0: {
                            items: 1
                        },
                        600: {
                            items: 2
                        },
                        1000: {
                            items: 4
                        }
                    }
                });
            }
        });
    </script>

    {{-- product review carousel --}}
    <script>
        // Use window load (fires after document.ready) so this runs AFTER
        // any existing owl-carousel auto-inits elsewhere on the page.
        $(window).on('load', function () {

            // Wait until after all other scripts/carousels have initialized,
            // then add classes and init ONLY this carousel by its unique ID.
            var $mainRevCarousel = $("#mainRevOwlSlider");

            // Add owl classes at runtime so Owl's auto-init never sees this element
            $mainRevCarousel.addClass("owl-carousel owl-theme");

            $mainRevCarousel.owlCarousel({
                loop: true,
                margin: 0,
                autoplay: true,
                autoplayTimeout: 4000,
                responsive: {
                    0:    { items: 1 },
                    600:  { items: 1 },
                    1000: { items: 2 }
                }
            });

            // Form submission handler
            $('#mainRevForm').on('submit', function (e) {
                e.preventDefault();
                var $btn = $(this).find('.main-rev-btn');
                $btn.html('Submitting... <i class="fas fa-spinner fa-spin"></i>').attr('disabled', true);

                setTimeout(function () {
                    $btn.css('background', '#10b981').html('Success! Review Live <i class="fas fa-check"></i>');
                }, 1500);
            });

        });
    </script>
    @include('layouts.include.toastrNotificationFunction')

</body>

</html>
