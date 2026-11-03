<?php

namespace App\Models\Products;

use App\Models\Payment\Tax;
use App\Models\Products\Quote;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealQuoteTax extends Model
{
    use HasFactory;
    protected $table = 'real_quote_taxes';
    protected $fillable = [
        'quote_number',
        'tax_id',
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class, 'quote_number', 'quote_number');
    }
    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id', 'id');
    }
}
