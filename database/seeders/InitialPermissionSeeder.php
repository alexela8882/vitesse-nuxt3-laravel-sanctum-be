<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

use App\Models\User;
use Carbon\Carbon;

class InitialPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['guard_name' => 'sanctum', 'name' => 'super admin', '_token' => generateRandomString()]);

        // Role & Permission Management
        Permission::create(['guard_name' => 'sanctum', 'name' => 'assign role', '_token' => generateRandomString()]);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'assign permission', '_token' => generateRandomString()]);

        // User Management
        Permission::create(['guard_name' => 'sanctum', 'name' => 'view user', '_token' => generateRandomString()]);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'add user', '_token' => generateRandomString()]);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'edit user', '_token' => generateRandomString()]);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'delete user', '_token' => generateRandomString()]);

        // create roles and assign existing permissions
        // Super Admin Role
        $role1 = Role::create(['guard_name' => 'sanctum', 'name' => 'super admin', '_token' => generateRandomString()]);
        $role1->givePermissionTo('super admin');

        // Manage Users Role
        $role2 = Role::create(['guard_name' => 'sanctum', 'name' => 'manage users', '_token' => generateRandomString()]);
        $role2->givePermissionTo('assign role');
        $role2->givePermissionTo('assign permission');
        $role2->givePermissionTo('view user');
        $role2->givePermissionTo('add user');
        $role2->givePermissionTo('edit user');
        $role2->givePermissionTo('delete user');

        // create super admin user
        $superadmin = new User;
        $superadmin->name = 'Super Admin';
        $superadmin->email = 'super@admin.com';
        $superadmin->password = bcrypt('superadmin');
        $superadmin->_token = generateRandomString();
        $superadmin->save();
        $superadmin->assignRole($role1); // assign role

        // create user who manage users
        $muser = new User;
        $muser->name = 'Alexander Flores';
        $muser->email = 'alex@escophotos.com';
        $muser->password = bcrypt('123456');
        $muser->_token = generateRandomString();
        $muser->save();
        $muser->assignRole($role2); // assign role
    }
}
