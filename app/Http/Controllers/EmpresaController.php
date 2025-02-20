<?php

namespace App\Http\Controllers;

use App\Models\empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{

    private $rolPermisoController;

    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(2);

        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }

        $empresa = empresa::first();

        if ($empresa == null) {
            $empresa = new empresa();
            $empresa->nombre = 'Flex Inventory';
            $empresa->logo = 'logo/empresa_logo.jpg';
        }

        return view('empresa.index', compact('empresa'));
    }

    public function getAllEmpresa()
    {

        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(4);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $empresas = empresa::all();
        return response()->json($empresas);
    }

    public function update(Request $request)
    {

        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(3);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $request->validate([
            'nombre' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'email' => 'required',
            'NIT' => 'required',
            'NRC' => 'required',
            'giro' => 'required',
            'nitRepresentante' => 'required',
            'telefonoRepresentante' => 'required',
            'emailRepresentante' => 'required',
            'representante' => 'required',
            'dui' => 'required',
            'logo' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'cuentaContable' => 'required',
            'valorIVA' => 'required'
        ]);

        try {

            if ($request->firstTime) {
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
                $logoAnterior = $empresa->logo;

                if ($logoAnterior != null) {
                    // Eliminar el archivo anterior
                    Storage::disk('local')->delete($logoAnterior);
                    Storage::disk('public')->delete($logoAnterior);
                }

                // Guardar el archivo en el almacenamiento local
                $documento->storeAs('logo', $nombreArchivo, 'local');

                // Copiar el archivo al almacenamiento público
                $rutaPublica = 'logo/' . $nombreArchivo;
                Storage::disk('public')->put($rutaPublica, file_get_contents(storage_path('app/' . $rutaLocal)));


                //Generar el base64 del archivo
                $base64 = base64_encode(file_get_contents(storage_path('app/' . $rutaLocal)));

            } else {

                //Buscar un logo existente
                $empresa = empresa::find($request->id);
                $rutaLocal = $empresa->logo;

                //Generar el base64 del archivo
                $base64 = base64_encode(file_get_contents(storage_path('app/' . $rutaLocal)));

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
            $empresa->logo = $rutaPublica ?? $empresa->logo;
            $empresa->cuentaContable = $request->cuentaContable;
            $empresa->valorIVA = $request->valorIVA;

            $empresa->save();

            return response()->json(['success' => 'Empresa guardada correctamente, aplicando cambios, espere...']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function empresaName()
    {

        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(3);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        try {
            $empresa = empresa::first();
            return response()->json($empresa);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
