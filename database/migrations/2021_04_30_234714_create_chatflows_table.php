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
            $table->foreignId('conversation_id')->references('id')->on('converstions');
            $table->enum('from', ['delivery', 'restaurant', 'admin']);
            $table->enum('to', ['delivery', 'restaurant', 'admin']);
            $table->text('message');
            $table->timestamp('seen_at')->nullable();
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
