<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLoginRecord extends Model
{
    use HasFactory;
    protected $table = 'user_login_records';
    protected $fillable = [
        'username',
        'session_id',
        'logged_in_at',
        'mfa_verified_at',
        'logged_out_at',
        'ip_address',
        'device_type',
    ];
}
