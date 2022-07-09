@extends("maestra")
@section("titulo", "Agregar factura")
@section("contenido")
    <div id="app" class="container" v-cloak>
        <div class="columns">
            <div class="column is-half-tablet">
                <h1 class="is-size-1">Agregar factura</h1>

                <form method="POST" action="">
                    @csrf
                    <div class="field">
                        <label class="label">Cliente</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select v-model="cliente">
                                    <option v-for="cliente in clientes" :value="cliente.id">@{{ cliente.nombre }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <label class="label">Articulo</label>
                    <div class="field is-grouped">
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select v-model="articulo_seleccionado">
                                    <option v-for="articulo in articulos" :value="articulo">@{{ `${articulo.codigo} | ${articulo.descripcion}`}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="control">
                            <input class="input" type="number" min=0 placeholder="Cantidad" v-model="cantidad_seleccionada">
                        </div>
                        <div class="control">
                            <button class="button is-info" type="button" v-on:click="agregarLinea()">
                                +
                            </button>
                        </div>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(linea, index) in lineas_factura">
                                <td>
                                    @{{linea.articulo.codigo}}
                                </td>
                                <td>
                                    @{{linea.articulo.descripcion}}
                                </td>
                                <td>
                                    $@{{new Number(linea.articulo.precio_venta).toFixed(2)}}
                                </td>
                                <td>
                                    @{{linea.cantidad}}
                                </td>
                                <td>
                                    $@{{new Number(linea.articulo.precio_venta * linea.cantidad).toFixed(2)}}
                                </td>
                                <td>
                                    <button class="button is-danger" type="button" v-on:click="removerLinea(index)">
                                        X
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Total: $@{{this.total()}}</th>
                            <th></th>
                        </tfoot>
                    </table>
                    <div v-for="linea in lineas_factura" class="lineas-factura">

                    </div>
                    @include("errores")
                    @include("notificacion")
                    <button class="button is-success mt-2">Guardar</button>
                    <a class="button is-primary" href="{{route("facturas")}}">Ver todas</a>
                </form>
                <br>
            </div>
        </div>
    </div>
    <script src="{{url("/js/facturas/agregar.js")}}"></script>
@endsection
