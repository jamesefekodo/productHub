<?php

namespace App\Models\Address;

use App\Models\Address\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;
    protected $table = 'countries';
    protected $fillable = [
        'name',
        'phone_code',
        'status',
    ];

    public function states()
    {
        return $this->hasMany(State::class, 'country_id', 'id');
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'country_id', 'id');
    }
}
