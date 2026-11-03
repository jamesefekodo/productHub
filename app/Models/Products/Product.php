<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'sku',
        'is_available',
        'name',
        'name_zh',
        'name_fr',
        'slug',
        'description',
        'description_zh',
        'description_fr',
        'short_description',
        'short_description_zh',
        'short_description_fr',
        'category_id',
        'sub_category_id',
        'brand_id',
        'price',
        'is_active',
        'is_featured',
        'meta_title',
        'meta_title_zh',
        'meta_title_fr',
        'meta_keywords',
        'meta_keywords_zh',
        'meta_keywords_fr',
        'meta_description',
        'meta_description_zh',
        'meta_description_fr',
        'colour_id',
    ];

    public function mediaFiles()
    {
        return $this->hasMany(ProductMedia::class, 'product_slug', 'slug')
            ->orderByDesc('is_primary')   // primary first
            ->orderBy('sort_order');      // yhen by sort order
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
    }
    public function colour()
    {
        return $this->belongsTo(Colour::class, 'colour_id', 'id');
    }
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class, 'product_slug', 'slug')
            ->whereNotNull('variant_slug')
            ->where('variant_slug', '!=', '');
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }
    public function activeVariants()
    {
        return $this->hasMany(ProductVariant::class)
            ->where('is_active', true);
    }
}
