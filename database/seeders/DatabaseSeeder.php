<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Personas;
use App\Models\Perfiles;
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

        Perfiles::create([
            'descripcion'=>'Administrador',
        ]);

        Personas::create([
            'nombre'=>'Daniel',
            'apellido'=>'Rojas',
            'dni'=>'43546534',
            'cuit'=>'34432343445',
            'domicilio'=>'asdasdas',
            'celular'=>'3454546576',
            'correo'=>'asdasd@asdas.com'
        ]);
        
        // USUARIO POR DEFECTO
        Usuario::create([
            'nombre_usuario' => 'admin',
            'contrasenia' => bcrypt('admin'),
            "id_perfil" => 1,
            "id_persona" => 1,
        ]);

    }
}
