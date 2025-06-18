<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{
    public function create(array $data = []): bool;

    public function list(array $filters = []): Collection;

    public function update($id, array $data): bool;

    public function find($id);

    public function findOrFail($id);

    public function delete($id): ?bool;

    public function resolveModel();
}
