<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseServiceInterface
{
    public function get(Request $request): LengthAwarePaginator;

    public function getAll(): Collection;

    public function find(int $id): ?Model;

    public function store(array $data): ?Model;

    public function update(int $id, array $data): ?Model;

    public function delete(int $id): bool;

    public function restore(int $id): bool;

    public function forceDelete(int $id): bool;

    public function trash(Request $request): LengthAwarePaginator;
}
