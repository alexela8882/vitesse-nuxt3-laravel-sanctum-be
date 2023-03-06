<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galleries', function (Blueprint $table) {
          $table->id();
          $table->integer('parent_id')->unsigned()->nullable();
          $table->foreignId('user_id')->constrained('users')->unsigned();
          $table->foreignId('country_id')->constrained('countries')->unsigned();
          $table->string('name');
          $table->string('description');
          $table->string('date');
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
        Schema::dropIfExists('galleries');
    }
}