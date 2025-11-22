<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure tests use the API guard by default so Spatie roles/permissions and JWT auth work
        config(['auth.defaults.guard' => 'api']);
        // Force in-memory sqlite for tests to avoid container-level DB env overrides
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);

        // Run migrations and seed roles/permissions for the in-memory test database
        // Running them here ensures the PHPUnit process (which uses the in-memory DB)
        // has all tables (including Spatie's) before any tests execute.
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--class' => \Database\Seeders\RolesAndPermissionsSeeder::class, '--force' => true]);
    }
}
