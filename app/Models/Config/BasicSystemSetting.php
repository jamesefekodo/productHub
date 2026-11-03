<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicSystemSetting extends Model
{
    use HasFactory;
    protected $table = 'basic_system_settings';
    protected $fillable = [
        'maintenance_mode',
        'logo',
        'logo_full',
        'app_slogan',
        'about_us',
        'about_us_image',
        'display_price',
        'phone',
        'email',
        'address',
        'quote_t_and_c_template'
    ];
}
