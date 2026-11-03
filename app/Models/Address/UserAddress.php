<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;
    protected $table = 'user_addresses';
    protected $fillable = [
        'username',
        'country',
        'state',
        'city',
        'more_details',
        'is_primary',
    ];

    public function theCountry()
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }
    public function theState()
    {
        return $this->belongsTo(State::class, 'state', 'id');
    }
     public function theCity()
    {
        return $this->belongsTo(City::class, 'city', 'id');
    }
}
