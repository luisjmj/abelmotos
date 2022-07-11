@extends("maestra")
@section("titulo", "Facturas")
@section("contenido")
    <div class="container">
        <h3 class="is-size-3">Factura ID: {{ $factura->id}} </h3>
        <div class="columns">
            <div class="column is-half">

                <h5><b>Datos del remitente</b></h5>
                <p>
                    <b>Nombre:</b> {{ $factura->remitente->nombre }}<br>
                    <b>Rif:</b> {{ $factura->remitente->rif }}<br>
                    <b>Email:</b> {{ $factura->remitente->email }}
                </p>
            </div>
            <div class="column is-half">
                <h5 class="mt-4" ><b>Datos del Cliente</b></h5>
                <p>
                    <b>Nombre:</b> {{ $factura->cliente->nombre }}<br>
                    <b>Cédula:</b> {{ $factura->cliente->dni }}<br>
                    <b>Dirección:</b> {{ $factura->cliente->direccion }}<br>
                    <b>Email:</b> {{ $factura->cliente->email }}
                </p>
            </div>
        </div>
        <div class="columns">
            <div class="column is-half">
                <h5 class="mt-4" ><b>Datos de la factura</b></h5>
                <p>
                    <b>Fecha:</b> {{ $factura->created_at }}<br>
                    <b>Monto:</b> ${{ $factura->getTotal() }}<br>
                </p>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <h5><b>Detalle de articulos</b></h5>
                <table class="table is-bordered is-striped is-hoverable is-fullwidth">
                    <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($factura->lineas_factura as $linea)
                        <tr>
                            <td>{{ $linea->articulo->codigo }}</td>
                            <td>{{ $linea->articulo->descripcion }}</td>
                            <td>{{ $linea->cantidad }}</td>
                            <td>${{ $linea->getPrecioUnitario() }}</td>
                            <td>${{ $linea->sub_total }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <h5><b>Detalle de pagos</b></h5>
                <table class="table is-bordered is-striped is-hoverable is-fullwidth">
                    <thead>
                    <tr>
                        <th>Modo de pago</th>
                        <th>Monto</th>
                        <th>Tasa de cambio al día</th>
                        <th>Conversión a Dólares al día</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($factura->divisas_factura as $divisa_factura)
                        <tr>
                            <td>{{ $divisa_factura->divisa->nombre }}</td>
                            <td>{{ $divisa_factura->monto }}</td>
                            <td>{{ $divisa_factura->tasa}} </td>
                            <td>${{ $divisa_factura->getCambioAlDolar() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <h5 class="is-size-5 has-text-right"><b>Total:</b> ${{ $factura->getTotal() }}</h5>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <a href="{{ route('facturas') }}" class="button is-primary">Volver</a>
            </div>
        </div>
    </div>
@endsection