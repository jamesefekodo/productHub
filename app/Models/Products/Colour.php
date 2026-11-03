<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Colour extends Model
{
    use HasFactory;
    protected $table = 'colours';
    protected $fillable = [
        'hex_code',
        'name',
        'name_zh',
        'name_fr',
    ];
}
