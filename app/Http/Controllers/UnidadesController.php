<?php

namespace App\Http\Controllers;

use App\Models\unidades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnidadesController extends Controller
{

    public function checkRole()
    {
        if (Auth::user()->rol != 1) {
            flash('No tienes permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }
    }

    public function index()
    {
        return view('unidades.index');
    }

    public function getAllUnidades()
    {
        $unidades = unidades::all();

        return response()->json($unidades);
    }

    public function store(Request $request)
    {
        $this->checkRole();

        //dd($request->all());

        $request->validate(
            [
                'descripcion' => 'required|unique:unidades',
                'abreviatura' => 'required|unique:unidades',
            ],
            [
                'descripcion.required' => 'La descripción es requerida',
                'descripcion.unique' => 'La descripción ya existe',
                'abreviatura.required' => 'La abreviatura es requerida',
                'abreviatura.unique' => 'La abreviatura ya existe',
            ]
        );

        try {
            $unidades = new unidades();
            $unidades->descripcion = $request->descripcion;
            $unidades->abreviatura = $request->abreviatura;
            $unidades->estado = 1;
            $unidades->save();

            return response()->json(['success' => 'Unidad creada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear la unidad']);
        }
    }

    public function update(Request $request)
    {
        $this->checkRole(); 

        $request->validate(
            [
                'descripcion' => 'required',
                'abreviatura' => 'required',
            ],
            [
                'descripcion.required' => 'La descripción es requerida',
                'abreviatura.required' => 'La abreviatura es requerida',
            ]
        );

        try {
            $unidades = unidades::find($request->id);
            $unidades->descripcion = $request->descripcion;
            $unidades->abreviatura = $request->abreviatura;
            $unidades->estado = $request->estado;
            $unidades->save();

            return response()->json(['success' => 'Unidad actualizada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar la unidad']);
        }
    }

    public function delete(Request $request)
    {
        $this->checkRole();

        try {
            $unidades = unidades::find($request->id);
            $unidades->delete();

            return response()->json(['success' => 'Unidad eliminada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la unidad']);
        }
    }
}
