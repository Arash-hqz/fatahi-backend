<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Repositories\ArticleRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\ProjectRepositoryInterface;
use App\Contracts\Services\ArticleServiceInterface;
use App\Contracts\Services\ProductServiceInterface;
use App\Contracts\Services\ProjectServiceInterface;
use App\Repositories\ArticleRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProjectRepository;
use App\Services\ArticleService;
use App\Services\ProductService;
use App\Services\ProjectService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind repositories to interfaces
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);

        // Bind services to interfaces
        $this->app->bind(ArticleServiceInterface::class, ArticleService::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(ProjectServiceInterface::class, ProjectService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
