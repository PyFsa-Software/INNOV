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
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id('id_comprobante');
            $table->string('descripcion_comprobante');
            $table->string('numero_recibo');
            $table->date('fecha_comprobante');
            $table->string('forma_pago');
            $table->string('importe_total');
            $table->string('concepto_de');
            $table->unsignedBigInteger('id_cliente')->nullable();
            $table->foreign('id_cliente')->references('id_persona')->on('personas');
            $table->unsignedBigInteger('id_venta')->nullable();
            $table->foreign('id_venta')->references('id_venta')->on('ventas');
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
        Schema::table('comprobantes', function (Blueprint $table) {
            $table->dropForeign(['id_cliente']);
            $table->dropForeign(['id_venta']);
        });
        Schema::dropIfExists('comprobantes');
    }
};
