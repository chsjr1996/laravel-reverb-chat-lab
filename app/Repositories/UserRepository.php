<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected string $model = User::class;

    public function list(array $filters = []): Collection
    {
        return $this->resolvedModel->newQuery()
            ->when($filters['exclude_current_user'] ?? 0, fn ($q) => $q->excludeLoggedInUser())
            ->when($filters['exclude_friends'] ?? 0, fn ($q) => $q->excludeFriends())
            ->get();
    }
}
