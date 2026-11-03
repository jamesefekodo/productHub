<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlutterwaveApiSetting extends Model
{
    use HasFactory;
    protected $table = 'flutterwave_api_settings';
    protected $fillable = [
        'payment_method_slug',
        'public_key',
        'secret_key',
        'encryption_key',
    ];
}
