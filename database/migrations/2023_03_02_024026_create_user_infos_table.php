<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_infos', function (Blueprint $table) {
					$table->id();
          $table->foreignId('user_id')->constrained('users')->unsigned();
					$table->string('first_name')->nullable();
          $table->string('last_name')->nullable();
          $table->foreignId('country_id')->constrained('countries')->unsigned();
          $table->foreignId('company_id')->constrained('companies')->unsigned();
          $table->foreignId('position_id')->constrained('positions')->unsigned();
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
        Schema::dropIfExists('user_infos');
    }
}
