<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // $this->call(GallerySeeder::class);
        // $this->call(RegionCountrySeeder::class);
        // $this->call(CompanyPositionSeeder::class);
        // $this->call(InitialPermissionSeeder::class);
        $this->call(SubdomainSeeder::class);
    }
}
