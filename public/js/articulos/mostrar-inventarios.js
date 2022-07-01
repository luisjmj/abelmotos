/*
 *     Copyright (C) 2019  Luis Cabrera Benito a.k.a. parzibyte
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

const RUTA_EDITAR_INVENTARIO = URL_BASE + "/inventarios/editar";
new Vue({
    el: "#app",
    data: () => ({
        buscando: false,
        inventarios: [],
        numeroDeElementosMarcados: 0,
        cargando: {
            eliminandoMuchos: false,
            lista: false,
            paginacion: false,
        },
        busqueda: "",
        paginacion: {
            total: 0,
            actual: 0,
            ultima: 0,
            siguientePagina: "",
            paginaAnterior: "",
        },
        paginas: [],
    }),
    beforeMount() {
        this.refrescarSinQueImporteBusquedaOPagina();
    },
    computed: {
        deberiaDeshabilitarBusqueda() {
            return this.inventarios.length <= 0 && !this.busqueda;
        }
    },
    methods: {
        puedeAvanzarPaginacion() {
            return this.paginacion.actual < this.paginacion.ultima;
        },
        puedeRetrocederPaginacion() {
            return this.paginacion.actual > 1;
        },
        avanzarPaginacion() {
            if (this.puedeAvanzarPaginacion()) {
                this.irALaPagina(this.paginacion.actual + 1);
            }
        },
        retrocederPaginacion() {
            if (this.puedeRetrocederPaginacion()) {
                this.irALaPagina(this.paginacion.actual - 1);
            }
        },
        limpiarBusqueda() {
            this.busqueda = "";
            this.refrescarSinQueImporteBusquedaOPagina();
        },
        buscar: debounce(function () {
            if (this.busqueda && !this.buscando) {
                this.buscando = true;
                this.consultarInventariosConUrl(`/articulos/inventario/${window.articuloId}/buscar/`)
                    .finally(() => this.buscando = false);
            } else {
                this.refrescarSinQueImporteBusquedaOPagina();
            }
        }, 500),
        editar(inventario) {
            window.location.href = `${RUTA_EDITAR_INVENTARIO}/${inventario.id}`;
        },
        eliminarMarcadas() {
            if (!confirm("¿Eliminar todos los elementos marcados?")) return;
            let arregloParaEliminar = this.inventarios.filter(inventario => inventario.marcada).map(inventario => inventario.id);
            this.cargando.eliminandoMuchos = true;
            HTTP.post(`/inventarios/eliminar/${window.articuloId}?ids=${JSON.stringify(arregloParaEliminar)}`, {
                ids: arregloParaEliminar,
            })
                .then(resultado => {
                })
                .finally(() => {
                    this.desmarcarTodas();
                    this.refrescarSinQueImporteBusquedaOPagina();
                    this.cargando.eliminandoMuchos = false;
                });
        },
        onBotonParaMarcarClickeado() {
            if (this.inventarios.some(inventario => inventario.marcada)) {
                this.desmarcarTodas();
            } else {
                this.marcarTodas();
            }
        },
        marcarTodas() {
            this.numeroDeElementosMarcados = this.inventarios.length;
            this.inventarios.forEach(inventario => {
                Vue.set(inventario, "marcada", true);
            });
        },
        desmarcarTodas() {
            this.numeroDeElementosMarcados = 0;
            this.inventarios.forEach(inventario => {
                Vue.set(inventario, "marcada", false);
            });
        },
        invertirEstado(inventario) {
            // Si está marcada, ahora estará desmarcada
            if (inventario.marcada) this.numeroDeElementosMarcados--;
            else this.numeroDeElementosMarcados++;
            Vue.set(inventario, "marcada", !inventario.marcada);
        },
        eliminar(inventario) {
            if (!confirm(`¿Eliminar inventario?`)) return;
            this.desmarcarTodas();
            Vue.set(inventario, "eliminando", true);
            const arregloParaEliminar = [inventario.id];
            HTTP.post(`/inventarios/eliminar/${window.articuloId}?ids=${JSON.stringify(arregloParaEliminar)}`, {
                ids: arregloParaEliminar,
            })
                .then(resultado => {
                })
                .finally(() => {
                    this.desmarcarTodas();
                    this.refrescarSinQueImporteBusquedaOPagina();
                    this.cargando.eliminandoMuchos = false;
                });
        },
        refrescarSinQueImporteBusquedaOPagina() {
            let url = this.busqueda ? `/articulos/inventario/${window.articuloId}/buscar/?page=${this.paginacion.actual}` : `/articulos/inventario/${window.articuloId}/buscar/`;
            this.consultarInventariosConUrl(url);
        },
        consultarInventariosConUrl(url) {
            this.desmarcarTodas();
            this.cargando.lista = true;
            return HTTP.get(url)
                .then(respuesta => {
                    this.inventarios = respuesta.data;
                    this.establecerPaginacion(respuesta);
                })
                .finally(() => this.cargando.lista = false);
        },
        establecerPaginacion(respuesta) {
            this.paginacion.siguientePagina = respuesta.next_page_url;
            this.paginacion.paginaAnterior = respuesta.prev_page_url;
            this.paginacion.actual = respuesta.current_page;
            this.paginacion.total = respuesta.total;
            this.paginacion.ultima = respuesta.last_page;
            this.prepararArregloParaPaginacion();
        },
        irALaPagina(pagina) {
            this.cargando.paginacion = true;
            this.consultarInventariosConUrl("/inventarios?page=" + pagina).finally(() => this.cargando.paginacion = false);
        },
        prepararArregloParaPaginacion() {

            // Si no hay más de una página ¿Para qué mostrar algo?
            if (this.paginacion.ultima <= 1) return;
            this.paginas = [];
            // Poner la primera página
            this.paginas.push({numero: 1});
            // Izquierda de la actual
            let posibleIzquierdaDeActual = this.paginacion.actual - 1;
            if (posibleIzquierdaDeActual > 1 && posibleIzquierdaDeActual !== this.paginacion.ultima) {

                this.paginas.push({numero: posibleIzquierdaDeActual});
                // Si entre la izquierda de la actual y la primera hay un espacio grande, poner ...
                if (posibleIzquierdaDeActual - 1 > 1) this.paginas.splice(1, 0, {puntosSuspensivos: true})
            }
            // Poner la actual igualmente si no es la primera o última
            if (this.paginacion.actual !== 1 && this.paginacion.actual !== this.paginacion.ultima) {

                this.paginas.push({numero: this.paginacion.actual});
            }
            // Derecha de la actual
            let posibleDerechaDeActual = this.paginacion.actual + 1;
            if (posibleDerechaDeActual !== 1 && posibleDerechaDeActual < this.paginacion.ultima) {

                this.paginas.push({numero: posibleDerechaDeActual});
                // Si entre la derecha de la actual y la última hay un espacio grande, poner ...
                if (posibleDerechaDeActual + 1 < this.paginacion.ultima) this.paginas.push({puntosSuspensivos: true})
            }
            // Última
            this.paginas.push({numero: this.paginacion.ultima});
        }
    }
});
