<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappSupportLine extends Model
{
    use HasFactory;
    protected $table = 'whatsapp_support_lines';
    protected $fillable = [
        'name',
        'number',
        'status',
    ];
}
