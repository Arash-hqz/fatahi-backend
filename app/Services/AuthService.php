<?php

namespace App\Services;

use App\Contracts\Services\AuthServiceInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService implements AuthServiceInterface
{
    protected $users;

    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
    }

    public function register(array $data)
    {
        return $this->users->create($data);
    }

    public function login(string $email, string $password): ?string
    {
        $user = $this->users->findByEmail($email);
        if (! $user) return null;
        if (! Hash::check($password, $user->password)) return null;

        return JWTAuth::fromUser($user);
    }
}
