<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUuid = Hash::make('admin-uuid');

        $user = User::create([
            'uuid' => $adminUuid,
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'onboarding_status' => 1,
            'onboarding_step' => 3,
        ]);

        DB::table('account_links')->insert([
            'player_uuid' => 'e296a2b2-3062-49e8-bba0-77e625038c22',
            'user_uuid' => $adminUuid,
            'is_linked' => true,
            'linked_at' => now(),
        ]);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        Permission::create(['name' => 'manage_permissions']);

        // Role
        $superadminRole = Role::create(['name' => 'Superadmin']);
        $superadminRole->givePermissionTo('manage_permissions');

        $user->givePermissionTo('manage_permissions');
        $user->assignRole('Superadmin');
    }
}
