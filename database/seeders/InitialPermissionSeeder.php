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
        Permission::create(['name' => 'super admin']);

        // Role & Permission Management
        Permission::create(['name' => 'assign role']);
        Permission::create(['name' => 'assign permission']);

        // User Management
        Permission::create(['name' => 'view user']);
        Permission::create(['name' => 'add user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'delete user']);

        // create roles and assign existing permissions
        // Super Admin Role
        $role1 = Role::create(['name' => 'super admin']);
        $role1->givePermissionTo('super admin');

        // Manage Users Role
        $role2 = Role::create(['name' => 'manage users']);
        $role2->givePermissionTo('view user');
        $role2->givePermissionTo('add user');
        $role2->givePermissionTo('edit user');
        $role2->givePermissionTo('delete user');

        // create super admin user
        $superadmin = new User;
        $superadmin->name = 'Super Admin';
        $superadmin->email = 'super@admin.com';
        $superadmin->password = bcrypt('superadmin');
        $superadmin->save();
        $superadmin->assignRole($role1); // assign role

        // create user who manage users
        $muser = new User;
        $muser->name = 'Alexander Flores';
        $muser->email = 'alex@escophotos.com';
        $muser->password = bcrypt('123456');
        $muser->save();
        $muser->assignRole($role2); // assign role
    }
}
