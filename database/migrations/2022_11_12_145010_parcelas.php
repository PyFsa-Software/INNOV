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
        Schema::create('parcelas', function (Blueprint $table){

            $table->id('id_parcela');
            $table->string('superficie_parcela');
            $table->string('manzana');
            $table->string('cantidad_bolsas');
            $table->string('ancho');
            $table->string('largo');
            $table->foreignId("id_lote")->references("id_lote")->on("lotes")->onDelete("restrict")->onUpdate("restrict");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parcelas');
    }
};