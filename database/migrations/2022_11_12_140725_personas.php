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
        Schema::create('personas', function (Blueprint $table) {

            $table->id('id_persona');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('dni');
            $table->string('domicilio');
            $table->string('celular', 10);
            $table->string('correo');
            $table->boolean('cliente')->default(1);
            $table->boolean('activo')->default(1);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas');
    }
};