<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageSlider extends Model
{
    use HasFactory;
    protected $table = 'homepage_sliders';
    protected $fillable = [
        'background_image',
        'header',
        'header_zh',
        'header_fr',
        'link',
        'order',
        'description',
        'description_zh',
        'description_fr',
    ];
}
