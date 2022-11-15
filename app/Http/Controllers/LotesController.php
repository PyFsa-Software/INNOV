<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LotesController extends Controller
{
    public function Lotes()
    {

        return view('lotes.index');


    }
}