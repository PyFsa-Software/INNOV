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
        Schema::create('detalle_ventas', function (Blueprint $table) {

            $table->id('id_detalle_venta');
            $table->string('numero_cuota');
            $table->date('fecha_maxima_a_pagar');
            $table->string('total_estimado_a_pagar');
            $table->string('total_intereses')->nullable(true);
            $table->string('fecha_pago')->nullable(true);
            $table->string('total_pago')->nullable(true);
            $table->string('pagado')->default('no');

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