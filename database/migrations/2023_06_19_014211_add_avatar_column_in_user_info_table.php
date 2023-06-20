<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAvatarColumnInUserInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_infos', function (Blueprint $table) {
          $table->longtext('avatar')->default('USER AVATAR_ESCO PHOTOS-70px-16.png');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_infos', function (Blueprint $table) {
            //
        });
    }
}
