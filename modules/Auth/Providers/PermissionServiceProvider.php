<?php

namespace Modules\Auth\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\Auth\Models\Permission;

/**
 *  Gate for permissions registration (it is not used anywhere, will be removed in the future!)
 */
class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            Permission::with('roles')->get()->map(function ($permission) {
               // check if user can (Gate)
               Gate::define($permission->slug, function ($user) use ($permission) {
                   return $user->hasPermissionTo($permission->slug);
               });
            });

        } catch (\Exception $e) {
            report($e);
            exit;
        }
    }
}
