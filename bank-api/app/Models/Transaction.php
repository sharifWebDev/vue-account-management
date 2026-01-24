<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'account_id',
        'bounce_transaction_id',
        'date',
        'type',
        'details',
        'deposit',
        'withdraw',
        'receive_from',
        'payment_to',
        'attachment',
        'bounce_details',
        'created',
        'modified',
        'status',
    ];

    protected $casts = [
        'date' => 'datetime',
        'created' => 'datetime',
        'modified' => 'datetime',
        'status' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
