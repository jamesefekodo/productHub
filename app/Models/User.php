<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Address\UserAddress;
use App\Models\Config\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'middle_name',
        'last_name',
        'account_type',
        'account_status',
        'email1',
        'email2',
        'email1_verified_at',
        'email2_verified_at',
        'phone1',
        'phone2',
        'password',
        'photo',
        'require_login_mfa',
        'email1_verification',
        'email2_verification',
        'email1_verification_code',
        'email2_verification_code',
        'email1_verification_code_expires_at',
        'email2_verification_code_expires_at',
        'gender',
        'date_of_birth',
        'nationality',
        'preferred_language',
    ];


    public function language()
    {
        return $this->belongsTo(Language::class, 'preferred_language', 'language_code');
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'username', 'username');
    }
    public function primaryAddress()
    {
        return $this->hasOne(UserAddress::class, 'username', 'username')
            ->where('is_primary', true);
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // custom functions starts here
    // static functions
    static public function getEmailCheck($email)
    {
        return User::where('email1', '=', $email)->first();
    }
    static public function getTokenSingle($remember_token)
    {
        return User::where('remember_token', '=', $remember_token)->first();
    }
}
