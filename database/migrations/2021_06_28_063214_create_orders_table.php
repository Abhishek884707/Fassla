<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("ordernum");
            $table->unsignedBigInteger('userid');
            $table->integer('status');
            $table->decimal('grandtotal',15,4);
            $table->integer('itemcount');
            $table->boolean('ispaid')->default(false);
            $table->string('paymentmethod')->nullable();
            $table->string('paymentid')->nullable();
            $table->string('razorpaysign')->nullable();
            $table->json('address')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
