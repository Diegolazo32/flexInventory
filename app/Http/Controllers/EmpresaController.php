<?php

namespace App\Http\Controllers;

use App\Models\empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{

    private $rolPermisoController;

    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(2);
        $auditoriaController = new AuditoriaController();

        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de acceso a la pantalla de empresa', 'Empresa', '-', '-');
            return redirect()->route('dashboard');
        }

        $empresa = empresa::first();

        if ($empresa == null) {
            $empresa = new empresa();
            $empresa->nombre = 'Flex Inventory';
            $empresa->logo = 'logo/empresa_logo.jpg';
        }

        $auditoriaController->registrarEvento(Auth::user()->nombre, 'Ingreso a la pantalla de empresa', 'Empresa', '-', '-');
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
        $auditoriaController = new AuditoriaController();

        if (!$permiso) {
            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de modificar empresa sin permiso', 'Empresa', '-', '-');
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

            $empresa = $request->firstTime ? new empresa() : empresa::find($request->id);

            if (!$empresa) {
                return response()->json(['error' => 'Empresa no encontrada.'], 404);
            }

            $oldEmpresa = $request->firstTime ? '-' : clone $empresa;

            // Manejo del logo
            if ($request->hasFile('logo')) {
                $logoAnterior = $empresa->logo ?? '-';
                $nombreArchivo = 'logo_empresa.' . $request->file('logo')->getClientOriginalExtension();
                $rutaPublica = 'logo/' . $nombreArchivo;

                // Eliminar logo anterior si existía
                if ($empresa->logo) {
                    Storage::disk('public')->delete($empresa->logo);
                }

                // Guardar el nuevo logo en el disco público
                $request->file('logo')->storeAs('logo', $nombreArchivo, 'public');
                $empresa->logo = $rutaPublica;

                $auditoriaController->registrarEvento(Auth::user()->nombre, 'Modificación de logo', 'Empresa', $logoAnterior, $rutaPublica);
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
            $empresa->firstTime = false;
            $empresa->save();

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Modificación de empresa', 'Empresa', $oldEmpresa, $empresa);
            return response()->json([
                'success' => 'Empresa guardada correctamente, aplicando cambios, espere...',
                'logo' => asset('storage/' . $empresa->logo)
            ]);

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
