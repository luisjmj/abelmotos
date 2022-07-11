@extends("maestra")
@section("titulo", "Facturas")
@section("contenido")
    <div id="app" class="container" v-cloak>
        <div class="columns">
            <div class="column">
                <div class="notification">
                    <div class="columns is-vcentered">
                        <div class="column">
                            @verbatim
                                <h4 class="is-size-4">Facturas ({{paginacion.total}})</h4>
                            @endverbatim
                        </div>
                        <div class="column">
                            <div class="field has-addons">
                                <div class="control">
                                    <input :readonly="deberiaDeshabilitarBusqueda" v-model="busqueda" @keyup="buscar()"
                                           class="input " type="text"
                                           placeholder="Buscar por nombre">
                                </div>
                                <div class="control">
                                    <button :disabled="deberiaDeshabilitarBusqueda || !busqueda" v-show="!this.busqueda"
                                            @click="buscar()" class="button is-info"
                                            :class="{'is-loading': buscando}">
                                            <span class="icon is-small">
                                              <i class="fa fa-search"></i>
                                            </span>
                                    </button>

                                    <button v-show="this.busqueda" @click="limpiarBusqueda()" class="button is-info"
                                            :class="{'is-loading': buscando}">
                                            <span class="icon is-small">
                                              <i class="fa fa-times"></i>
                                            </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field is-grouped is-pulled-right">
                                <div class="control">
                                    <a href="{{route("formularioAgregarFactura")}}" class="button is-success">Agregar</a>
                                </div>
                                <div class="control">
                                    @verbatim
                                        <transition name="bounce">
                                            <button @click="eliminarMarcados()" v-show="numeroDeElementosMarcados > 0"
                                                    class="button is-warning"
                                                    :class="{'is-loading': cargando.eliminandoMuchos}">
                                                Eliminar ({{numeroDeElementosMarcados}})
                                            </button>
                                        </transition>
                                    @endverbatim
                                </div>
                            </div>
                            &nbsp;
                        </div>
                    </div>
                </div>
                <div v-show="cargando.lista" class="notification is-info has-text-centered">
                    <h3 class="is-size-3">Cargando</h3>
                    <div>
                        <h1 class="icono-gigante">
                            <i class="fas fa-spinner fa-spin"></i>
                        </h1>
                    </div>
                    <p class="is-size-5">
                        Por favor, espera un momento
                    </p>
                </div>
                <transition name="fade">
                    <div v-show="facturas.length <= 0 && !busqueda && !cargando.lista"
                         class="notification is-info has-text-centered">
                        <h3 class="is-size-3">Todavía no hay facturas</h3>
                        <div>
                            <h1 class="icono-gigante">
                                <i class="fas fa-box-open"></i>
                            </h1>
                        </div>
                        <p class="is-size-5">
                            Parece que no has agregado ninguna factura. Registra una haciendo click en el botón
                            <strong>Agregar</strong>
                        </p>
                    </div>
                </transition>
                <transition name="fade">
                    <div v-show="facturas.length <= 0 && busqueda && !cargando.lista"
                         class="notification is-warning has-text-centered">
                        <h3 class="is-size-3">No hay resultados</h3>
                        <div>
                            <h1 class="icono-gigante">
                                <i class="fas fa-search"></i>
                            </h1>
                        </div>
                        <p class="is-size-5">
                            No hay resultados que coincidan con tu búsqueda
                        </p>
                    </div>
                </transition>
                @include("errores")
                @include("notificacion")
                <div>
                    <table v-show="facturas.length > 0 && !cargando.lista"
                           class="table is-bordered is-striped is-hoverable is-fullwidth">
                        <thead>
                        <tr>
                            <th>
                                <button @click="onBotonParaMarcarClickeado()" class="button"
                                        :class="{'is-info': numeroDeElementosMarcados > 0}">
                                    <span class="icon is-small">
                                        <i class="fa fa-check"></i>
                                    </span>
                                </button>
                            </th>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Ver</th>
                            <th>Anular</th>
                        </tr>
                        </thead>
                        <tbody>
                        @verbatim
                            <tr v-for="factura in facturas">
                                <td>
                                    <button @click="invertirEstado(factura)" class="button"
                                            :class="{'is-info': factura.marcado}">
                                    <span class="icon is-small">
                                        <i class="fa fa-check"></i>
                                    </span>
                                    </button>
                                </td>
                                <td>{{factura.id}}</td>
                                <td>{{factura.cliente.nombre}}</td>
                                <td>{{factura.created_at}}</td>
                                <td>
                                    <a class="button is-info" :href="'/facturas/' + factura.id">
                                        <span class="icon is-small">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </a>
                                </td>
                                <td>
                                    <button @click="eliminar(factura)" class="button is-danger"
                                            :class="{'is-loading': factura.eliminando}">
                                    <span class="icon is-small">
                                        <i class="fa fa-trash"></i>
                                    </span>
                                    </button>
                                </td>
                            </tr>
                        @endverbatim
                        </tbody>
                    </table>
                    <nav v-show="paginacion.ultima > 1" class="pagination" role="navigation" aria-label="pagination">
                        <a :disabled="!puedeRetrocederPaginacion()" @click="retrocederPaginacion()"
                           class="pagination-previous">Anterior</a>
                        <a :disabled="!puedeAvanzarPaginacion()" @click="avanzarPaginacion()" class="pagination-next">Siguiente
                            página</a>
                        @verbatim
                            <ul class="pagination-list">
                                <li v-for="pagina in paginas">
                                    <a v-if="!pagina.puntosSuspensivos" @click="irALaPagina(pagina.numero)"
                                       class="pagination-link"
                                       :class="{'is-current':pagina.numero === paginacion.actual}">{{pagina.numero}}</a>
                                    <span class="pagination-ellipsis" v-else>&hellip;</span>
                                </li>

                            </ul>
                        @endverbatim
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <script src="{{url("/js/facturas/mostrar.js?q=") . time()}}"></script>
@endsection
