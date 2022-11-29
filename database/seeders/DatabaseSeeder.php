<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Perfiles;
use App\Models\Personas;
use App\Models\Usuario;
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

        Perfiles::create([
            'descripcion' => 'Administrador',
        ]);

        Personas::create([
            'nombre' => 'Daniel',
            'apellido' => 'Rojas',
            'dni' => '43546534',
            'cuit' => '34432343445',
            'domicilio' => 'asdasdas',
            'celular' => '3454546576',
            'correo' => 'asdasd@asdas.com',
            'cliente' => 0,
        ]);

        Personas::create([
            'nombre' => 'Marcos',
            'apellido' => 'Franco',
            'dni' => '43711098',
            'cuit' => '12437110988',
            'domicilio' => 'klñklkñ',
            'celular' => '3740981212',
            'correo' => 'marcosd@gmail.com',
            'cliente' => 1,
        ]);
        Personas::create([
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

    }
}