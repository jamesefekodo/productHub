<?php

namespace App\Models\Products;

use App\Models\Products\Attribute;
use App\Models\Products\AttributeValue;
use App\Models\Products\Product;
use App\Models\Products\ProductVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;
    protected $table = 'product_attributes';
    protected $fillable = [
        'product_slug',
        'variant_slug',
        'attribute_id',
        'value_id',
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'id');
    }
    public function value()
    {
        return $this->belongsTo(AttributeValue::class, 'value_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_slug', 'slug');
    }
    public function variantProduct()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_slug', 'slug');
    }
}
