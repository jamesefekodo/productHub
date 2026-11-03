<?php

namespace App\Models\Products;

use App\Models\Products\Product;
use App\Models\Products\ProductVariant;
use App\Models\Products\Quote;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealQuoteItem extends Model
{
    use HasFactory;
    protected $table = 'real_quote_items';
    protected $fillable = [
        'quote_number',
        'product_slug',
        'variant_slug',
        'quantity',
        'unit_price'
    ];


    public function quote()
    {
        return $this->belongsTo(Quote::class, 'quote_number', 'quote_number');
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
