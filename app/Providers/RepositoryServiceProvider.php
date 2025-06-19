<?php

namespace App\Providers;

use App\Interfaces\ChatMessageRepositoryInterface;
use App\Interfaces\ChatRoomRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\ChatMessageRepository;
use App\Repositories\ChatRoomRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ChatRoomRepositoryInterface::class, ChatRoomRepository::class);
        $this->app->bind(ChatMessageRepositoryInterface::class, ChatMessageRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
