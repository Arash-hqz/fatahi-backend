<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function findByIdentifier(string $identifier): ?User
    {
        return User::where('email', $identifier)
        ->orWhere('phone', $identifier)
        ->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function all()
    {
        return User::all();
    }

    public function find($id): ?User
    {
        return User::find($id);
    }

    public function delete($id): bool
    {
        $user = User::find($id);
        if (! $user) return false;
        return (bool) $user->delete();
    }

    public function updateRole($id, $roles): ?User
    {
        $user = User::find($id);
        if (! $user) return null;
        $user->syncRoles($roles);
        $user->refresh();
        return $user;
    }
}

