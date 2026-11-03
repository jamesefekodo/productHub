<?php

namespace App\Models\Products;

use App\Models\Payment\Tax;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryQuoteTax extends Model
{
    use HasFactory;
    protected $table = 'temporary_quote_taxes';
    protected $fillable = [
        'quote_number',
        'tax_id',
    ];

    public function quote()
    {
        return $this->belongsTo(TemporaryQuote::class, 'quote_number', 'quote_number');
    }
    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id', 'id');
    }
}
