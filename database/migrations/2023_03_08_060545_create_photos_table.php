<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
          $table->id();
          $table->foreignId('user_id')->constrained('users')->unsigned();
          $table->foreignId('album_id')->constrained('albums')->unsigned();
          $table->string('file_name');
          $table->string('file_size');
          $table->string('file_type');
          $table->longtext('description')->nullable();
          $table->string('_token')->default(generateRandomString());
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
        Schema::dropIfExists('photos');
    }
}
