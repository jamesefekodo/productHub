<?php

namespace App\Models\Payment;

use App\Models\Payment\FlutterwaveApiSetting;
use App\Models\Payment\PaystackApiSetting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;
    protected $table = 'payment_methods';
    protected $fillable = [
        'slug',
        'name',
        'logo',
        'version',
        'status',
    ];

    public function PaystackApi(){
        return $this->belongsTo(PaystackApiSetting::class, 'slug', 'payment_method_slug');
    }
    public function FlutterwaveApi(){
        return $this->belongsTo(FlutterwaveApiSetting::class, 'slug', 'payment_method_slug');
    }
}
