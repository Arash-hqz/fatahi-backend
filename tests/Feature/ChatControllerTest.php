<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Database\Seeders\RolesAndPermissionsSeeder;

class ChatControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_recent_returns_array(): void
    {
        $resp = $this->getJson('/api/admin/chat/recent');
        // route admin/chat/recent requires auth in routes; call as guest should 401
        $resp->assertStatus(401);

        // create admin and try
        $admin = \App\Models\User::factory()->create();
        $admin->assignRole('admin');
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($admin);
        $headers = ['Authorization' => 'Bearer ' . $token];

        $resp2 = $this->getJson('/api/admin/chat/recent', $headers);
        $resp2->assertStatus(200);
        $resp2->assertExactJson([]);
    }
}
