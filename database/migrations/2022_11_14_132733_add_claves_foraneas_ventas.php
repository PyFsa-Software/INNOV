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
        Schema::table('ventas', function (Blueprint $table) {
            $table->foreignId("id_parcela")->references("id_parcela")->on("parcelas")->onDelete("restrict")->onUpdate("restrict");
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
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign(['id_parcela']);
            $table->dropForeign(['id_cliente']);
            $table->dropColumn('id_parcela');
            $table->dropColumn('id_cliente');
        });
    }
};