<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->engine = 'MyISAM';
            $table->string('order_title');
            $table->string('order_body');
            $table->string('express_title');
            $table->string('express_body');
            $table->timestamps();
        });

        //Insert default settings!
        \DB::table('app_settings')->insert(
            array(
                'order_title'   => 'Nouvelle commande',
                'order_body' => 'Vous avez une nouvelle commande à livrer, appuyez sur pour plus d\'informations',
                'express_title' => 'Express livreur ⚡️',
                'express_body' => 'demande un livreur Express..',
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_settings');
    }
}
