<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdditionalPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Gallery Management
        Permission::create(['guard_name' => 'sanctum', 'name' => 'view gallery', '_token' => generateRandomString()]);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'add gallery', '_token' => generateRandomString()]);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'edit gallery', '_token' => generateRandomString()]);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'delete gallery', '_token' => generateRandomString()]);

        // Gallery management role
        $galleryManager = Role::create(['guard_name' => 'sanctum', 'name' => 'manage gallery', '_token' => generateRandomString()]);
        $galleryManager->givePermissionTo('view gallery');
        $galleryManager->givePermissionTo('add gallery');
        $galleryManager->givePermissionTo('edit gallery');
        $galleryManager->givePermissionTo('delete gallery');

        // Album Management
        Permission::create(['guard_name' => 'sanctum', 'name' => 'view album', '_token' => generateRandomString()]);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'add album', '_token' => generateRandomString()]);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'edit album', '_token' => generateRandomString()]);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'delete album', '_token' => generateRandomString()]);

        // Album management role
        $albumManager = Role::create(['guard_name' => 'sanctum', 'name' => 'manage album', '_token' => generateRandomString()]);
        $albumManager->givePermissionTo('view album');
        $albumManager->givePermissionTo('add album');
        $albumManager->givePermissionTo('edit album');
        $albumManager->givePermissionTo('delete album');
        
        // Photo Management
        Permission::create(['guard_name' => 'sanctum', 'name' => 'view photo', '_token' => generateRandomString()]);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'add photo', '_token' => generateRandomString()]);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'edit photo', '_token' => generateRandomString()]);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'delete photo', '_token' => generateRandomString()]);

        // Photo management role
        $photoManager = Role::create(['guard_name' => 'sanctum', 'name' => 'manage photo', '_token' => generateRandomString()]);
        $photoManager->givePermissionTo('view photo');
        $photoManager->givePermissionTo('add photo');
        $photoManager->givePermissionTo('edit photo');
        $photoManager->givePermissionTo('delete photo');

        // Tag Management
        Permission::create(['guard_name' => 'sanctum', 'name' => 'view tag', '_token' => generateRandomString()]);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'add tag', '_token' => generateRandomString()]);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'edit tag', '_token' => generateRandomString()]);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'delete tag', '_token' => generateRandomString()]);

        // Tag management role
        $tagManager = Role::create(['guard_name' => 'sanctum', 'name' => 'manage tag', '_token' => generateRandomString()]);
        $tagManager->givePermissionTo('view tag');
        $tagManager->givePermissionTo('add tag');
        $tagManager->givePermissionTo('edit tag');
        $tagManager->givePermissionTo('delete tag');

        // subdomain Management
        Permission::create(['guard_name' => 'sanctum', 'name' => 'view subdomain', '_token' => generateRandomString()]);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'add subdomain', '_token' => generateRandomString()]);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'edit subdomain', '_token' => generateRandomString()]);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'delete subdomain', '_token' => generateRandomString()]);

        // subdomain management role
        $subdomainManager = Role::create(['guard_name' => 'sanctum', 'name' => 'manage subdomain', '_token' => generateRandomString()]);
        $subdomainManager->givePermissionTo('view subdomain');
        $subdomainManager->givePermissionTo('add subdomain');
        $subdomainManager->givePermissionTo('edit subdomain');
        $subdomainManager->givePermissionTo('delete subdomain');
    }
}
