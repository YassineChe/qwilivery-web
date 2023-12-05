<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('conversations', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('delivery_id')->nullable()->references('id')->on('deliveries');
        //     $table->foreignId('restaurant_id')->nullable()->references('id')->on('restaurants');
        //     $table->foreignId('admin_id')->nullable()->references('id')->on('admins');
        //     $table->enum('deleted_by', ['delivery', 'restaurant', 'admin'])->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversations');
    }
}
