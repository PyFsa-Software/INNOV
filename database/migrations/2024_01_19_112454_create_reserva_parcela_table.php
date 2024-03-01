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
        if (!Schema::hasTable('reserva_parcela')) {
            Schema::create('reserva_parcela', function (Blueprint $table) {
                $table->id('id_reserva_parcela');
                $table->string('id_cliente');
                $table->foreign('id_cliente')->references('id_persona')->on('personas');
                $table->string('id_parcela');
                $table->foreign('id_parcela')->references('id_parcela')->on('parcelas');
                $table->date('fecha_reserva');
                $table->string('monto_total');
                $table->boolean('estado_reserva')->default(false)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reserva_parcela');
    }
};