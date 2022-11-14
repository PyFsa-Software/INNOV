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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_usuario');
            // $table->string('email')->unique();
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('contrasenia');
            $table->string('estado_usuario')->default('1');
            $table->foreignId("id_persona")->references("id_persona")->on("personas")->onDelete("restrict")->onUpdate("restrict"); 
            $table->foreignId("id_perfil")->references("id_perfil")->on("perfiles")->onDelete("restrict")->onUpdate("restrict");
            $table->rememberToken();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
};
