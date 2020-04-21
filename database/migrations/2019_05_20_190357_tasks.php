<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Tasks extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('tasks', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->integer('priority')->default(0);
      $table->integer('user_id');
      $table->string('status')->default('Todo');
      $table->string('title');
      $table->string('description');
      $table->timestamp('due')->nullable();
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
    Schema::dropIfExists('tasks');
  }
}
