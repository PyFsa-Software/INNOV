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
        Schema::create('detalle_planes', function (Blueprint $table){

            $table->id('id_detalle_plan');
            $table->date('fecha_desde');
            $table->date('fecha_hasta');
            $table->string('valor_cuota');
            $table->timestamp('fecha')->useCurrent();



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_planes');
    }
};