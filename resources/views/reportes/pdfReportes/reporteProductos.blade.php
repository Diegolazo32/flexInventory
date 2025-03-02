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

    @if ($productos->isEmpty())
    <div class="card-body">
        <h3>No se encontraron resultados con los parametros seleccionados</h3>
    </div>
    @else
    <div class="card-text">
        <h3>Reporte de productos</h3>

        <table ref="table" class="table" style="border: solid 1px black; width: 100%; text-align: center;">
            <thead>
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Precio de compra</th>
                    <th scope="col">Precio de venta</th>
                    <th scope="col">Proveedor</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                <tr>
                    <td>{{ $producto->codigo }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>${{ $producto->precioCompra }}</td>
                    <td>${{ $producto->precioVenta }}</td>
                    <td>{{ $proveedores->where('id', $producto->proveedor)->first()->nombre }}</td>
                    <td>{{ $producto->stock }}</td>
                    <td>
                        ${{ number_format($producto->precioVenta * $producto->stock, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="1">Totales</td>
                    <td colspan="1">-</td>
                    <td colspan="1">${{ $productos->sum('precioCompra') }}</td>
                    <td colspan="1">${{ $productos->sum('precioVenta') }}</td>
                    <td colspan="1">-</td>
                    <td colspan="1"> {{ $stockTotal }} </td>
                    <td colspan="1">Total: ${{ $sumaTotal }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    @endif

</body>

</html>
