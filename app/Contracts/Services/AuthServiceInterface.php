<?php

namespace App\Contracts\Services;

interface AuthServiceInterface
{
    /**
     * Register a new user and return the created model
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function register(array $data);

    /**
     * Attempt login with credentials, return JWT token string
     * or null on failure
     *
     * @param string $email
     * @param string $password
     * @return string|null
     */
    public function login(string $email, string $password): ?string;
}
