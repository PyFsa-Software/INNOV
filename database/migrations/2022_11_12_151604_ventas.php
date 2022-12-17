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
        Schema::create('ventas', function (Blueprint $table) {

            $table->id('id_venta');
            $table->timestamp('fecha_venta')->useCurrent();
            $table->integer('cuotas');
            $table->string('precio_total_terreno');
            $table->string('cuota_mensual_bolsas_cemento');
            $table->string('precio_total_entrega');
            $table->string('precio_final');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas');
    }
};