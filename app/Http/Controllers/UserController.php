<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserIndexRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function index(UserIndexRequest $request): AnonymousResourceCollection
    {
        $users = $this->userRepository->list($request->validated());

        return UserResource::collection($users);
    }
}
