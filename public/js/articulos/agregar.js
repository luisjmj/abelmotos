new Vue({
    el: "#app",
    data: () => ({
        areas: [],
        busqueda: "",
        areaSeleccionada: {},
        mostrar: {
            areas: false,
            aviso: false,
        },
        articulo: {
            codigo: "",
            numeroFolioComprobante: "",
            descripcion: "",
            marca: "",
            modelo: "",
            serie: "",
            estado: "regular",
            observaciones: "",
            precioVenta: "",
        },
        errores: [],
        cargando: false,
        aviso: {},
    }),
    methods: {
        guardar() {
            this.mostrar.aviso = false;
            if (!this.validar()) return;
            this.cargando = true;
            HTTP
                .post("/articulo", {
                    codigo: this.articulo.codigo,
                    numeroFolioComprobante: this.articulo.numeroFolioComprobante,
                    descripcion: this.articulo.descripcion,
                    marca: this.articulo.marca,
                    modelo: this.articulo.modelo,
                    serie: this.articulo.serie,
                    estado: this.articulo.estado,
                    observaciones: this.articulo.observaciones,
                    precioVenta: this.articulo.precioVenta,
                    areas_id: this.areaSeleccionada.id
                })
                .then(resultado => {
                    resultado && this.resetear();
                    this.mostrar.aviso = true;
                    this.aviso.mensaje = resultado ? "Artículo agregado con éxito" : "Error agregando artículo. Intenta de nuevo";
                    this.aviso.tipo = resultado ? "is-success" : "is-danger";
                })
                .finally(() => this.cargando = false);

        },
        validar() {
            this.errores = [];
            if (!this.articulo.codigo.trim())
                this.errores.push("Escribe el código del artículo");
            if (this.articulo.codigo.length > 255)
                this.errores.push("El código no debe contener más de 255 caracteres");
            if (!this.articulo.descripcion.trim())
                this.errores.push("Escribe la descripción del artículo");
            if (this.articulo.descripcion.length > 255)
                this.errores.push("La descripción no debe contener más de 255 caracteres");
            if (!this.articulo.estado)
                this.errores.push("Selecciona el estado del artículo");
            if (!parseFloat(this.articulo.precioVenta))
                this.errores.push("Escribe el costo de adquisición del artículo");
            if (parseFloat(this.articulo.precioVenta) <= 0)
                this.errores.push("El costo de adquisición debe ser mayor a 0");
            if (parseFloat(this.articulo.precioVenta) > 99999999.99)
                this.errores.push("El costo de adquisición debe ser menor que 100000000");

            if (!this.areaSeleccionada.id)
                this.errores.push("Selecciona un área");
            return this.errores.length <= 0;
        },
        seleccionarArea(area) {
            this.areaSeleccionada = area;
            this.mostrar.areas = false;
        },
        resetear() {
            this.areas = [];
            this.areaSeleccionada = {};
            this.articulo = {
                codigo: "",
                numeroFolioComprobante: "",
                descripcion: "",
                marca: "",
                modelo: "",
                serie: "",
                estado: "regular",
                observaciones: "",
                precioVenta: "",
            };
            this.errores = [];
            this.cargando = false;
            this.busqueda = "";
        },
        buscarArea: debounce(function () {
            if (!this.busqueda) return;
            HTTP.get("/areas/buscar/" + encodeURIComponent(this.busqueda))
                .then(areas => this.areas = areas.data)
        }, 500)
    }
});
