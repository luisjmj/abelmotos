@extends("maestra")
@section("titulo", "Artículos")
@section("contenido")
    <div id="app" class="container" v-cloak>
        <div class="columns">
            <div class="column">
                <div class="notification">
                    <div class="columns is-vcentered">
                        <div class="column">
                            @verbatim
                                <h4 class="is-size-4">Artículos ({{paginacion.total}})</h4>
                            @endverbatim
                        </div>
                        <div class="columns" style="margin-top: 1em">
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

                                        <button v-show="this.busqueda" @click="limpiarBusqueda()" class="button is-danger"
                                                :class="{'is-loading': buscando}">
                                            <span class="icon is-small">
                                                <i class="fa fa-times"></i>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="column">
                                <div class="field has-addons">
                                    <div class="control">
                                        <input @focus="mostrar.proveedores = true" v-model="busquedaProveedor"
                                                @keyup="buscarProveedor()" class="input" type="text"
                                                placeholder="Buscar proveedores">
                                    </div>
                                    <div class="control">
                                        <button :disabled="deberiaDeshabilitarBusquedaProveedor || !busquedaProveedor" v-show="!this.busquedaProveedor"
                                            class="button is-info":class="{'is-loading': buscandoProveedor}">
                                            <span class="icon is-small">
                                                <i class="fa fa-search"></i>
                                            </span>
                                        </button>

                                        <button v-show="this.busquedaProveedor" @click="limpiarBusquedaProveedor()" class="button is-danger" :class="{'is-loading': buscandoProveedor}">
                                            <span class="icon is-small">
                                                <i class="fa fa-times"></i>
                                            </span>
                                        </button>
                                    </div>
                                    
                                </div>
                                @verbatim
                                <a v-show="mostrar.proveedores && busquedaProveedor" @click="seleccionaProveedor(proveedor)"
                                    v-for="proveedor in proveedores"
                                    class="panel-block" :class="{'is-active': proveedor.id === proveedorSeleccionado.id}">
                                <span class="panel-icon">
                                    <i class="fas fa-building" aria-hidden="true"></i>
                                </span>
                                    {{proveedor.nombre}}
                                </a>
                                <div v-show="!mostrar.proveedores && proveedorSeleccionado.id" class="notification is-info">
                                    <h4 class="is-size-6">{{proveedorSeleccionado.nombre}}</h4>
                                </div>
                                @endverbatim
                            </div>
                        </div>
                        <div class="column">
                            <div class="field is-grouped is-pulled-right">
                                <div class="control">
                                    <a href="{{route("formularioAgregarArticulo")}}"
                                       class="button is-success">Agregar</a>
                                </div>
                                <div class="control">
                                    <a href="{{route("formularioAgregarArticulo")}}"
                                       class="button is-info">Imprimir códigos</a>
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
                    <div v-show="articulos.length <= 0 && !busqueda && !cargando.lista"
                         class="notification is-info has-text-centered">
                        <h3 class="is-size-3">Todavía no hay artículos</h3>
                        <div>
                            <h1 class="icono-gigante">
                                <i class="fas fa-box-open"></i>
                            </h1>
                        </div>
                        <p class="is-size-5">
                            Parece que no has agregado ningún artículo. Registra uno haciendo click en el botón
                            <strong>Agregar</strong>
                        </p>
                    </div>
                </transition>
                <transition name="fade">
                    <div v-show="articulos.length <= 0 && busqueda && !cargando.lista"
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
                @verbatim
                    <div class="columns" v-for="grupoDeArticulos in articulos">
                        <div class="column  card" v-for="articulo in grupoDeArticulos">
                            <div class="card-image">
                                <img v-if="articulo.fotos.length > 0"
                                     :src="rutaBaseFoto + '/'+articulo.fotos[0].ruta"
                                     :alt="articulo.descripcion">
                            </div>
                            <div class="card-content">
                                <div class="media">

                                    <div class="media-content">
                                        <p class="title is-4">{{articulo.descripcion}}</p>
                                        <p class="subtitle is-6">{{articulo.marca}}&nbsp;|
                                            {{articulo.modelo}}&nbsp;|
                                            {{articulo.serie}}</p>
                                    </div>
                                </div>
                                <div class="content">
                                    <strong>Costo BS: </strong> {{articulo.precio_venta}}<br>
                                    <div v-for="divisa in divisas">
                                        <strong>Costo {{divisa.nombre}}: </strong> {{articulo.precio_venta * divisa.tasa}}<br>
                                    </div>
                                    <strong>No. Factura: </strong> {{articulo.factura}}<br>
                                    <div class="field has-addons">
                                        <p class="control">
                                            <button class="button is-info" @click="administrarFotos(articulo)">
                                                <span class="is-hidden-mobile">
                                                    Administrar fotos&nbsp;&nbsp;
                                                </span>
                                                <span class="icon">
                                                    <i class="fas fa-camera"></i>
                                                </span>
                                            </button>
                                        </p>
                                        <p class="control">
                                            <button class="button is-warning" @click="editar(articulo)">
                                                <span class="is-hidden-mobile">
                                                    Editar&nbsp;&nbsp;
                                                </span>
                                                <span class="icon">
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                            </button>
                                        </p>
                                        <p class="control">
                                            <button class="button is-success" @click="inventario(articulo)">
                                                <span class="is-hidden-mobile">
                                                    Inventario&nbsp;&nbsp;
                                                </span>
                                                <span class="icon">
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                            </button>
                                        </p>
                                        <p class="control">
                                            <button class="button is-danger" @click="eliminar(articulo)">
                                                <span class="is-hidden-mobile">
                                                    Eliminar&nbsp;&nbsp;
                                                </span>
                                                <span class="icon">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                            </button>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endverbatim

                <div v-if="false" class="table-container" style="overflow: visible;">

                    <table v-show="articulos.length > 0 && !cargando.lista"
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
                            <th>Opciones</th>
                            <th>Área</th>
                            <th>Fecha de adquisición</th>
                            <th>Número de factura</th>
                            <th>Descripción</th>
                            <th>Código</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Serie</th>
                            <th>Estado</th>
                            <th>Costo</th>
                            <th>Observaciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @verbatim
                            <tr v-for="articulo in articulos">
                                <td>
                                    <button @click="invertirEstado(articulo)" class="button"
                                            :class="{'is-info': articulo.marcado}">
                                        <span class="icon is-small">
                                            <i class="fa fa-check"></i>
                                        </span>
                                    </button>
                                </td>
                                <td>
                                    <div class="dropdown is-up" :class="{'is-active':articulo.mostrarMenu}">
                                        <div
                                                class="dropdown-trigger">
                                            <button @blur="ocultarMenu(articulo)" @click="alternarMenu(articulo)"
                                                    class="button"
                                                    aria-haspopup="true" aria-controls="dropdown-menu2">
                                                <span class="icon">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </span>
                                            </button>
                                        </div>
                                        <div class="dropdown-menu" id="dropdown-menu2" role="menu">
                                            <div class="dropdown-content">
                                                <div class="dropdown-item">
                                                    <button class="button is-danger" @click="editar(articulo)">
                                                        Eliminar&nbsp;&nbsp;<span class="icon">
                                                            <i class="fas fa-trash"></i>
                                                        </span>
                                                    </button>
                                                </div>
                                                <div class="dropdown-item">
                                                    <button class="button is-warning" @click="editar(articulo)">
                                                        Editar&nbsp;&nbsp;<span class="icon">
                                                            <i class="fas fa-edit"></i>
                                                        </span>
                                                    </button>
                                                </div>

                                                <div class="dropdown-item">
                                                    <button class="button is-info" @click="administrarFotos(articulo)">
                                                        Administrar fotos&nbsp;&nbsp;<span class="icon">
                                                            <i class="fas fa-camera"></i>
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{articulo.area.nombre}}</td>
                                <td>{{articulo.fecha_adquisicion}}</td>
                                <td>{{articulo.factura}}</td>
                                <td>{{articulo.descripcion}}</td>
                                <td>{{articulo.codigo}}</td>
                                <td>{{articulo.marca}}</td>
                                <td>{{articulo.modelo}}</td>
                                <td>{{articulo.serie}}</td>
                                <td>{{articulo.estado}}</td>
                                <td>{{articulo.costo_adquisicion}}</td>
                                <td>{{articulo.observaciones}}</td>
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
    <script src="{{url("/js/articulos/mostrar.js?q=") . time()}}"></script>
@endsection
