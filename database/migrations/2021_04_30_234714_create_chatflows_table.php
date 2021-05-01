<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatflowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chatflows', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('conversation_id')->references('id')->on('conversations');
            $table->foreignId('admin_id')->nullable()->references('id')->on('admins');
            $table->foreignId('delivery_id')->nullable()->references('id')->on('deliveries');
            $table->enum('from', ['admin', 'derlivery']);
            $table->enum('to', ['admin', 'derlivery']);
            $table->text('message');
            $table->timestamp('seen_at');
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
        Schema::dropIfExists('chatflows');
    }
}
