<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function all()
    {
        return User::all();
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
        $user->role = $role;
        $user->save();
        return $user;
    }
}
