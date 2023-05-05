<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Region;
use App\Models\Country;

class RegionCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // REGIONS
        $region = new Region;
        $region->id = 1;
        $region->name = 'Africa';
        $region->_token = generateRandomString();
        $region->save();

        $region = new Region;
        $region->id = 2;
        $region->name = 'Arab States';
        $region->_token = generateRandomString();
        $region->save();

        $region = new Region;
        $region->id = 3;
        $region->name = 'Asia & Pacific';
        $region->_token = generateRandomString();
        $region->save();

        $region = new Region;
        $region->id = 4;
        $region->name = 'Europe';
        $region->_token = generateRandomString();
        $region->save();

        $region = new Region;
        $region->id = 5;
        $region->name = 'Middle East';
        $region->_token = generateRandomString();
        $region->save();

        $region = new Region;
        $region->id = 6;
        $region->name = 'North America';
        $region->_token = generateRandomString();
        $region->save();

        $region = new Region;
        $region->id = 7;
        $region->name = 'South/Central America';
        $region->_token = generateRandomString();
        $region->save();

        $region = new Region;
        $region->id = 8;
        $region->name = 'South/Latin America';
        $region->_token = generateRandomString();
        $region->save();















        // COUNTRIES
        // ==========
        // AFRICA
        // ==========
        $country = new Country;
        $country->name = 'Angola';
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = 'Benin';
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = 'Botswana';
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = 'Burkina Faso';
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = 'Burundi';
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = 'Cameroon';
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = 'Cape Verde';
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = 'Central African Republic';
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = 'Chad';
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = 'Congo';
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = 'Congo, The Democratic Republic of the';
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Côte D'Ivoire";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Equatorial Guinea";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Eritrea";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Eswatini";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Ethiopia";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Gabon";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Gambia";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Ghana";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Guinea";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Guinea-Bissau";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Kenya";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Lesotho";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Liberia";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Madagascar";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Malawi";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Mali";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Mauritius";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Mayotte";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Mozambique";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Namibia";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Niger";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Nigeria";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Rwanda";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Saint Helena";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Sao Tome and Principe";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Senegal";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Seychelles";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Sierra Leone";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "South Africa";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "South Sudan";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Tanzania, United Republic of";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Togo";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Uganda";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Western Sahara";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Zambia";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Zimbabwe";
        $country->region_id = 1;
        $country->_token = generateRandomString();
        $country->save();









        // ==============
        // ARAB STATES
        // ==============
        $country = new Country;
        $country->name = "Algeria";
        $country->region_id = 2;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Bahrain";
        $country->region_id = 2;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Comoros";
        $country->region_id = 2;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Djibouti";
        $country->region_id = 2;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Mauritania";
        $country->region_id = 2;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Morocco";
        $country->region_id = 2;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Palestinian Territory";
        $country->region_id = 2;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Somalia";
        $country->region_id = 2;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Sudan";
        $country->region_id = 2;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Tunisia";
        $country->region_id = 2;
        $country->_token = generateRandomString();
        $country->save();














        // ================
        // ASIA PACIFIC
        // ================
        $country = new Country;
        $country->name = "Afghanistan";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "American Samoa";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Antarctica";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Australia";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Azerbaijan";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Bangladesh";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Bhutan";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "British Indian Ocean Territory";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Brunei Darussalam";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Cambodia";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "China";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Christmas Island";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Cocos (Keeling) Islands";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Cook Islands";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Fiji";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "French Polynesia";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "French Southern Territories";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Guam";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Heard Island and McDonald Islands";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Hong Kong";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "India";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Indonesia";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Japan";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Kazakhstan";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Kiribati";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Korea, Democratic People's Republic of";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Korea, Republic of";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Kyrgyzstan";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Lao People's Democratic Republic";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Macau";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Malaysia";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Maldives";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Marshall Islands";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Micronesia, Federated States of";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Mongolia";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Myanmar";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Nauru";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Nepal";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "New Caledonia";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "New Zealand";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Niue";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Norfolk Island";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Northern Mariana Islands";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Pakistan";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Palau";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Papua New Guinea";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Philippines";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Pitcairn Islands";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Reunion";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Samoa";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Singapore";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Solomon Islands";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Sri Lanka";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Syrian Arab Republic";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Taiwan";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Tajikistan";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Thailand";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Timor-Leste";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Tokelau";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Tonga";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Turkmenistan";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Tuvalu";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "United States Minor Outlying Islands";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Uzbekistan";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Vanuatu";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Vietnam";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Wallis and Futuna";
        $country->region_id = 3;
        $country->_token = generateRandomString();
        $country->save();








        // ==========
        // EUROPE
        // ==========
        $country = new Country;
        $country->name = "Aland Islands";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Albania";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Andorra";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Armenia";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Austria";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Belarus";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Belgium";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Bosnia and Herzegovina";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Bulgaria";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Croatia";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Cyprus";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Czech Republic";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Denmark";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Estonia";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Faroe Islands";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Finland";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "France";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "France, Metropolitan";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Georgia";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Germany";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Gibraltar";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Greece";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Greenland";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Guernsey";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Holy See (Vatican City State)";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Hungary";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Iceland";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Ireland";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Isle of Man";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Italy";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Jersey";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Latvia";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Liechtenstein";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Lithuania";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Luxembourg";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Macedonia";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Malta";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Moldova, Republic of";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Monaco";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Montenegro";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Netherlands";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Norway";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Poland";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Portugal";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Romania";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Russian Federation";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "San Marino";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Serbia";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Slovakia";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Slovenia";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Spain";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Svalbard and Jan Mayen";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Sweden";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Switzerland";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Turkey";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Ukraine";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "United Kingdom";
        $country->region_id = 4;
        $country->_token = generateRandomString();
        $country->save();















        // =============
        // MIDDLE EAST
        // =============
        $country = new Country;
        $country->name = "Egypt";
        $country->region_id = 5;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Iran, Islamic Republic of";
        $country->region_id = 5;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Iraq";
        $country->region_id = 5;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Israel";
        $country->region_id = 5;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Jordan";
        $country->region_id = 5;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Kuwait";
        $country->region_id = 5;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Lebanon";
        $country->region_id = 5;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Libya";
        $country->region_id = 5;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Oman";
        $country->region_id = 5;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Qatar";
        $country->region_id = 5;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Saudi Arabia";
        $country->region_id = 5;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "United Arab Emirates";
        $country->region_id = 5;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Yemen";
        $country->region_id = 5;
        $country->_token = generateRandomString();
        $country->save();













        // ===============
        // NORTH AMERICA
        // ===============
        $country = new Country;
        $country->name = "Canada";
        $country->region_id = 6;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Saint Pierre and Miquelon";
        $country->region_id = 6;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "United States";
        $country->region_id = 6;
        $country->_token = generateRandomString();
        $country->save();








        // ========================
        // SOUTH/CENTRAL AMERICA
        // ========================
        $country = new Country;
        $country->name = "Nicaragua";
        $country->region_id = 7;
        $country->_token = generateRandomString();
        $country->save();













        // ======================
        // SOUTH/LATIN AMERICA
        // ======================
        $country = new Country;
        $country->name = "Anguilla";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Antigua and Barbuda";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Argentina";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Aruba";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Bahamas";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Barbados";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Belize";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Bermuda";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Bolivia";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Bouvet Island";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Brazil";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Cayman Islands";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Chile";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Colombia";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Costa Rica";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Cuba";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Dominica";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Dominican Republic";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Ecuador";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "El Salvador";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Falkland Islands (Malvinas)";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "French Guiana";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Grenada";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Guadeloupe";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Guatemala";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Guyana";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Haiti";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Honduras";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Jamaica";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Martinique";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Mexico";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Montserrat";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Netherlands Antilles";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Panama";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Paraguay";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Peru";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Puerto Rico";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Saint Barthelemy";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Saint Kitts and Nevis";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Saint Lucia";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Saint Martin";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Saint Vincent and the Grenadines";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "South Georgia and the South Sandwich Islands";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Suriname";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Trinidad and Tobago";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Turks and Caicos Islands";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Uruguay";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Venezuela";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Virgin Islands, British";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();

        $country = new Country;
        $country->name = "Virgin Islands, U.S.";
        $country->region_id = 8;
        $country->_token = generateRandomString();
        $country->save();
    }
}
