<?php

namespace App\Models\Products;

use App\Models\Products\Category;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $table = 'sub_categories';
    protected $fillable = [
        'slug',
        'sub_category',
        'sub_category_zh',
        'sub_category_fr',
        'category',
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
    public function theCategory()
    {
        return $this->belongsTo(Category::class, 'category', 'slug');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'sub_category_id', 'id');
    }
    public function activeProducts()
    {
        return $this->hasMany(Product::class, 'sub_category_id', 'id')->where('is_active', true);
    }
}
