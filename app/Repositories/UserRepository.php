<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function all()
    {
        return User::all();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = User::find($id);
        if (! $user) return null;
        $user->update($data);
        return $user;
    }

    public function find($id)
    {
        return User::find($id);
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user) {
            return $user->delete();
        }
        return false;
    }

    public function updateRole($id, $role)
    {
        $user = User::find($id);
        if (! $user) return null;
        // Use Spatie roles/permissions instead of storing raw role
        if (method_exists($user, 'syncRoles')) {
            $user->syncRoles([$role]);
        } else {
            $user->role = $role;
            $user->save();
        }
        return $user;
    }
}
