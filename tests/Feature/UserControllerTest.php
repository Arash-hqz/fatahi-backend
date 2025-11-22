<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_admin_can_update_user_role(): void
    {
        Permission::firstOrCreate(['name' => 'manage users', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api'])->syncPermissions(['manage users']);
        // ensure the 'user' role exists for role updates
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'api']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($admin);
        $headers = ['Authorization' => 'Bearer ' . $token];

        $user = User::factory()->create(['email' => 'target@example.com']);

        $resp = $this->patchJson('/api/admin/users/role/' . $user->id, ['role' => 'user'], $headers);
        $resp->assertStatus(200);
        $this->assertTrue($user->fresh()->hasRole('user'));
    }

    public function test_admin_can_delete_user(): void
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($admin);
        $headers = ['Authorization' => 'Bearer ' . $token];

        $user = User::factory()->create();
        $del = $this->deleteJson('/api/admin/users/' . $user->id, [], $headers);
        $del->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
