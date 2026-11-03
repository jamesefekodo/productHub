<?php

namespace App\Models\Products;

use App\Models\Products\Colour;
use App\Models\Products\Product;
use App\Models\Products\ProductAttribute;
use App\Models\Products\ProductMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    protected $table = 'product_variants';
    protected $fillable = [
        'slug',
        'name',
        'name_zh',
        'name_fr',
        'product_id',
        'sku',
        'price',
        'is_available',
        'is_active',
        'colour_id',
    ];
    public function mediaFile()
    {
        return $this->belongsTo(ProductMedia::class, 'slug', 'variant_slug');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function colour()
    {
        return $this->belongsTo(Colour::class, 'colour_id', 'id');
    }
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class, 'variant_slug', 'slug');
    }
}
