<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

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
        Gate::define('isAdmin', function(User $user){
            return $user->role === 'admin' && $user->is_active;
        });
        Gate::define('isUser', function(User $user){
            return $user->role === 'user' && $user->is_active;
        });
        Gate::define('manage-products', function(User $user){
            return $user-tokenCan('admin') || ($user->tokenCan('manage-products') && $user->role === 'admin');     
        });
    }
}
