<?php

namespace App\Http\Controllers;

use App\Models\Grupos;
use Illuminate\Http\Request;

class GruposController extends Controller
{
    public function getAllGrupos()
    {
        $grupos = Grupos::orderBy('nombre', 'asc')->get();
        return response()->json($grupos);
    }
}
