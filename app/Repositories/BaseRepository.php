<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class BaseRepository
{
    protected string $model;

    /**
     * @var Model
     */
    protected mixed $resolvedModel;

    public function __construct()
    {
        $this->resolvedModel = $this->resolveModel();
    }

    public function create(array $data = []): bool
    {
        return $this->resolvedModel->fill($data)->save();
    }

    public function list(array $filters = []): Collection
    {
        return $this->resolvedModel->all();
    }

    public function paginate(int $perPage): LengthAwarePaginator
    {
        return $this->resolvedModel->paginate($perPage);
    }

    public function find($id)
    {
        return $this->resolvedModel->find($id);
    }

    public function findOrFail($id)
    {
        return $this->resolvedModel->findOrFail($id);
    }

    public function update($id, array $data): bool
    {
        /**
         * @var Model $model
         */
        $model = $this->resolvedModel->findOrFail($id);

        $model->fill($data);

        return $model->save();
    }

    public function delete($id): ?bool
    {
        /**
         * @var Model $model
         */
        $model = $this->resolvedModel->findOrFail($id);

        return $model->delete();
    }

    public function getTable(): string
    {
        return $this->resolvedModel->getTable();
    }

    public function resolveModel()
    {
        return app($this->model);
    }
}
