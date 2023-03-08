<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
          $table->id();
          $table->string('title');
          $table->foreignId('user_id')->constrained('users')->unsigned();
          $table->foreignId('country_id')->constrained('countries')->unsigned();
          $table->string('venue');
          $table->longtext('description');
          $table->datetime('event_date');
          $table->longtext('img_path');
          $table->string('_token');
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('albums');
    }
}
