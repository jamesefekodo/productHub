<div class="main-step-container">
    <div class="main-step-bar">

        <a href="{{ route('viewDraftedQuote', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}" class="main-step-node {{ $data['currentSection'] == 1 ? 'main-active' : ($data['currentSection'] > 1 ? 'main-past' : '') }}">
            <div class="main-step-circle">
                1
            </div>
            <span class="main-step-text">{{ t('app.product.products') }}</span>
            <div class="main-step-connector"></div>
        </a>

        <a href="{{ route('createQuoteTaxDiscount', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}" class="main-step-node {{ $data['currentSection'] == 2 ? 'main-active' : ($data['currentSection'] > 2 ? 'main-past' : '') }}">
            <div class="main-step-circle">2</div>
            <span class="main-step-text">{{ t('app.payment.taxes') }}/{{ t('app.payment.discounts') }}</span>
            <div class="main-step-connector"></div>
        </a>

        <a href="{{ route('createQuoteCustomer', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}" class="main-step-node {{ $data['currentSection'] == 3 ? 'main-active' : ($data['currentSection'] > 3 ? 'main-past' : '') }}">
            <div class="main-step-circle">3</div>
            <span class="main-step-text">{{ t('app.sales.customer') }}</span>
            <div class="main-step-connector"></div>
        </a>
        <a href="{{ route('createQuoteNotes', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}" class="main-step-node {{ $data['currentSection'] == 4 ? 'main-active' : ($data['currentSection'] > 4 ? 'main-past' : '') }}">
            <div class="main-step-circle">4</div>
            <span class="main-step-text">{{ t('app.notes') }}</span>
            <div class="main-step-connector"></div>
        </a>

        <a href="{{ route('createQuoteFinish', ['quote_number' => $data['existingTemporaryQuote']->quote_number]) }}" class="main-step-node {{ $data['currentSection'] == 6 ? 'main-active' : ($data['currentSection'] > 6 ? 'main-past' : '') }}">
            <div class="main-step-circle">5</div>
            <span class="main-step-text">{{ t('app.finish') }}</span>
        </a>

    </div>
</div>
