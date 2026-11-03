<?php

namespace App\Models\Address;

use App\Models\Address\State;
use App\Models\Address\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;
    protected $table = 'cities';
    protected $fillable = [
        'country_id',
        'state_id',
        'name',
        'status',

    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
