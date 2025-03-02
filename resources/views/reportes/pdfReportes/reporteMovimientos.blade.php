<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de productos</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 15px;
        }

        .table th,
        .table td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .logo {
            /*Round image*/
            border-radius: 50%;
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
    </style>

</head>

<body>

    <div style="display: flex;">

        <div>

            <div style="justify-content: space-between; width: 100%; align-items:baseline;">
                <h1>{{ $empresa->nombre ?? '-' }} </h1>
            </div>

        </div>

        <h3>
            {{ $empresa->direccion ?? '-' }} ||
            {{ $empresa->telefono ?? '-' }} ||
            {{ $fecha }} ||
            Generado por: {{ Auth::user()->nombre . ' ' . Auth::user()->apellido }}
        </h3>
        <hr>
    </div>

    @if ($movimientos->isEmpty())
    <div class="card-body">
        <h3>No se encontraron resultados con los parametros seleccionados</h3>
    </div>
    @else
    <div class="card-text">
        <h3>Reporte de productos</h3>

        <table ref="table" class="table" style="border: solid 1px black; width: 100%; text-align: center;">
            <thead>
                <tr>
                    <th scope="col">Correlativo</th>
                    <th scope="col">Producto</th>
                    <th scope="col">Accion</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Observacion</th>
                    <th scope="col">Inventario</th>
                    <th scope="col">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movimientos as $movimiento)
                <tr>
                    <td>{{ $movimiento->id }}</td>
                    <td>{{ $movimiento->producto }}</td>
                    <td>{{ $acciones[$movimiento->accion]}}</td>
                    <td>{{ $movimiento->cantidad }}</td>
                    <td>{{ $movimiento->observacion }}</td>
                    <td>{{ $movimiento->inventario }}</td>
                    <td>{{ $movimiento->created_at }}</td>
                </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="1">Totales</td>
                    <td colspan="1">-</td>
                    <td colspan="1">-</td>
                    <td colspan="1">-</td>
                    <td colspan="1">-</td>
                    <td colspan="1">-</td>
                    <td colspan="1">-</td>
                </tr>
            </tfoot>
        </table>
    </div>

    @endif

</body>

</html>
