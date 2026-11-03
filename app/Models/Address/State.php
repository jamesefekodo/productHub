<?php

namespace App\Models\Address;

use App\Models\Address\City;
use App\Models\Address\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory;
    protected $table = 'states';
    protected $fillable = [
        'country_id',
        'name',
        'state_code',
        'status',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
