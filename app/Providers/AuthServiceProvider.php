<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('has-permission', function ($user, $permissionName) {
            if (empty($permissionName)) {
                return true; // Allow access if no specific permission is required
            }
            return $user->subscription && 
                   $user->subscription->permissions()->where('name', $permissionName)->exists();
        });
    }
}
