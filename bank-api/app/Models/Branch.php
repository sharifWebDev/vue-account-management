<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'branches';

    protected $fillable = [
        'branch_name',
        'bank_id',
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
        return $this->hasMany(Account::class, 'branch_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
}
