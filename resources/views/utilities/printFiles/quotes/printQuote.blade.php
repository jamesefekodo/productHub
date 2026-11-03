<!DOCTYPE html>
<html lang="en, zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('system/images/favicon.ico') }}">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    {{-- font awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <title>Quote
        ({{ $data['quote']->quote_number }})
    </title>


</head>

<body>
    <div class="background-container">
        <div class="content">
            {{-- bg start --}}

            <div class="header">
                <div class="container">
                    <table width="100%" style="table-layout:fixed;">
                        <tr>
                            <td width="50%">
                                <img src="{{ systemWideLogo() }}" width="400px">
                            </td>
                            <td width="50%" style="font-size: 15px">
                                <p>
                                    <span style="vertical-align:middle;">
                                        <svg width="14" height="14" viewBox="0 0 24 24">
                                            <path
                                                d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z" />
                                        </svg>
                                    </span>: {{ businessAddress() }}
                                </p>
                                <p>
                                    <span style="vertical-align:middle;">
                                        <svg width="14" height="14" viewBox="0 0 24 24">
                                            <path
                                                d="M6.62 10.79a15.53 15.53 0 006.59 6.59l2.2-2.2a1 1 0 011.01-.24c1.11.37 2.31.57 3.57.57a1 1 0 011 1V21a1 1 0 01-1 1C10.07 22 2 13.93 2 3a1 1 0 011-1h3.5a1 1 0 011 1c0 1.26.2 2.46.57 3.57a1 1 0 01-.25 1.01l-2.2 2.21z" />
                                        </svg>
                                    </span>: {{ businessPhone() }}
                                </p>
                                <p>
                                    <span style="vertical-align:middle;">
                                        <svg width="14" height="14" viewBox="0 0 24 24">
                                            <path
                                                d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 2v.01L12 13 4 6.01V6h16zM4 18V8.24l7.38 5.46a1 1 0 001.24 0L20 8.24V18H4z" />
                                        </svg>
                                    </span>: {{ businessEmail() }}
                                </p>
                            </td>
                        </tr>
                    </table>

                    <h3 style="text-transform: uppercase; font-weight: bold; color: red; margin: 10px">
                        Quote
                    </h3>
                </div>
            </div>
            {{-- ############################################ --}}
            <table width="100%" style="table-layout:fixed; font-size:12px">
                <tr>
                    <td width="50%" valign="top">
                        {{-- <p style="padding-bottom: 15px">
                            <span style="font-weight: bold">Date printed:</span>
                            {{ now('Africa/Lagos')->format('l, d F, Y h:i:s A') }}
                        </p> --}}
                        <p
                            style="background-color:#145BD9; padding:10px; margin-top:10px; margin-bottom:5px; color:white; font-weight:bold;">
                            <br>
                            &nbsp;&nbsp;Customer&nbsp;&nbsp;
                        </p>
                        <p>
                            <span style="font-weight: bold; padding-top:50px">Name:</span>
                            @php
                                $customer = $data['quote']->theCustomer;

                                $firstName = $customer->first_name ?? ($data['quote']->customer_name ?? '');
                                $middleName = $customer->middle_name ?? '';
                                $lastName = $customer->last_name ?? '';
                                $email = $customer->email1 ?? ($quote->email ?? '');
                                $phone = $customer->phone1 ?? ($quote->phone ?? '');
                            @endphp

                            {{ trim($firstName . ' ' . $middleName . ' ' . $lastName) }}
                        </p>
                        <p>
                            <span style="font-weight: bold; padding-top:50px">Phone:</span>
                            {{ $data['quote']->theCustomer
                                ? $data['quote']->theCustomer->phone1 .
                                    ($data['quote']->theCustomer->phone2 ? ' / ' . $data['quote']->theCustomer->phone2 : '')
                                : $data['quote']->customer_phone }}
                        </p>
                        <p>
                            <span style="font-weight: bold; padding-top:50px">Email:</span>
                            {{ $data['quote']->theCustomer
                                ? $data['quote']->theCustomer->email1 .
                                    ($data['quote']->theCustomer->email2 ? ' / ' . $data['quote']->theCustomer->email2 : '')
                                : $data['quote']->customer_email }}
                        </p>
                    </td>
                    <td width="50%" valign="top">

                        <p>
                            <span style="font-weight: bold">Quote Number:</span>
                            {{ $data['quote']->quote_number }}
                        </p>
                        <p>
                            <span style="font-weight: bold">Issue Date:</span>
                            {{ \Carbon\Carbon::parse($data['quote']->issue_date)->format('l, d F, Y h:i:s A') }}
                        </p>
                        <p>
                            <span style="font-weight: bold">Valid Until:</span>
                            {{ \Carbon\Carbon::parse($data['quote']->valid_until)->format('l, d F, Y h:i:s A') }}
                        </p>
                    </td>
                </tr>
            </table>
            {{-- ################################### --}}

            <table style="margin-top: 5px; width: 100%">
                <thead class="justify-content-center text-center align-middle">
                    <tr style="border: 1px solid white; justify-content: center; text-align: center;">
                        <th scope="col"
                            style="background: #145BD9; padding: 10px; margin: 10px; color: white; border: 1px solid white; text-align: center;">
                            #
                        </th>
                        <th scope="col"
                            style="background: #145BD9; padding: 10px; margin: 10px; color: white; border: 1px solid white; text-align: center;">
                            Description of Goods
                        </th>
                        <th scope="col"
                            style="background: #145BD9; padding: 10px; margin: 10px; color: white; border: 1px solid white; text-align: center;">
                            Rate
                        </th>
                        <th scope="col"
                            style="background: #145BD9; padding: 10px; margin: 10px; color: white; border: 1px solid white; text-align: center;">
                            Quanty
                        </th>
                        <th scope="col"
                            style="background: #145BD9; padding: 10px; margin: 10px; color: white; border: 1px solid white; text-align: center;">
                            Amount
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if ($data['quote']->items->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center text-danger fw-bold fs-5">
                                {{ t('app.sales.noProductsHere') }}
                            </td>
                        </tr>
                    @else
                        @foreach ($data['quote']->items as $item)
                            <tr>
                                <td style="text-align: center">
                                    {{ $loop->iteration }}
                                </td>
                                @if ($item->variant)
                                    <td>
                                        @php
                                            $locale = currentLocale();
                                            $field = 'name_' . $locale;
                                            $variantName = !empty($item->variant->$field)
                                                ? $item->variant->$field
                                                : $item->variant->name;
                                        @endphp
                                        {{ $variantName }}
                                        =>
                                        {{ $item->variant->sku }}
                                    </td>
                                @else
                                    <td>
                                        @php
                                            $locale = currentLocale();
                                            $field = 'name_' . $locale;
                                            $productName = !empty($item->product->$field)
                                                ? $item->product->$field
                                                : $item->product->name;
                                        @endphp

                                        {{ $productName }}
                                        <a href="{{ route('viewProduct', ['slug' => $item->product->slug]) }}"
                                            target="_blank" class="badge bg-primary text-white"> <i
                                                class="fas fa-eye"></i>
                                        </a>
                                        =>
                                        {{ $item->product->sku }}
                                    </td>
                                @endif

                                <td style="text-align: right">
                                    ₦{{ number_format($item->unit_price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td style="text-align: right">
                                    @php
                                        $total = $item->unit_price * $item->quantity;
                                    @endphp
                                    ₦{{ number_format($total, 2) }}
                                </td>
                            </tr>
                        @endforeach
                        {{-- summations start --}}
                        <tr style="font-size: 20px">
                            <td colspan="2" style="padding-top:10px">
                                <h5>Sub-Total</h5>
                            </td>
                            <td colspan="3" style="text-align: right">
                                @php
                                    $subTotal = $data['quote']->items->sum(function ($item) {
                                        return $item->unit_price * $item->quantity;
                                    });
                                @endphp

                                <h4>₦{{ number_format($subTotal, 2) }}</h4>
                            </td>
                        </tr>
                        <tr style="font-size: 20px">
                            <td colspan="2">
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <h5 style="margin:0;">Discount(s)</h5>

                                    <span>
                                        @if ($data['quote']->discounts->isNotEmpty())
                                            @foreach ($data['quote']->discounts as $discount)
                                                {{ $discount->discount->discount_name }} =>

                                                @switch($discount->discount->discount_method)
                                                    @case('percentage')
                                                        {{ $discount->discount->discount_rate }}%
                                                    @break

                                                    @case('fixed_amount')
                                                        ₦{{ number_format($discount->discount->discount_rate, 2) }}
                                                    @break
                                                @endswitch

                                                @if (!$loop->last)
                                                    +
                                                @endif
                                            @endforeach
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <td colspan="3" style="text-align: right">
                                @php
                                    $totalDiscount = 0;

                                    if ($data['quote']->discounts->isNotEmpty()) {
                                        foreach ($data['quote']->discounts as $discount) {
                                            if ($discount->discount->discount_method == 'percentage') {
                                                $totalDiscount +=
                                                    ($discount->discount->discount_rate / 100) * $subTotal;
                                            }

                                            if ($discount->discount->discount_method == 'fixed_amount') {
                                                $totalDiscount += $discount->discount->discount_rate;
                                            }
                                        }
                                    }
                                @endphp

                                <h4>₦{{ number_format($totalDiscount, 2) }}</h4>
                            </td>
                        </tr>
                        <tr style="font-size: 20px">
                            <td colspan="2">
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <h5 style="margin:0;">Taxe(s)</h5>

                                    <span>
                                        @if ($data['quote']->taxes->isNotEmpty())
                                            @foreach ($data['quote']->taxes as $tax)
                                                {{ $tax->tax->tax_name }} =>

                                                @switch($tax->tax->tax_method)
                                                    @case('percentage')
                                                        {{ $tax->tax->tax_rate }}%
                                                    @break

                                                    @case('fixed_amount')
                                                        ₦{{ number_format($tax->tax->tax_rate, 2) }}
                                                    @break
                                                @endswitch

                                                @if (!$loop->last)
                                                    +
                                                @endif
                                            @endforeach
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <td colspan="3" style="text-align: right">
                                @php
                                    $balanceAfterDiscount = max(0, $subTotal - $totalDiscount);
                                    $totalTax = 0;

                                    if ($data['quote']->taxes->isNotEmpty()) {
                                        foreach ($data['quote']->taxes as $tax) {
                                            if ($tax->tax->tax_method == 'percentage') {
                                                $totalTax += ($tax->tax->tax_rate / 100) * $balanceAfterDiscount;
                                            }

                                            if ($tax->tax->tax_method == 'fixed_amount') {
                                                $totalTax += $tax->tax->tax_rate;
                                            }
                                        }
                                    }
                                @endphp

                                <h4>₦{{ number_format($totalTax, 2) }}</h4>
                            </td>
                        </tr>
                        <tr style="font-size: 20px">
                            <td colspan="2">
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <h4 style="margin:0;">Grand Total</h4>
                                </div>
                            </td>
                            <td colspan="3" style="text-align: right">
                                @php
                                    $grandTotal = $balanceAfterDiscount + $totalTax;
                                @endphp

                                <h3>₦{{ number_format($grandTotal, 2) }}</h3>
                            </td>
                        </tr>
                        {{-- summations end --}}
                    @endif
                </tbody>
            </table>
            <br>
            <p style="font-weight: bold"><span style="text-transform: uppercase">Payment Instructions:</span> You may
                settle this quotation using any of the following methods:</p>
            <ul>
                <li>
                    Log in to your customer dashboard and complete payment online.
                </li>
                <li>
                    Make a bank transfer to any of the account(s) provided below:
                    <ul>
                        @foreach (activeBankAccounts() as $account)
                            <li>
                                <strong>{{ $account->bank_name }}</strong> —
                                {{ $account->account_name }}
                                ({{ $account->account_number }})
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li>
                    Contact our team if you prefer alternative payment arrangements.
                </li>
                <li>
                    Please reference Quote #{{ $data['quote']->quote_number }} when making payment.
                </li>
            </ul>
            @if ($data['quote']->terms_and_conditions || $data['quote']->customer_note)
                <p style="font-weight: bold"><span style="text-transform: uppercase">Notes</span> </p>
                <span style="font-weight: bold">Terms & Conditions </span><br>
                @if ($data['quote']->terms_and_conditions)
                    {!! $data['quote']->terms_and_conditions !!}
                @endif
                @if ($data['quote']->customer_note)
                    <span style="font-weight: bold">Additional Note</span> <br>
                    {!! $data['quote']->customer_note !!}
                @endif
            @endif

        </div>
        {{-- bg end --}}
    </div>


</body>

</html>



<style>
    body {
        margin: 0;
        padding: 0;
    }


    .content {
        padding-left: 30px;
        padding-right: 30px;
        font-size: 14px;
        /* margin-bottom: 50px; */
    }

    .header {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
        /* Set an appropriate height for vertical centering */
    }

    .container {
        text-align: center;
    }
</style>
