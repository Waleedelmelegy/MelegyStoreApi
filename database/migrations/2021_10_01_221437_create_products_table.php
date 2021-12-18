<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('title');
            $table->longText('desc');
            $table->string('title_seo')->nullable();
            $table->longText('desc_seo')->nullable();
            $table->string('img')->nullable();
            $table->integer('price');
            $table->string('color');
            $table->string('size');
            $table->integer('weight');
            $table->integer('qty');
            $table->integer('views')->default('0');
            $table->string('created_by');
            $table->foreignId('user_id')->constrained('users');
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
        Schema::dropIfExists('products');
    }
}
