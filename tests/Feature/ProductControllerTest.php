<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use App\Models\Product;
use Tymon\JWTAuth\Facades\JWTAuth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ProductControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_admin_can_create_product(): void
    {
        // ensure permissions & role
        Permission::firstOrCreate(['name' => 'create products', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'delete products', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api'])->syncPermissions(['create products', 'delete products']);

        $admin = User::factory()->create();
            $admin = User::factory()->create();
            $admin->assignRole('admin');
            $this->actingAs($admin, 'api');

        $data = ['title' => 'Test Product', 'description' => 'desc', 'price' => 19.99];
            $resp = $this->postJson('/api/admin/products', $data);
        $resp->assertStatus(201);
        $this->assertDatabaseHas('products', ['title' => 'Test Product']);
    }

    public function test_guest_can_list_products(): void
    {
        Product::factory()->create(['title' => 'Public Product']);
        $guestIndex = $this->getJson('/api/guest/products');
        $guestIndex->assertStatus(200);
        $guestIndex->assertJsonFragment(['title' => 'Public Product']);
    }

    public function test_guest_can_show_product(): void
    {
        $p = Product::factory()->create(['title' => 'Show Product']);
        $show = $this->getJson('/api/guest/products/' . $p->id);
        $show->assertStatus(200);
        $show->assertJsonFragment(['title' => 'Show Product']);
    }

    public function test_admin_can_update_product(): void
    {
        Permission::firstOrCreate(['name' => 'create products', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'delete products', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api'])->syncPermissions(['create products', 'delete products']);

        $p = Product::factory()->create(['title' => 'Old Title']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($admin);
        $headers = ['Authorization' => 'Bearer ' . $token];

        $update = $this->putJson('/api/admin/products/' . $p->id, ['title' => 'Updated Product'], $headers);
        $update->assertStatus(200);
        $this->assertDatabaseHas('products', ['title' => 'Updated Product']);
    }

    public function test_admin_can_delete_product(): void
    {
        Permission::firstOrCreate(['name' => 'create products', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'delete products', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api'])->syncPermissions(['create products', 'delete products']);

        $p = Product::factory()->create();
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($admin);
        $headers = ['Authorization' => 'Bearer ' . $token];

        $del = $this->deleteJson('/api/admin/products/' . $p->id, [], $headers);
        $del->assertStatus(200);
        $this->assertDatabaseMissing('products', ['id' => $p->id]);
    }
}
