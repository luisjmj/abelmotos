const RUTA_EDITAR_DIVISA = URL_BASE + "/divisas/editar";
new Vue({
    el: "#app",
    data: () => ({
        buscando: false,
        divisas: [],
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
            return this.divisas.length <= 0 && !this.busqueda;
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
                this.consultarDivisasConUrl(`/divisas/buscar/${encodeURIComponent(this.busqueda)}`)
                    .finally(() => this.buscando = false);
            } else {
                this.refrescarSinQueImporteBusquedaOPagina();
            }
        }, 500),
        editar(divisa) {
            window.location.href = `${RUTA_EDITAR_DIVISA}/${divisa.id}`;
        },
        eliminarMarcadas() {
            if (!confirm("¿Eliminar todos los elementos marcados?")) return;
            let arregloParaEliminar = this.divisas.filter(divisa => divisa.marcada).map(divisa => divisa.id);
            this.cargando.eliminandoMuchos = true;
            HTTP.post("/divisas/eliminar", arregloParaEliminar)
                .then(resultado => {

                })
                .finally(() => {
                    this.desmarcarTodas();
                    this.refrescarSinQueImporteBusquedaOPagina();
                    this.cargando.eliminandoMuchos = false;
                });
        },
        onBotonParaMarcarClickeado() {
            if (this.divisas.some(divisa => divisa.marcada)) {
                this.desmarcarTodas();
            } else {
                this.marcarTodas();
            }
        },
        marcarTodas() {
            this.numeroDeElementosMarcados = this.divisas.length;
            this.divisas.forEach(divisa => {
                Vue.set(divisa, "marcada", true);
            });
        },
        desmarcarTodas() {
            this.numeroDeElementosMarcados = 0;
            this.divisas.forEach(divisa => {
                Vue.set(divisa, "marcada", false);
            });
        },
        invertirEstado(divisa) {
            // Si está marcada, ahora estará desmarcada
            if (divisa.marcada) this.numeroDeElementosMarcados--;
            else this.numeroDeElementosMarcados++;
            Vue.set(divisa, "marcada", !divisa.marcada);
        },
        eliminar(divisa) {
            if (!confirm(`¿Eliminar divisa ${divisa.nombre}?`)) return;
            this.desmarcarTodas();
            let {id} = divisa;
            Vue.set(divisa, "eliminando", true);
            HTTP.delete(`/divisa/${id}`)
                .then(resultado => {

                })
                .finally(() => {
                    this.refrescarSinQueImporteBusquedaOPagina();
                })
        },
        refrescarSinQueImporteBusquedaOPagina() {
            let url = this.busqueda ? `/divisas/buscar/${encodeURIComponent(this.busqueda)}?page=${this.paginacion.actual}` : "/divisas";
            this.consultarDivisasConUrl(url);
        },
        consultarDivisasConUrl(url) {
            this.desmarcarTodas();
            this.cargando.lista = true;
            return HTTP.get(url)
                .then(respuesta => {
                    this.divisas = respuesta.data;
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
            this.consultarDivisasConUrl("/divisas?page=" + pagina).finally(() => this.cargando.paginacion = false);
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