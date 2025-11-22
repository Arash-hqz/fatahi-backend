<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_register_creates_user(): void
    {
        $payload = [
            'name' => 'Integration User',
            'email' => 'integration@example.com',
            'password' => 'password',
        ];

        $resp = $this->postJson('/api/auth/register', $payload);
        $resp->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'integration@example.com']);
    }

    public function test_login_returns_token(): void
    {
        // create user with known password
        User::factory()->create([
            'email' => 'login@example.com',
            'password' => bcrypt('password'),
        ]);

        $login = $this->postJson('/api/auth/login', ['email' => 'login@example.com', 'password' => 'password']);
        $login->assertStatus(200);
        $login->assertJsonStructure(['access_token', 'token_type']);
    }
}
