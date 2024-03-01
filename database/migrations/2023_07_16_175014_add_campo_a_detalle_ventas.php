<?php

use App\Enums\FormasPago;
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
        if (!Schema::hasColumn('detalle_ventas', 'forma_pago')) {
            Schema::table('detalle_ventas', function (Blueprint $table) {
                // add column forma_pago with enum FormasPago
                $table->enum('forma_pago', [
                    FormasPago::EFECTIVO->value,
                    FormasPago::TRANSFERENCIA->value,
                    FormasPago::DEBITO->value
                ])->nullable();

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
        Schema::table('detalle_ventas', function (Blueprint $table) {
            // drop column forma_pago
            $table->dropColumn('forma_pago');
        });
    }
};
