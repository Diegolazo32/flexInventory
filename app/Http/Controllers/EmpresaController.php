<?php

namespace App\Http\Controllers;

use App\Models\empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    public function index()
    {
        return view('empresa.index');
    }

    public function getAllEmpresa()
    {
        $empresas = empresa::all();
        return response()->json($empresas);
    }

    public function update(Request $request)
    {

        $request->validate([
            'logo' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {

            if ($request->id == 0 || $request->id == null) {
                $empresa = new empresa();
            } else {
                $empresa = empresa::find($request->id);
            }

            if ($request->hasFile('logo')) {
                // Obtener el archivo subido
                $documento = $request->file('logo');

                $extension = $documento->getClientOriginalExtension(); // Obtener la extensión del archivo
                $nombreArchivo = 'logo_empresa' . '.' . $extension; // Nombre del archivo
                $rutaLocal = 'logo/' . $nombreArchivo;

                //Si el archivo ya existe, eliminarlo
                if (Storage::disk('local')->exists($rutaLocal)) {
                    Storage::disk('local')->delete($rutaLocal);
                }

                // Guardar el archivo en el almacenamiento local
                $documento->storeAs('logo', $nombreArchivo, 'local');

                // Copiar el archivo al almacenamiento público
                $rutaPublica = 'logo/' . $nombreArchivo;
                Storage::disk('public')->put($rutaPublica, file_get_contents(storage_path('app/' . $rutaLocal)));
            } else {
                $rutaLocal = 'logo/logo_empresa.jpg';
            }

            $empresa->nombre = $request->nombre;
            $empresa->direccion = $request->direccion;
            $empresa->telefono = $request->telefono;
            $empresa->email = $request->email;
            $empresa->NIT = $request->NIT;
            $empresa->NRC = $request->NRC;
            $empresa->giro = $request->giro;
            $empresa->nit_representante = $request->nitRepresentante;
            $empresa->telefono_representante = $request->telefonoRepresentante;
            $empresa->email_representante = $request->emailRepresentante;
            $empresa->representante = $request->representante;
            $empresa->dui = $request->dui;
            $empresa->logo = $rutaLocal;

            $empresa->save();

            return response()->json(['success' => 'Empresa guardada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function empresaName()
    {

        try {
            $empresa = empresa::first();
            return response()->json($empresa);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
