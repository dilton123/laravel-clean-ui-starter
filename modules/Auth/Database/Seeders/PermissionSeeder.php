<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Auth\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $manageUsers = new Permission();
        $manageUsers->name = 'Manage Users';
        $manageUsers->slug = 'manage-users';
        $manageUsers->save();

        $manageAccount = new Permission();
        $manageAccount->name = 'Manage Account';
        $manageAccount->slug = 'manage-account';
        $manageAccount->save();

        $manageRoles = new Permission();
        $manageRoles->name = 'Manage Roles';
        $manageRoles->slug = 'manage-roles';
        $manageRoles->save();

        $managePermissions = new Permission();
        $managePermissions->name = 'Manage Permissions';
        $managePermissions->slug = 'manage-permissions';
        $managePermissions->save();
    }
}
