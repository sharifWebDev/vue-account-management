<?php

namespace App\Services;

use App\Contracts\BankServiceInterface;
use App\Models\Bank;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BankService implements BankServiceInterface
{
    public function __construct(protected Bank $model) {}

    public function get(Request $request): LengthAwarePaginator
    {
        $length = $request->input('length', 10);
        $search = $request->input('search');
        $status = $request->input('status');

        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_direction', 'desc');

        $columns = [
            'id',
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

        if (! in_array($sortBy, $columns)) {
            $sortBy = 'id';
        }

        if (! in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query = $this->model->query();

        $query
            ->when($search && is_string($search), function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    foreach ((new Bank)->getFillable() as $column) {
                        $q->orWhere($column, 'like', "%{$search}%");
                    }
                });
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            });
        $query->orderBy($sortBy, $sortOrder);

        return $length === -1
            ? $query->paginate($query->get()->count())
            : $query->paginate($length);
    }

    /**
     * Get all Teams for form dropdowns.
     */
    public function getAll(): Collection
    {
        return $this->model->get();
    }

    public function find(int $id): Model
    {
        $bank = $this->model->findOrFail($id);

        if (! $bank) {
            throw new Exception('featureName not found.');
        }

        return $bank;
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

        $sortBy = $request->input('sort_by', 'id');
        $sortDirection = strtolower($request->input('sort_direction', 'asc'));

        // Prevent array / invalid column crash
        if (! is_string($sortBy) || ! in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'id';
        }

        $sortDirection = $sortDirection === 'desc' ? 'desc' : 'asc';

        $query = $this->model::onlyTrashed();

        // Search
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('bank_name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('fax', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('website', 'like', "%{$search}%");
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
