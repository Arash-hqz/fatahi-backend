<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function create(array $data)
    {
        // prepare data
        $payload = [];
        $payload['name'] = $data['name'] ?? null;
        $payload['email'] = $data['email'] ?? null;
        $payload['phone'] = $data['phone'] ?? null;
        $payload['password'] = isset($data['password']) ? Hash::make($data['password']) : null;
        $payload['active'] = $data['active'] ?? true;

        $user = $this->repo->create($payload);

        // assign role if provided and Spatie is available
        if (! empty($data['role']) && method_exists($user, 'assignRole')) {
            $user->assignRole($data['role']);
        }

        return $user;
    }

    public function all()
    {
        return $this->repo->all();
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function updateRole($id, $role)
    {
        return $this->repo->updateRole($id, $role);
    }
}
