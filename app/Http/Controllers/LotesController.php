<?php

namespace App\Http\Controllers;

use App\Models\lotes;
use App\Models\productos;
use Illuminate\Http\Request;

class LotesController extends Controller
{

    public function index()
    {
        return view('lotes.index');
    }

    public function storeWithProduct($producto, $request, $inventario)
    {
        //Crer lote
        $lote = new lotes();
        $lote->codigo = 'L' . $producto->codigo . $producto->id . '-' . date('Y-m-d') . date('H:i:s');
        $lote->numero = 1;
        $lote->cantidad = $request->stock;
        $lote->fechaVencimiento = $request->fechaVencimiento;
        $lote->producto = $producto->id;
        $lote->estado = 1;
        $lote->inventario = $inventario->id;
        $lote->save();
    }

    public function getAllLotes()
    {
        $lotes = lotes::all();
        return response()->json($lotes);
    }

    public function getLotesByInventario($id)
    {
        $lotes = lotes::where('inventario', $id)->get();
        return response()->json($lotes);
    }

    public function getLotesByProduct($id)
    {
        $lotes = lotes::where('producto', $id)->get();
        return response()->json($lotes);
    }

    public function actualizarLotes(Request $request)
    {

        $lotes = $request->lotes;

        try {
            foreach ($lotes as $lote) {
                $loteUpdate = lotes::find($lote['id']);
                //$loteUpdate->cantidad = $lote['cantidad'];
                $loteUpdate->fechaVencimiento = $lote['fechaVencimiento'];
                $loteUpdate->save();

                //Asignar fecha de vencimiento mas proxima al producto
                $producto = productos::find($loteUpdate->producto);
                $lotes = lotes::where('producto', $producto->id)->get();
                $fechaVencimiento = null;

                foreach ($lotes as $lote) {
                    if ($fechaVencimiento == null) {
                        $fechaVencimiento = $lote->fechaVencimiento;
                    } else {
                        if ($lote->fechaVencimiento < $fechaVencimiento) {
                            $fechaVencimiento = $lote->fechaVencimiento;
                        }
                    }
                }

                $producto->fechaVencimiento = $fechaVencimiento;
                $producto->save();
            }

            return response()->json(['success' => 'Lotes actualizados correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar los lotes']);
        }
    }
}
