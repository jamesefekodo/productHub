<?php

namespace App\Models\Products;

use App\Models\Payment\Discount;
use App\Models\Products\Quote;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealQuoteDiscount extends Model
{
    use HasFactory;
    protected $table = 'real_quote_discounts';
    protected $fillable = [
        'quote_number',
        'discount_id',
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class, 'quote_number', 'quote_number');
    }
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id', 'id');
    }
}




