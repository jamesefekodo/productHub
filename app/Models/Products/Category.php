<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = [
        'slug',
        'category',
        'category_zh',
        'category_fr',
        'meta_title',
        'meta_title_zh',
        'meta_title_fr',
        'meta_description',
        'meta_description_zh',
        'meta_description_fr',
        'image',
        'is_active',
        'is_featured',
    ];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'category', 'slug');
    }
    public function activeSubCategories()
    {
        return $this->hasMany(SubCategory::class, 'category', 'slug')
            ->where('is_active', true);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class, 'category_id', 'id')->where('is_active', true);
    }
}
