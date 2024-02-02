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
        Schema::create('detalle_reserva_parcela', function (Blueprint $table) {
            $table->id('id_detalle_reserva_parcela');
            $table->string('id_reserva_parcela');
            $table->foreign('id_reserva_parcela')->references('id_reserva_parcela')->on('reserva_parcela');
            $table->string('fecha_pago');
            $table->string('forma_pago')->enum('EFECTIVO', 'TRANSFERENCIA', 'DEBITO');
            $table->string('concepto_de');
            $table->string('importe_pago');
            $table->boolean('cancelado')->default(false);
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
        Schema::dropIfExists('detalle_reserva_parcela');
    }
};