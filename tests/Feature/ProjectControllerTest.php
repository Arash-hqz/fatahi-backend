<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Project;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ProjectControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_admin_can_create_project(): void
    {
        Permission::firstOrCreate(['name' => 'create projects', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'delete projects', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api'])->syncPermissions(['create projects', 'delete projects']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin, 'api');

        $data = ['title' => 'Test Project', 'description' => 'desc'];
        $resp = $this->postJson('/api/admin/projects', $data);
        $resp->assertStatus(201);
        $this->assertDatabaseHas('projects', ['title' => 'Test Project']);
    }

    public function test_guest_can_list_projects(): void
    {
        Project::factory()->create(['title' => 'Public Project']);
        $guestIndex = $this->getJson('/api/guest/projects');
        $guestIndex->assertStatus(200);
        $guestIndex->assertJsonFragment(['title' => 'Public Project']);
    }

    public function test_guest_can_show_project(): void
    {
        $p = Project::factory()->create(['title' => 'Show Project']);
        $show = $this->getJson('/api/guest/projects/' . $p->id);
        $show->assertStatus(200);
        $show->assertJsonFragment(['title' => 'Show Project']);
    }

    public function test_admin_can_update_project(): void
    {
        Permission::firstOrCreate(['name' => 'create projects', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'delete projects', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api'])->syncPermissions(['create projects', 'delete projects']);

        $p = Project::factory()->create(['title' => 'Old Title']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($admin);
        $headers = ['Authorization' => 'Bearer ' . $token];

        $update = $this->putJson('/api/admin/projects/' . $p->id, ['title' => 'Updated Project'], $headers);
        $update->assertStatus(200);
        $this->assertDatabaseHas('projects', ['title' => 'Updated Project']);
    }

    public function test_admin_can_delete_project(): void
    {
        Permission::firstOrCreate(['name' => 'create projects', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'delete projects', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api'])->syncPermissions(['create projects', 'delete projects']);

        $p = Project::factory()->create();
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($admin);
        $headers = ['Authorization' => 'Bearer ' . $token];

        $del = $this->deleteJson('/api/admin/projects/' . $p->id, [], $headers);
        $del->assertStatus(200);
        $this->assertDatabaseMissing('projects', ['id' => $p->id]);
    }
}
