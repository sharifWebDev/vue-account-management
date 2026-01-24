<?php

namespace App\Services;

use App\Contracts\AccountServiceInterface;
use App\Models\Account;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AccountService implements AccountServiceInterface
{
    public function __construct(protected Account $model) {}

    public function get(Request $request): LengthAwarePaginator
    {
        $length = $request->input('length') ?? $request->input('per_page', 10);
        $search = $request->input('search');
        $status = $request->input('status');
        $type = $request->input('type') == 'trash';

        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_direction', 'desc');

        $columns = [
            'id',
            'company_id',
            'bank_id',
            'branch_id',
            'account_name',
            'account_number',
            'cheque_book',
            'opening_balance',
            'created',
            'modified',
            'status',
        ];

        if ($sortBy == 'branch_name') {
            $sortBy = 'branch_id';
        }
        if ($sortBy == 'bank_name') {
            $sortBy = 'bank_id';
        }
        if ($sortBy == 'company_name') {
            $sortBy = 'company_id';
        }
        if ($sortBy == 'is_active') {
            $sortBy = 'status';
        }

        if (! in_array($sortBy, $columns)) {
            $sortBy = 'id';
        }

        if (! in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query = $type ? $this->model->onlyTrashed() : $this->model->query();
        $query->with(['company', 'bank', 'branch']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('account_name', 'like', "%{$search}%")
                    ->orWhere('account_number', 'like', "%{$search}%")
                    ->orWhere('cheque_book', 'like', "%{$search}%")
                    ->orWhereHas('company', function ($q) use ($search) {
                        $q->where('company_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('bank', function ($q) use ($search) {
                        $q->where('bank_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('branch', function ($q) use ($search) {
                        $q->where('branch_name', 'like', "%{$search}%");
                    });
            });
        }

        $query->orderBy($sortBy, $sortOrder);

        return $length == -1
            ? $query->paginate($query->count())
            : $query->paginate($length);
    }

    public function getAll(): Collection
    {
        return $this->model->get();
    }

    public function find(int $id): Model
    {
        $account = $this->model->findOrFail($id);

        if (! $account) {
            throw new Exception('featureName not found.');
        }

        return $account;
    }

    public function store(array $data): ?Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): ?Model
    {
        $record = $this->find($id);
        $record->update($data);

        return $record;
    }

    public function delete(int $id): bool
    {
        $record = $this->find($id);

        return $record ? (bool) $record->delete() : false;
    }

    public function restore(int $id): bool
    {
        $record = $this->model->withTrashed()->findOrFail($id);

        return $record ? (bool) $record->restore() : false;
    }

    public function forceDelete(int $id): bool
    {
        $record = $this->model->withTrashed()->findOrFail($id);

        if ($record->transactions()->exists()) {
            throw new \Exception('Cannot delete this account as it has transactions.');
        }

        return (bool) $record->forceDelete();
    }

    public function trash(Request $request): LengthAwarePaginator
    {
        $length = (int) $request->input('per_page', 10);
        $search = $request->input('search');
        $status = $request->input('status');

        // Sorting (safe)
        $allowedSorts = [
            'id',
            'account_name',
            'account_number',
            'status',
            'created_at',
            'updated_at',
        ];

        $sortBy = $request->input('sort_by', 'id');
        $sortDirection = strtolower($request->input('sort_direction', 'asc'));

        // Prevent array / invalid column crash
        if (! is_string($sortBy) || ! in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'id';
        }

        $sortDirection = $sortDirection === 'desc' ? 'desc' : 'asc';

        $query = $this->model::onlyTrashed()
            ->with(['company', 'bank', 'branch']);

        // Search
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('account_name', 'like', "%{$search}%")
                    ->orWhere('account_number', 'like', "%{$search}%")
                    ->orWhereHas('company', function ($q2) use ($search) {
                        $q2->where('company_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('bank', function ($q2) use ($search) {
                        $q2->where('bank_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('branch', function ($q2) use ($search) {
                        $q2->where('branch_name', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($status !== null) {
            $query->where('status', $status);
        }

        // Apply sorting
        $query->orderBy($sortBy, $sortDirection);

        // Pagination
        if ($length === -1) {
            return $query->paginate($query->count());
        }

        return $query->paginate($length);
    }
}
