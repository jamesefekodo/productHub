<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;
    protected $table = 'taxes';
    protected $fillable = [
        'tax_name',
        'tax_method',
        'tax_rate',
        'is_active',
        'is_default',
    ];
}
