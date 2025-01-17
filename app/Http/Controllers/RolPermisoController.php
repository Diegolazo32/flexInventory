<?php

namespace App\Http\Controllers;

use App\Models\rolPermiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function checkPermisos($permisoId)
    {
        try {
            //Verificar que este autenticado
            if (!Auth::check()) {
                return false;
            }

            //Verificar que tenga permisos
            $rol = Auth::user()->rol;
            $permiso = rolPermiso::where('rol', $rol)->where('permiso', $permisoId)->first();

            if (!$permiso) {
                return false;
            }

            return true;

        } catch (\Exception $e) {
            
            return false;
        }
    }

    public function permisosRolAuth()
    {

        $this->checkPermisos(46);

        $rol = Auth::user()->rol;
        $permisos = rolPermiso::where('rol', $rol)->get();

        return response()->json($permisos);
    }
}
