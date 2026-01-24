<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'companies';

    protected $fillable = [
        'company_name',
        'address',
        'phone',
        'mobile',
        'email',
        'website',
        'logo',
        'created',
        'modified',
        'status',
        'created_at',
        'updated_at',

    ];

    protected $casts = [
        'created' => 'datetime',
        'modified' => 'datetime',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class, 'company_id');
    }
}
