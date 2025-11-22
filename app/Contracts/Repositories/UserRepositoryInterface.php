<?php

namespace App\Contracts\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * Find a user by email
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * Create a new user
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User;

    /**
     * Return all users
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Find a user by id
     *
     * @param int|string $id
     * @return User|null
     */
    public function find($id): ?User;

    /**
     * Delete a user by id
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool;

    /**
     * Update a user's role(s)
     *
     * @param int|string $id
     * @param string|array $roles
     * @return User|null
     */
    public function updateRole($id, $roles): ?User;
}
