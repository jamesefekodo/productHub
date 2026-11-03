<?php

namespace App\Models\Products;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteRequest extends Model
{
    use HasFactory;
    protected $table = 'quote_requests';
    protected $fillable = [
        'slug',
        'username',
        'session_id',
        'name',
        'email',
        'phone',
        'status',
        'submitted_at',
        'address'
    ];

    public function items()
    {
        return $this->hasMany(QuoteItem::class, 'quote_request_slug', 'slug');
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }
}
