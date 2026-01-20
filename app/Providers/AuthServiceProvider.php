<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;
use App\Models\User;

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
        $this->registerPolicies();

        // Cek jika tabel permissions ada (untuk menghindari error saat migrate awal)
        if (Schema::hasTable('permissions')) {

            // Ambil semua permission beserta role yang memilikinya
            $permissions = Permission::with('roles')->get();

            foreach ($permissions as $permission) {
                // Definisikan Gate: @can('dashboard.view')
                Gate::define($permission->name, function (User $user) use ($permission) {
                    // Cek apakah Role User ada di daftar role permission ini
                    // User -> role_id -> Roles table
                    // Permission -> roles (via role_has_permissions)

                    if (!$user->role) return false;

                    // Cek apakah permission ini dimiliki oleh role user tersebut
                    return $permission->roles->contains('id', $user->role_id);
                });
            }
        }
    }
}
