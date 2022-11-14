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
        Schema::create('detalle_ventas', function (Blueprint $table){

            $table->id('id_detalle_venta');
            $table->date('nro_cuota_pagada');
            $table->timestamp('fecha')->useCurrent();
            $table->string('fecha_prox_vencimiento');
            $table->string('importe');
            $table->foreignId("id_venta")->references("id_venta")->on("ventas")->onDelete("restrict")->onUpdate("restrict");
   

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_ventas');
    }
};
