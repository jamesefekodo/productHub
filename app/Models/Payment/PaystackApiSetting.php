<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaystackApiSetting extends Model
{
    use HasFactory;
    protected $table = 'paystack_api_settings';
    protected $fillable = [
        'payment_method_slug',
        'secret_key',
        'public_key',
        'tax_cap',
    ];
}
