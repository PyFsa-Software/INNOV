<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Auth extends Controller
{
   public function username()
    {
        return 'nombre_usuario';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('login.login');
    }

    public function loguearse(Request $request)
    {



        if (auth()->attempt(['nombre_usuario' => $request->usuario, 'contrasenia' => $request->password])) {


            return redirect()->route('inicio');
        }

        return back()->withErrors(['mensaje' => 'Correo o contraseña incorrectos']);

    }

    public function logout(Request $request)
    {
        auth()->logout();
        return redirect()->route('inicioSesion.index');

    }
}