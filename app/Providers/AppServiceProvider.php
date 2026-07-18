<?php

namespace App\Providers;

use App\Repositories\Contracts\ClassRepository;
use App\Repositories\Contracts\MembershipRepository;
use App\Repositories\Contracts\ProductRepository;
use App\Repositories\Eloquent\EloquentClassRepository;
use App\Repositories\Eloquent\EloquentMembershipRepository;
use App\Repositories\Eloquent\EloquentProductRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MembershipRepository::class, EloquentMembershipRepository::class);
        $this->app->bind(ClassRepository::class, EloquentClassRepository::class);
        $this->app->bind(ProductRepository::class, EloquentProductRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, string $ability): ?bool {
            if (method_exists($user, 'hasAnyRole') && $user->hasAnyRole(['Owner', 'Super admin'])) {
                return true;
            }

            return null;
        });
    }
}
