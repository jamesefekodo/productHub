<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment\BankAccount;
use App\Models\Payment\Discount;
use App\Models\Payment\FlutterwaveApiSetting;
use App\Models\Payment\PaymentMethod;
use App\Models\Payment\PaystackApiSetting;
use App\Models\Payment\Tax;
use Illuminate\Http\Request;

class PaymentSettingsController extends Controller
{
    public function paymentMethods(Request $request)
    {
        $paymentMethods = PaymentMethod::orderBy('name')->get();

        $data = [
            'paymentMethods' => $paymentMethods
        ];

        return view('users.admin.payments.settings.paymentMethods.paymentMethods', compact('data'));
    }
    public function updatePaymentMethodSubmit(Request $request, $slug)
    {
        $request->validate([
            'status' => 'required',
        ]);

        $method = PaymentMethod::where('slug', $slug)->first();
        if ($method) {
            $method->status = trim($request->status);
            $method->version = trim($request->version);
            $method->save();


            if ($request->slug == 'paystack') {
                $paystack = PaystackApiSetting::first();
                $paystack->secret_key = $request->secret_key;
                $paystack->public_key = $request->public_key;
                $paystack->save();
            }
            if ($request->slug == 'flutterwave') {
                $flutterwave = FlutterwaveApiSetting::first();
                $flutterwave->secret_key = $request->secret_key;
                $flutterwave->public_key = $request->public_key;
                $flutterwave->encryption_key = $request->encryption_key;
                $flutterwave->save();
            }

            return redirect()->back()->with('success', t('notifications.done'));
        } else {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
    }
    public function bankAccounts()
    {
        $bankAccounts = BankAccount::orderBy('bank_name')->get();

        $data = [
            'bankAccounts' => $bankAccounts
        ];

        return view('users.admin.payments.settings.bankAccounts.bankAccounts', compact('data'));
    }
    public function createNewBankAccount(Request $request)
    {
        $request->validate([
            'bank_name' => 'required',
            'account_number' => 'required',
            'account_name' => 'required',
            'currency' => 'required'
        ]);

        $bankAccount = new BankAccount();
        $bankAccount->bank_name = trim($request->bank_name);
        $bankAccount->account_number = trim($request->account_number);
        $bankAccount->account_name = trim($request->account_name);
        $bankAccount->currency = trim($request->currency);
        $bankAccount->save();

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function updateBankAccountSubmit($id, Request $request)
    {
        $request->validate([
            'bank_name' => 'required',
            'account_number' => 'required',
            'account_name' => 'required',
            'currency' => 'required',
            'is_active' => 'required',
        ]);

        $account = BankAccount::where('id', $id)->first();
        if (!$account) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $account->update(
            [
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'account_name' => $request->account_name,
                'currency' => $request->currency,
                'is_active' => $request->is_active,
            ]
        );
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function taxes(Request $request)
    {
        $taxes = Tax::orderBy('tax_name')->get();

        $data = [
            'taxes' => $taxes,
        ];

        return view('users.admin.payments.settings.taxes.taxes', compact('data'));
    }
    public function createTaxAccount(Request $request)
    {
        $request->validate([
            'tax_name' => 'required',
            'tax_method' => 'required',
            'tax_rate' => 'required',
            'is_default' => 'required',
        ]);

        $tax = new Tax();
        $tax->tax_name = $request->tax_name;
        $tax->tax_method = $request->tax_method;
        $tax->tax_rate = $request->tax_rate;
        $tax->is_default = $request->is_default;
        $tax->save();
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function updateTaxSubmit($id, Request $request)
    {
        $request->validate([
            'tax_name' => 'required',
            'tax_method' => 'required',
            'tax_rate' => 'required',
            'is_active' => 'required',
            'is_default' => 'required',
        ]);


        $tax = Tax::where('id', $id)->first();
        if (!$tax) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $tax->update(
            [
                'tax_name' => $request->tax_name,
                'tax_method' => $request->tax_method,
                'tax_rate' => $request->tax_rate,
                'is_active' => $request->is_active,
                'is_default' => $request->is_default,
            ]
        );
        return redirect()->back()->with('success', t('notifications.done'));
    }

    public function discounts()
    {
        $discounts = Discount::orderBy('discount_name')->get();

        $data = [
            'discounts' => $discounts,
        ];

        return view('users.admin.payments.settings.discounts.discounts', compact('data'));
    }
    public function createDiscountSubmit(Request $request)
    {
        $request->validate([
            'discount_name' => 'required',
            'discount_method' => 'required',
            'discount_rate' => 'required',
            'is_default' => 'required',
        ]);

        $discount = new Discount();
        $discount->discount_name = $request->discount_name;
        $discount->discount_method = $request->discount_method;
        $discount->discount_rate = $request->discount_rate;
        $discount->is_default = $request->is_default;
        $discount->save();
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function updateDiscountSubmit($id, Request $request)
    {
        $request->validate([
            'discount_name' => 'required',
            'discount_method' => 'required',
            'discount_rate' => 'required',
            'is_active' => 'required',
            'is_default' => 'required',
        ]);


        $discount = Discount::where('id', $id)->first();
        if (!$discount) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $discount->update(
            [
                'discount_name' => $request->discount_name,
                'discount_method' => $request->discount_method,
                'discount_rate' => $request->discount_rate,
                'is_active' => $request->is_active,
                'is_default' => $request->is_default,
            ]
        );
        return redirect()->back()->with('success', t('notifications.done'));
    }
}
