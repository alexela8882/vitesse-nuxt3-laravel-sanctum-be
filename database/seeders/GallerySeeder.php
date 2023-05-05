<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Gallery;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // MAIN GALLERIES
        $gallery = new Gallery;
        $gallery->id = 1;
        $gallery->name = "Esco Lifesciences Group";
        $gallery->parent_id = null;
        $gallery->_token = generateRandomString();
        $gallery->save();

        $gallery = new Gallery;
        $gallery->id = 2;
        $gallery->name = "Esco Aster";
        $gallery->parent_id = null;
        $gallery->_token = generateRandomString();
        $gallery->save();

        $gallery = new Gallery;
        $gallery->id = 3;
        $gallery->name = "EVX Ventures";
        $gallery->parent_id = null;
        $gallery->_token = generateRandomString();
        $gallery->save();



        // SUB-GALLERIES
        $gallery = new Gallery;
        $gallery->name = "Esco Scientific";
        $gallery->parent_id = 1;
        $gallery->_token = generateRandomString();
        $gallery->save();

        $gallery = new Gallery;
        $gallery->name = "Esco Medical";
        $gallery->parent_id = 1;
        $gallery->_token = generateRandomString();
        $gallery->save();

        $gallery = new Gallery;
        $gallery->name = "Esco Healthcare";
        $gallery->parent_id = 1;
        $gallery->_token = generateRandomString();
        $gallery->save();

        $gallery = new Gallery;
        $gallery->name = "Life in Esco";
        $gallery->parent_id = 1;
        $gallery->_token = generateRandomString();
        $gallery->save();
    }
}
