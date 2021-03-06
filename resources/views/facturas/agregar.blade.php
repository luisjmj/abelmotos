@extends("maestra")
@section("titulo", "Agregar factura")
@section("contenido")
    <div id="app" class="container" v-cloak>
        <div class="columns">
            <div class="column is-three-fifths">
                <h1 class="is-size-1">Agregar factura</h1>

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
                        <input class="input" type="number" value="1" min=1 placeholder="Cantidad" v-model="cantidad_seleccionada">
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
                                <div class="field">
                                    <div class="control">
                                        <input class="input" type="number" min=1 placeholder="Cantidad" v-model="linea.cantidad">
                                    </div>
                                </div>
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
                @include("errores")
                @include("notificacion")
                @verbatim
                    <button :class="{'is-loading':cargando}" @click="guardar()" class="button is-success">Guardar
                    </button>
                @endverbatim
                <a class="button is-primary" href="{{route("facturas")}}">Ver todas</a>

                <div v-show="errores.length > 0" class="notification is-danger">
                    <h5 class="is-size-5">Por favor, valida los siguientes errores:</h5>
                    <ul>
                        <li v-for="error in errores">
                            @{{error}}
                        </li>
                    </ul>
                </div>
                <div v-show="mostrar.aviso" class="notification" :class="aviso.tipo">
                    @{{aviso.mensaje}}
                </div>
            </div>

            <div class="column is-two-fifths">
                <h2 class="is-size-3">Agregar m??todos de pago</h2>
                <label class="label">M??todo de pago</label>
                <div class="field is-grouped">
                    <div class="control">
                        <div class="select is-full-width">
                            <select v-model="payment_method">
                                <option v-for="payment_method in payment_methods" :value="payment_method">@{{ payment_method.nombre }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="control">
                        <input class="input" type="number" min=1 placeholder="Monto" v-model="payment_amount">
                    </div>
                    <div class="control">
                        <button class="button is-info" type="button" v-on:click="agregarMetodoPago()">
                            +
                        </button>
                    </div>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>M??todo de pago</th>
                            <th>Monto</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(selected_payment_method, index) in selected_payment_methods">
                            <td>
                                @{{selected_payment_method.payment_method.nombre}}
                            </td>
                            <td>
                                <div class="field">
                                    <div class="control">
                                        <input type="number" class="input" placeholder="Monto" v-model="selected_payment_method.payment_amount">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <button class="button is-danger" type="button" v-on:click="removerMetodoPago(index)">
                                    X
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <th></th>
                        <th>Total (en D??lares): $@{{totalMetodosPago()}}</th>
                        <th></th>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <script src="{{url("/js/facturas/agregar.js")}}"></script>
@endsection
