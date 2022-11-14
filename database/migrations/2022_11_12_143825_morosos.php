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
        Schema::create('morosos', function (Blueprint $table){

            $table->id('id_moroso');
            $table->timestamp('fecha_inicio')->useCurrent();
            $table->date('fecha_fin');
            $table->string('total_intereses');
            $table->string('activo');
            $table->foreignId("id_cliente")->references("id_persona")->on("personas")->onDelete("restrict")->onUpdate("restrict");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('morosos');
    }
};
