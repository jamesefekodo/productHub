<?php

namespace App\Models\Products;

use App\Models\Products\QuoteRequest;
use App\Models\Products\RealQuoteDiscount;
use App\Models\Products\RealQuoteItem;
use App\Models\Products\RealQuoteTax;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;
    protected $table = 'quotes';
    protected $fillable = [
        'request_slug',
        'quote_number',
        'customer',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'address',
        'subtotal',
        'total_tax',
        'total_discount',
        'grand_total',
        'currency',
        'is_tax_inclusive',
        'issue_date',
        'valid_until',
        'sent_at',
        'viewed_at',
        'accepted_at',
        'declined_at',
        'converted_at',
        'internal_note',
        'customer_note',
        'terms_and_conditions',
        'created_by',
        'last_update_by',
        'locked',
        'status',
    ];



public function items()
    {
        return $this->hasMany(RealQuoteItem::class, 'quote_number', 'quote_number');
    }
    public function theCustomer()
    {
        return $this->belongsTo(User::class, 'customer', 'username');
    }
    public function quoteRequest()
    {
        return $this->belongsTo(QuoteRequest::class, 'request_slug', 'slug');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'username');
    }
    public function lastUpdatedBy()
    {
        return $this->belongsTo(User::class, 'last_update_by', 'username');
    }
    public function taxes()
    {
        return $this->hasMany(RealQuoteTax::class, 'quote_number', 'quote_number');
    }
    public function discounts()
    {
        return $this->hasMany(RealQuoteDiscount::class, 'quote_number', 'quote_number');
    }

}
