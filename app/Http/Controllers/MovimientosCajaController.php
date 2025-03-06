<?php

namespace App\Http\Controllers;

use App\Models\caja;
use App\Models\inventario;
use App\Models\movimientosCaja;
use App\Models\turnos;
use Illuminate\Http\Request;

class MovimientosCajaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'turno' => 'required',
            'tipo' => 'required',
            'monto' => 'required',
            'descripcion' => 'required',
            'caja' => 'required',
        ]);

        //turno, tipo, valor, descripcion, usuario, caja, inventario
        try {
            $inventario = inventario::where('estado', 3)->first();
            $caja = caja::where('nombre', $request->caja)->first();
            $turno = turnos::find($request->turno);
            $usuario = turnos::find($request->turno)->vendedor;

            $movimiento = new movimientosCaja();
            $movimiento->turno = $request->turno;
            $movimiento->tipo = $request->tipo;
            $movimiento->valor = $request->monto;
            $movimiento->descripcion = $request->descripcion;
            $movimiento->usuario = $usuario;
            $movimiento->caja = $caja->id;
            $movimiento->inventario = $inventario->id;
            $movimiento->save();

            //Actualizar el total de entradas y salidas del turno
            if ($request->tipo == 1) {
                $turno->totalEntradas += $request->monto;
            } else {
                $turno->totalSalidas += $request->monto;
            }

            //dd($turno->montoInicial);

            //Calcular montoCierre (montoCierre = MontoApertura + totalEntradas - totalSalidas)
            $turno->montoCierre = $turno->montoInicial + $turno->totalEntradas - $turno->totalSalidas;
            $turno->save();


            return response()->json(['success' => 'Movimiento de caja registrado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al registrar el movimiento de caja']);
        }


    }
}
