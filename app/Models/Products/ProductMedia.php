<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductMedia extends Model
{
    use HasFactory;
    protected $table = 'product_media';
    protected $fillable = [
        'product_slug',
        'variant_slug',
        'file_name',
        'file_type',
        'is_primary',
        'sort_order',
    ];
}
