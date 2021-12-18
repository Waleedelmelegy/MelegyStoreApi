<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesAttributes extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('categories_attributes', function (Blueprint $table) {
      $table->id();
      $table->string('key_en');
      $table->string('key_ar');
      $table->string('icon');
      $table->foreignId('categorie_id')->constrained('categories');
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
    Schema::dropIfExists('categories_attributes');
  }
}
