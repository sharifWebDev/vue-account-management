<?php

namespace App\Models;

use App\Models\Bank;
use App\Models\User;
use App\Models\Account;
use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'created_by',
        'updated_by',
        'deleted_at',
        'created_at',
        'updated_at',
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

    public static function getActive()
    {
        return self::where('status', '!=', '2')->get();
    }

    public static function getTrashed()
    {
        return self::onlyTrashed()->get();
    }

    public function bounceTransaction()
    {
        return $this->belongsTo(Transaction::class, 'bounce_transaction_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
