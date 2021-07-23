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
            $table->integer('categories_id');
            $table->integer('sub_categories_id');
            $table->string('name');
            $table->json('image');
            $table->unsignedBigInteger('mrp')->nullable();
            $table->json('price');
            $table->unsignedBigInteger('quantity');
            $table->unsignedBigInteger('qty_value')->nullable();
            $table->string('short_desc')->nullable();
            $table->text('description')->nullable();
            $table->integer('best_seller')->nullable();
            $table->integer('updated_by')->nullabel();
            $table->string('meta_title')->nullable();
            $table->string('meta_desc')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->integer('status')->default(false);
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
