<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\file;
use App\Models\User;
use App\Models\storage;
use App\Models\sub_storage;
use App\Policies\FilePolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Policies\StoragePolicy;
use App\Policies\PermissionPolicy;
use App\Policies\SubStoragePolicy;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Permission::class => PermissionPolicy::class,
        Role::class => RolePolicy::class,
        User::class => UserPolicy::class,
        file::class => FilePolicy::class,
        storage::class => StoragePolicy::class,
        sub_storage::class => SubStoragePolicy::class,
        
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
