<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Article;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Roles & permissions
        $this->call(RolesAndPermissionsSeeder::class);

        // Create some users
            $test = User::where('email', 'test@example.com')->first();
            if (! $test) {
                $test = User::factory()->create([
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                ]);
            }

        // give test user admin role
        if (method_exists($test, 'assignRole')) {
            $test->assignRole('admin');
        }

        $users = User::factory(5)->create();

        // For each user, create some articles, products and projects
        $users->each(function (User $user) {
            Article::factory(3)->create(['user_id' => $user->id]);
            Product::factory(2)->create(['user_id' => $user->id]);
            Project::factory(1)->create(['user_id' => $user->id]);
        });

        // Also create some content for the test user
        Article::factory(2)->create(['user_id' => $test->id]);
        Product::factory(1)->create(['user_id' => $test->id]);
        Project::factory(1)->create(['user_id' => $test->id]);
    }
}
