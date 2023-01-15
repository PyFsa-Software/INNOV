<?php

namespace App\Http\Controllers;

use App\Exports\PlanillaExport;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportesController extends Controller
{

    public function planilla()
    {
        return view('reportes.planilla.index');
    }

    // public function exportarPlanilla(Request $request)
    // {

    //     dd($request->all());

    //     return Excel::download(new PlanillaExport(), 'users.xlsx');
    // }
}