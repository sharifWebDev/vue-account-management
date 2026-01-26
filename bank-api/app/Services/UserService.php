<?php

namespace App\Services;

use App\Contracts\UserServiceInterface;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService implements UserServiceInterface
{
    public function __construct(protected User $model) {}

    public function get(Request $request): LengthAwarePaginator
    {
        $length = $request->input('length') ?? $request->input('per_page', 10);
        $search = $request->input('search');
        $status = $request->input('status');

        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_direction', 'desc');

        $columns = [
            'id',
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
            'user_type',
            'created',
            'modified',
            'status',
        ];

        if (! in_array($sortBy, $columns)) {
            $sortBy = 'id';
        }


        if ($sortBy == 'company_id') {
            $sortBy = 'company_ids';
        }

        if (! in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query = $this->model->query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('company', function ($q) use ($search) {
                        $q->where('company_name', 'like', "%{$search}%");
                    });
            });
        }

        $query->orderBy($sortBy, $sortOrder);

        return $length == -1
            ? $query->paginate($query->count())
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
        $user = $this->model->findOrFail($id);

        if (! $user) {
            throw new Exception('featureName not found.');
        }

        return $user;
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

        $sortBy = $request->input('sort_by', 'id');
        $sortDirection = strtolower($request->input('sort_direction', 'asc'));

        if ($sortBy == 'company_id') {
            $sortBy = 'company_ids';
        }

        if (! is_string($sortBy) || ! in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'id';
        }

        $sortDirection = $sortDirection === 'desc' ? 'desc' : 'asc';

        $query = $this->model::onlyTrashed();

        // Search
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('user_type', 'like', "%{$search}%")
                    ->orWhere('date_of_birth', 'like', "%{$search}%")
                    ->orWhere('joining_date', 'like', "%{$search}%");
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
