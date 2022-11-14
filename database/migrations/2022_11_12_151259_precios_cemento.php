<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('precios_cemento', function (Blueprint $table){

            $table->id('id_precio_cemento');
            $table->string('precio_bercomat');
            $table->string('precio_sancayetano');
            $table->string('precio_rio_colorado');
            $table->string('precio_promedio');
            $table->date('fecha');
   

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('precios_cemento');
    }
};
