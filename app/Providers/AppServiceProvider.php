<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Gate::define('manage-users', fn ($user) => $user->canManageUsers());
        Gate::define('manage-restaurant', fn ($user) => $user->canManageRestaurant());
        Gate::define('manage-orders', fn ($user) => $user->canManageOrders());
        Gate::define('manage-reservations', fn ($user) => $user->canManageReservations());
    }
}
