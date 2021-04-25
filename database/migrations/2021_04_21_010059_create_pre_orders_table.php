<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->nullable()->references('id')->on('deliveries');
            $table->foreignId('restaurant_id')->references('id')->on('restaurants');
            $table->text('fullname');
            $table->text('address')->nullable();
            $table->text('lat')->nullable();
            $table->text('lng')->nullable();
            $table->timestamp('delivered_at')->default(false);
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
        Schema::dropIfExists('pre_orders');
    }
}
