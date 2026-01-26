<?php

namespace App\Models;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bank extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'banks';

    protected $fillable = [
        'bank_name',
        'address',
        'phone',
        'mobile',
        'fax',
        'email',
        'website',
        'created',
        'modified',
        'status',
    ];

    protected $casts = [
        'created' => 'datetime',
        'modified' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class, 'bank_id');
    }
    public function branches()
    {
        return $this->hasMany(Branch::class, 'bank_id');
    }
}
