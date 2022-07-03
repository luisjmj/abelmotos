const RUTA_EDITAR_PROVEEDOR = URL_BASE + "/proveedores/editar";
new Vue({
    el: "#app",
    data: () => ({
        buscando: false,
        proveedores: [],
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
            return this.proveedores.length <= 0 && !this.busqueda;
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
                this.consultarProveedoresConUrl(`/proveedores/buscar/${encodeURIComponent(this.busqueda)}`)
                    .finally(() => this.buscando = false);
            } else {
                this.refrescarSinQueImporteBusquedaOPagina();
            }
        }, 500),
        editar(proveedor) {
            window.location.href = `${RUTA_EDITAR_PROVEEDOR}/${proveedor.id}`;
        },
        eliminarMarcados() {
            if (!confirm("¿Eliminar todos los elementos marcados?")) return;
            let arregloParaEliminar = this.proveedores.filter(proveedor => proveedor.marcado).map(proveedor => proveedor.id);
            this.cargando.eliminandoMuchos = true;
            HTTP.post("/proveedores/eliminar", arregloParaEliminar)
                .then(resultado => {

                })
                .finally(() => {
                    this.desmarcarTodos();
                    this.refrescarSinQueImporteBusquedaOPagina();
                    this.cargando.eliminandoMuchos = false;
                });
        },
        onBotonParaMarcarClickeado() {
            if (this.proveedores.some(proveedor => proveedor.marcado)) {
                this.desmarcarTodos();
            } else {
                this.marcarTodos();
            }
        },
        marcarTodos() {
            this.numeroDeElementosMarcados = this.proveedores.length;
            this.proveedores.forEach(proveedor => {
                Vue.set(proveedor, "marcado", true);
            });
        },
        desmarcarTodos() {
            this.numeroDeElementosMarcados = 0;
            this.proveedores.forEach(proveedor => {
                Vue.set(proveedor, "marcado", false);
            });
        },
        invertirEstado(proveedor) {
            // Si está marcada, ahora estará desmarcada
            if (proveedor.marcado) this.numeroDeElementosMarcados--;
            else this.numeroDeElementosMarcados++;
            Vue.set(proveedor, "marcado", !proveedor.marcado);
        },
        eliminar(proveedor) {
            if (!confirm(`¿Eliminar proveedor ${proveedor.nombre}?`)) return;
            this.desmarcarTodos();
            let {id} = proveedor;
            Vue.set(proveedor, "eliminando", true);
            HTTP.delete(`/proveedor/${id}`)
                .then(resultado => {

                })
                .finally(() => {
                    this.refrescarSinQueImporteBusquedaOPagina();
                })
        },
        refrescarSinQueImporteBusquedaOPagina() {
            let url = this.busqueda ? `/proveedores/buscar/${encodeURIComponent(this.busqueda)}?page=${this.paginacion.actual}` : "/proveedores";
            this.consultarProveedoresConUrl(url);
        },
        consultarProveedoresConUrl(url) {

            // return console.log("Todavía no!");
            this.desmarcarTodos();
            this.cargando.lista = true;
            return HTTP.get(url)
                .then(respuesta => {
                    this.proveedores = respuesta.data;
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
            this.consultarProveedoresConUrl("/proveedores?page=" + pagina).finally(() => this.cargando.paginacion = false);
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