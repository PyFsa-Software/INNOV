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
        Schema::table('usuarios', function (Blueprint $table) {
            $table->foreignId("id_persona")->references("id_persona")->on("personas")->onDelete("restrict")->onUpdate("restrict");
            $table->foreignId("id_perfil")->references("id_perfil")->on("perfiles")->onDelete("restrict")->onUpdate("restrict");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
             $table->dropForeign(['id_persona']);
             $table->dropForeign(['id_perfil']);
             $table->dropColumn('id_persona');
             $table->dropColumn('id_perfil');
        });
    }
};