<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\DetallePlan;
use App\Models\DetalleVenta;
use App\Models\Lote;
use App\Models\Parcela;
use App\Models\Perfil;
use App\Models\Persona;
use App\Models\Precio;
use App\Models\Usuario;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Perfil::create([
            'descripcion' => 'Administrador',
        ]);

        Persona::create([
            'nombre' => 'Daniel',
            'apellido' => 'Rojas',
            'dni' => '43546534',
            'cuit' => '34432343445',
            'domicilio' => 'asdasdas',
            'celular' => '3454546576',
            'correo' => 'asdasd@asdas.com',
            'cliente' => 0,
        ]);

        Persona::create([
            'nombre' => 'Marcos',
            'apellido' => 'Franco',
            'dni' => '43711098',
            'cuit' => '12437110988',
            'domicilio' => 'klñklkñ',
            'celular' => '3740981212',
            'correo' => 'marcosd@gmail.com',
            'cliente' => 1,
        ]);
        Persona::create([
            'nombre' => 'Cliente2',
            'apellido' => 'Cliente2',
            'dni' => '43711094',
            'cuit' => '12437110981',
            'domicilio' => 'kjhk',
            'celular' => '3740981214',
            'correo' => 'Cliente2d@gmail.com',
            'cliente' => 1,
        ]);

        // USUARIO POR DEFECTO
        Usuario::create([
            'nombre_usuario' => 'admin',
            'contrasenia' => bcrypt('admin'),
            "id_perfil" => 1,
            "id_persona" => 1,
        ]);

        Precio::create([
            'precio_bercomat' => '1700',
            'precio_sancayetano' => '1800',
            'precio_rio_colorado' => '1900',
            'precio_promedio' => '1800',
        ]);

        // LOTES

        Lote::create([
            'nombre_lote' => 'Lote 1',
            'superficie_lote' => '2000',
            'cantidad_manzanas' => '4',
            'ubicacion' => 'Barrio las orquideas',
        ]);

        Lote::create([
            'nombre_lote' => 'Lote 2',
            'superficie_lote' => '3000',
            'cantidad_manzanas' => '6',
            'ubicacion' => 'Barrio eva peron',
        ]);

        Parcela::create([
            'descripcion_parcela' => 'Parcela 1',
            'superficie_parcela' => '1',
            'manzana' => '1',
            'cantidad_bolsas' => '1166',
            'ancho' => '200',
            'largo' => '200',
            'disponible' => '0',
            'id_lote' => '1',
        ]);

        Parcela::create([
            'descripcion_parcela' => 'Parcela 2',
            'superficie_parcela' => '2',
            'manzana' => '2',
            'cantidad_bolsas' => '1200',
            'ancho' => '230',
            'largo' => '240',
            'id_lote' => '1',
        ]);

        Parcela::create([
            'descripcion_parcela' => 'Parcela 3',
            'superficie_parcela' => '3',
            'manzana' => '2',
            'cantidad_bolsas' => '1300',
            'ancho' => '260',
            'largo' => '260',
            'id_lote' => '2',
        ]);

        Venta::create([
            'cuotas' => '120',
            'cuotas' => '120',
            'precio_total_terreno' => '2200000',
            'cuota_mensual_bolsas_cemento' => '9,7',
            'precio_total_entrega' => '100000',
            'precio_final' => '2100000',
            'id_parcela' => '1',
            'id_cliente' => '2',
        ]);

        DetallePlan::create([
            'fecha_desde' => Carbon::now()->format('Y-m-d'),
            'fecha_hasta' => Carbon::now()->addMonths(6)->format('Y-m-d'),
            'valor_cuota' => '17.460',
            'id_venta' => '1',
        ]);

        DetalleVenta::create([
            'numero_cuota' => '1',
            'fecha_maxima_a_pagar' => '2023-01-21',
            'total_estimado_a_pagar' => '17.460',
            'total_intereses' => '',
            'fecha_pago' => '',
            'total_pago' => '',
            'id_venta' => '1',
        ]);
    }
}