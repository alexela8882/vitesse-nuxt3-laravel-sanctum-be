<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Subdomain;

class SubdomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subdomain = new Subdomain;
        $subdomain->id = 1;
        $subdomain->name = 'scientific';
        $subdomain->_token = generateRandomString();
        $subdomain->save();

        $subdomain = new Subdomain;
        $subdomain->id = 2;
        $subdomain->name = 'medical';
        $subdomain->_token = generateRandomString();
        $subdomain->save();

        $subdomain = new Subdomain;
        $subdomain->id = 3;
        $subdomain->name = 'pharma';
        $subdomain->_token = generateRandomString();
        $subdomain->save();

        $subdomain = new Subdomain;
        $subdomain->id = 4;
        $subdomain->name = 'vaccixcell';
        $subdomain->_token = generateRandomString();
        $subdomain->save();

        $subdomain = new Subdomain;
        $subdomain->id = 5;
        $subdomain->name = 'tapestlerx';
        $subdomain->_token = generateRandomString();
        $subdomain->save();

        $subdomain = new Subdomain;
        $subdomain->id = 6;
        $subdomain->name = 'aster';
        $subdomain->_token = generateRandomString();
        $subdomain->save();
    }
}
