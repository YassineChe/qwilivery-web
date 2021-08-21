<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpressDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('express_deliveries', function (Blueprint $table) {
            $table->id();
            $table->engine = 'MyISAM';
            $table->foreignId('delivery_id')->nullable()->references('id')->on('deliveries');
            $table->foreignId('restaurant_id')->references('id')->on('restaurants');
            $table->timestamp('taken_at')->nullable();
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
        Schema::dropIfExists('express_deliveries');
    }
}
