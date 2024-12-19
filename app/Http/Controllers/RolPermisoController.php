<?php

namespace App\Http\Controllers;

use App\Models\rolPermiso;
use Illuminate\Http\Request;

class RolPermisoController extends Controller
{
    public function getAllRolPermiso()
    {
        $rolpermiso = rolPermiso::all();
        return response()->json($rolpermiso);
    }

    public function getPermisosByRol($id)
    {
        $rolpermiso = rolPermiso::where('rol', $id)->get();
        return response()->json($rolpermiso);
    }
}
