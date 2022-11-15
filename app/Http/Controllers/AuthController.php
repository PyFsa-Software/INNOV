<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
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

        try {
            if (auth()->attempt(['nombre_usuario' => $request->usuario, 'password' => $request->password])) {


                return redirect()->route('inicio');
            }

            return back()->withErrors(['mensaje' => 'Correo o contraseña incorrectos']);

        } catch (\Exception$e) {

            $mensaje = $e->getMessage();


        }


    }

    public function logout(Request $request)
    {
        Session::flush();

        Auth::logout();
        return redirect()->route('inicioSesion.index');

    }
}
