@php
    $currentLocale = app()->getLocale();
    $currentLanguage = collect(activeLanguages())->firstWhere('language_code', $currentLocale);
@endphp

<div class="lang-card-container">
    <div class="language-switcher">
        <button class="lang-toggle" type="button">
            <img src="{{ asset('system/images/lang/' . $currentLanguage->flag) }}" alt="" width="25px">
            <span class="lang-code">
                {{ $currentLanguage->language ?? strtoupper($currentLocale) }}
            </span>

            <svg class="chevron" viewBox="0 0 24 24">
                <path d="M6 9l6 6 6-6" />
            </svg>
        </button>

        <ul class="lang-menu">
            @foreach (activeLanguages() as $language)
                <li>
                    <a href="?lang={{ $language->language_code }}"
                        class="{{ $currentLocale === $language->language_code ? 'active' : '' }}">
                        <img src="{{ asset('system/images/lang/' . $language->flag) }}" alt="" width="25px">
                        {{ $language->language }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

</div>
