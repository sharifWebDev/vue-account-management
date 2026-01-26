<?php

namespace App\Services;

use App\Contracts\TransactionServiceInterface;
use App\Models\Transaction;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TransactionService implements TransactionServiceInterface
{
    public function __construct(protected Transaction $model) {}

    public function get(Request $request): LengthAwarePaginator
    {
        $length = $request->input('length') ?? $request->input('per_page', 10);
        $search = $request->input('search');
        $status = $request->input('status');
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_direction', 'desc');
        $accountNumber = $request->input('account_number');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $columns = [
            'id',
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
            'bounce_details',
            'status',
        ];

        if (!in_array($sortBy, $columns)) {
            $sortBy = 'id';
        }

        if (!in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query = $this->model->query()
            ->with(['account', 'user', 'bounceTransaction'])
            ->where('status', '!=', '2');

        // Search
        if ($search && is_string($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('type', 'like', "%{$search}%")
                    ->orWhere('details', 'like', "%{$search}%")
                    ->orWhere('receive_from', 'like', "%{$search}%")
                    ->orWhere('payment_to', 'like', "%{$search}%");
            });
        }

        if ($accountNumber) {
            $query->whereHas('account', function ($q) use ($accountNumber) {
                $q->where('account_number', $accountNumber);
            });
        }

        // Date range filter
        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }

        // Status filter
        if ($status !== null) {
            $query->where('status', $status);
        }

        $query->orderBy($sortBy, $sortOrder);

        return $length === -1
            ? $query->paginate($query->get()->count())
            : $query->paginate($length);
    }

    public function getAll(): Collection
    {
        return $this->model->where('status', '!=', '2')->get();
    }

    public function find(int $id): Model
    {
        $transaction = $this->model->with(['account', 'user', 'bounceTransaction'])
            ->findOrFail($id);

        if (!$transaction) {
            throw new Exception('Transaction not found.');
        }

        return $transaction;
    }

    public function store(array $data): ?Model
    {
        return DB::transaction(function () use ($data) {
            $transaction = $this->model->create($data);

            // Handle bounce transaction creation if needed
            if (isset($data['create_bounce_transaction']) && $data['create_bounce_transaction']) {
                $bounceData = [
                    'date' => $data['date'],
                    'type' => 'Cheque Bounce',
                    'details' => $data['bounce_details'] ?? 'Cheque Bounced',
                    'deposit' => $data['type'] === 'Deposit' ? -abs($data['deposit'] ?? 0) : 0,
                    'withdraw' => $data['type'] === 'Withdraw' ? -abs($data['withdraw'] ?? 0) : 0,
                    'account_id' => $data['account_id'],
                    'user_id' => $data['user_id'],
                    'status' => $data['status'] ?? 1,
                    'bounce_transaction_id' => $transaction->id,
                ];

                $bounceTransaction = $this->model->create($bounceData);
                $transaction->update(['bounce_transaction_id' => $bounceTransaction->id]);
            }

            return $transaction;
        });
    }

    public function update(int $id, array $data): ?Model
    {
        return DB::transaction(function () use ($id, $data) {
            $transaction = $this->find($id);

            // Handle bounce transaction updates
            if ($transaction->bounce_transaction_id) {
                if (!isset($data['bounce_transaction_id']) || empty($data['bounce_transaction_id'])) {
                    // Delete existing bounce transaction
                    $this->model->where('id', $transaction->bounce_transaction_id)->delete();
                    $data['bounce_transaction_id'] = null;
                } else {
                    // Update bounce transaction
                    $bounceData = [
                        'date' => $data['date'] ?? $transaction->date,
                        'details' => $data['bounce_details'] ?? $transaction->bounceTransaction->details,
                        'deposit' => $data['type'] === 'Deposit' ? -abs($data['deposit'] ?? 0) : 0,
                        'withdraw' => $data['type'] === 'Withdraw' ? -abs($data['withdraw'] ?? 0) : 0,
                    ];
                    $this->model->where('id', $transaction->bounce_transaction_id)->update($bounceData);
                }
            } elseif (isset($data['bounce_transaction_id']) && $data['bounce_transaction_id']) {
                // Link existing bounce transaction
                $bounceTransaction = $this->model->find($data['bounce_transaction_id']);
                if ($bounceTransaction) {
                    $data['bounce_transaction_id'] = $bounceTransaction->id;
                }
            }

            $transaction->update($data);
            return $transaction;
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $transaction = $this->find($id);

            if ($transaction->bounce_transaction_id) {
                $bounceTransaction = $this->model->find($transaction->bounce_transaction_id);
                if ($bounceTransaction) {
                    $bounceTransaction->delete();
                }
            }

            return (bool) $transaction->delete();
        });
    }

    public function restore(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $transaction = $this->model->withTrashed()->findOrFail($id);

            if ($transaction->bounce_transaction_id) {
                $bounceTransaction = $this->model->withTrashed()
                    ->where('id', $transaction->bounce_transaction_id)
                    ->first();
                if ($bounceTransaction) {
                    $bounceTransaction->restore();
                }
            }

            return (bool) $transaction->restore();
        });
    }

    public function forceDelete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $transaction = $this->model->withTrashed()->findOrFail($id);

            if ($transaction->bounce_transaction_id) {
                $bounceTransaction = $this->model->withTrashed()
                    ->where('id', $transaction->bounce_transaction_id)
                    ->first();
                if ($bounceTransaction) {
                    $bounceTransaction->forceDelete();
                }
            }

            return (bool) $transaction->forceDelete();
        });
    }

    public function softDelete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $transaction = $this->find($id);

            if ($transaction->bounce_transaction_id) {
                $bounceTransaction = $this->model->find($transaction->bounce_transaction_id);
                if ($bounceTransaction) {
                    $bounceTransaction->update(['status' => '2']);
                }
            }

            $transaction->update(['status' => '2']);
            return true;
        });
    }

    public function undoSoftDelete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $transaction = $this->model->where('status', '2')->findOrFail($id);

            if ($transaction->bounce_transaction_id) {
                $bounceTransaction = $this->model->where('status', '2')
                    ->where('id', $transaction->bounce_transaction_id)
                    ->first();
                if ($bounceTransaction) {
                    $bounceTransaction->update(['status' => '1']);
                }
            }

            $transaction->update(['status' => '1']);
            return true;
        });
    }

    public function changeStatus(int $id, string $status): bool
    {
        $transaction = $this->find($id);
        $transaction->status = $status === 'active' ? 1 : 0;
        return $transaction->save();
    }

    public function trash(Request $request): LengthAwarePaginator
    {
        $length = (int) $request->input('per_page', 10);
        $search = $request->input('search');

        $query = $this->model::onlyTrashed()
            ->with(['account', 'user']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('type', 'like', "%{$search}%")
                    ->orWhere('details', 'like', "%{$search}%")
                    ->orWhere('receive_from', 'like', "%{$search}%")
                    ->orWhere('payment_to', 'like', "%{$search}%");
            });
        }

        $query->orderBy('deleted_at', 'desc');

        if ($length === -1) {
            return $query->paginate($query->count());
        }

        return $query->paginate($length);
    }

    public function getTransactionHistory(Request $request): LengthAwarePaginator
    {
        $query = $this->model->with(['account', 'user'])
            ->where('status', '!=', '2');

        if ($request->filled('account_number')) {
            $query->whereHas('account', function ($q) use ($request) {
                $q->where('account_number', $request->account_number);
            });
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('date', [
                $request->date_from,
                $request->date_to
            ]);
        }

        return $query->orderBy('date', 'desc')
            ->paginate($request->input('per_page', 20));
    }

    public function getBalance(string $accountNumber): float
    {
        return $this->model->whereHas('account', function ($q) use ($accountNumber) {
            $q->where('account_number', $accountNumber);
        })
            ->where('status', '!=', '2')
            ->sum(DB::raw('COALESCE(deposit, 0) - COALESCE(withdraw, 0)'));
    }
}
