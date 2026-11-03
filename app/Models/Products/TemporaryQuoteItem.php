<?php

namespace App\Models\Products;

use App\Models\Products\TemporaryQuote;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryQuoteItem extends Model
{
    use HasFactory;
    protected $table = 'temporary_quote_items';
    protected $fillable = [
        'quote_number',
        'product_slug',
        'variant_slug',
        'quantity',
        'unit_price'
    ];


    public function quote()
    {
        return $this->belongsTo(TemporaryQuote::class, 'quote_number', 'quote_number');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_slug', 'slug');
    }
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_slug', 'slug');
    }
}
