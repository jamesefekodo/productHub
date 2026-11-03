<?php

namespace App\Models\Products;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model
{
    use HasFactory;
    protected $table = 'quote_items';
    protected $fillable = [
        'quote_request_slug',
        'product_slug',
        'variant_slug',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_slug', 'slug');
    }
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_slug', 'slug');
    }
}
