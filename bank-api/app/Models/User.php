<?php

namespace App\Models;

use App\Models\Transaction;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory, Notifiable, TwoFactorAuthenticatable;
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'phone',
        'mobile',
        'email',
        'password',
        'date_of_birth',
        'joining_date',
        'image',
        'facebook',
        'twitter',
        'instagram',
        'google_plus',
        'linkedin',
        'company_ids',
        'user_type',
        'created',
        'modified',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'company_id' => 'array',
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'two_factor_expires_at' => 'datetime',
        'last_login_at' => 'datetime',
        'last_login_ip' => 'string',
        'current_team_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'created' => 'datetime',
        'modified' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'status' => 'boolean',
    ];

    public function getUserNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_ids', 'id');
    }

    // User.php
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user', 'user_id', 'company_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }
}
