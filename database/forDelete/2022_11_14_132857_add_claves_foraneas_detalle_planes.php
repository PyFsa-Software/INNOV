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
        Schema::table('detalle_planes', function (Blueprint $table) {
            $table->foreignId("id_venta")->references("id_venta")->on("ventas")->onDelete("restrict")->onUpdate("restrict");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detalle_planes', function (Blueprint $table) {
            $table->dropForeign(['id_venta']);
            $table->dropColumn('id_venta');
        });
    }
};