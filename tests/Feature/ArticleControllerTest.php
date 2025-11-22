<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Article;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ArticleControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_admin_can_create_article(): void
    {
        Permission::firstOrCreate(['name' => 'create articles', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'delete articles', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api'])->syncPermissions(['create articles', 'delete articles']);

        $admin = User::factory()->create();
            $admin = User::factory()->create();
            $admin->assignRole('admin');
            $this->actingAs($admin, 'api');

        $data = ['title' => 'Test Article', 'content' => 'content'];
            $resp = $this->postJson('/api/admin/articles', $data);
        $resp->assertStatus(201);
        $this->assertDatabaseHas('articles', ['title' => 'Test Article']);
    }

    public function test_guest_can_list_articles(): void
    {
        Article::factory()->create(['title' => 'Public Article']);
        $guestIndex = $this->getJson('/api/guest/articles');
        $guestIndex->assertStatus(200);
        $guestIndex->assertJsonFragment(['title' => 'Public Article']);
    }

    public function test_guest_can_show_article(): void
    {
        $a = Article::factory()->create(['title' => 'Show Article']);
        $show = $this->getJson('/api/guest/articles/' . $a->id);
        $show->assertStatus(200);
        $show->assertJsonFragment(['title' => 'Show Article']);
    }

    public function test_admin_can_update_article(): void
    {
        Permission::firstOrCreate(['name' => 'create articles']);
        Permission::firstOrCreate(['name' => 'delete articles']);
        Role::firstOrCreate(['name' => 'admin'])->syncPermissions(['create articles', 'delete articles']);

        $a = Article::factory()->create(['title' => 'Old Title']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($admin);
        $headers = ['Authorization' => 'Bearer ' . $token];

        $update = $this->putJson('/api/admin/articles/' . $a->id, ['title' => 'Updated Article'], $headers);
        $update->assertStatus(200);
        $this->assertDatabaseHas('articles', ['title' => 'Updated Article']);
    }

    public function test_admin_can_delete_article(): void
    {
        Permission::firstOrCreate(['name' => 'create articles']);
        Permission::firstOrCreate(['name' => 'delete articles']);
        Role::firstOrCreate(['name' => 'admin'])->syncPermissions(['create articles', 'delete articles']);

        $a = Article::factory()->create();
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($admin);
        $headers = ['Authorization' => 'Bearer ' . $token];

        $del = $this->deleteJson('/api/admin/articles/' . $a->id, [], $headers);
        $del->assertStatus(200);
        $this->assertDatabaseMissing('articles', ['id' => $a->id]);
    }
}
