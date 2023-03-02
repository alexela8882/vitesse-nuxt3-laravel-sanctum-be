<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Country;
use App\Models\Company;
use App\Models\Position;

class CountryCompanyPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // COUNTRIES
        $country = new Country;
        $country->name = 'Philippines';
        $country->code = 'PH';
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = 'Singapore';
        $country->code = 'SG';
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = 'Indonesia';
        $country->code = 'ID';
        $country->_token = generateRandomString();
        $country->save();

        // COMPANIES
        $company = new Company;
        $company->name = 'EPI';
        $company->_token = generateRandomString();
        $company->save();

        $company = new Company;
        $company->name = 'SG';
        $company->_token = generateRandomString();
        $company->save();

        $company = new Company;
        $company->name = 'BINTAN';
        $company->_token = generateRandomString();
        $company->save();

        // POSITIONS
        $position = new Position;
        $position->name = 'Web Dev';
        $position->_token = generateRandomString();
        $position->save();
        
        $position = new Position;
        $position->name = 'Pro Art';
        $position->_token = generateRandomString();
        $position->save();
        
        $position = new Position;
        $position->name = 'Human Resource';
        $position->_token = generateRandomString();
        $position->save();

        $position = new Position;
        $position->name = 'Marketing';
        $position->_token = generateRandomString();
        $position->save();

        $position = new Position;
        $position->name = 'Service Engineer';
        $position->_token = generateRandomString();
        $position->save();
        
    }
}
