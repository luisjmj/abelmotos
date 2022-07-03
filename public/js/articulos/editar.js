new Vue({
    el: "#app",
    data: () => ({
        areas: [],
        proveedores: [],
        busqueda: "",
        busquedaProveedor: "",
        areaSeleccionada: {},
        proveedorSeleccionado: {},
        mostrar: {
            areas: false,
            aviso: false,
            proveedores: false,
        },
        articulo: {
            id: "",
            codigo: "",
            factura: "",
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
    beforeMount() {
        // Separar por / y obtener el último elemento
        let idArticulo = window.location.href.split("/").pop();
        HTTP.get("/articulo/" + idArticulo).then(articulo => {
            if (!articulo.id) {
                alert("El artículo que intentas editar no existe");
                window.location.href = URL_BASE;
                return;
            }
            this.articulo.id = articulo.id;
            this.articulo.codigo = articulo.codigo;
            this.articulo.factura = articulo.factura;
            this.articulo.descripcion = articulo.descripcion;
            this.articulo.marca = articulo.marca;
            this.articulo.modelo = articulo.modelo;
            this.articulo.serie = articulo.serie;
            this.articulo.estado = articulo.estado;
            this.articulo.observaciones = articulo.observaciones;
            this.articulo.precioVenta = articulo.precio_venta;
            this.areaSeleccionada.nombre = articulo.area.nombre;
            this.areaSeleccionada.id = articulo.area.id;
            this.proveedorSeleccionado.nombre = articulo.proveedor.nombre;
            this.proveedorSeleccionado.id = articulo.proveedor.id;
        });
    },
    methods: {
        guardar() {
            this.mostrar.aviso = false;
            if (!this.validar()) return;
            this.cargando = true;
            HTTP
                .post(`/articulo/${this.articulo.id}`, {
                    id: this.articulo.id,
                    codigo: this.articulo.codigo,
                    factura: this.articulo.factura,
                    descripcion: this.articulo.descripcion,
                    marca: this.articulo.marca,
                    modelo: this.articulo.modelo,
                    serie: this.articulo.serie,
                    estado: this.articulo.estado,
                    observaciones: this.articulo.observaciones,
                    precioVenta: this.articulo.precioVenta,
                    areas_id: this.areaSeleccionada.id,
                    proveedor_id: this.proveedorSeleccionado.id,
                })
                .then(resultado => {
                    resultado && this.resetear();
                    this.mostrar.aviso = true;
                    this.aviso.mensaje = resultado ? "Artículo actualizado con éxito" : "Error actualizando artículo. Intenta de nuevo";
                    this.aviso.tipo = resultado ? "is-success" : "is-danger";
                })
                .finally(() => this.cargando = false);

        },
        validar() {
            this.errores = [];
            if (!this.articulo.codigo.trim())
                this.errores.push("Escriba el código del artículo");
            if (this.articulo.codigo.length > 255)
                this.errores.push("El código no debe contener más de 255 caracteres");
            if (!this.articulo.descripcion.trim())
                this.errores.push("Escriba la descripción del artículo");
            if (this.articulo.descripcion.length > 255)
                this.errores.push("La descripción no debe contener más de 255 caracteres");
            if (!this.articulo.estado)
                this.errores.push("Selecciona el estado del artículo");
            if (!parseFloat(this.articulo.precioVenta))
                this.errores.push("Escriba el costo de adquisición del artículo");
            if (parseFloat(this.articulo.precioVenta) <= 0)
                this.errores.push("El costo de adquisición debe ser mayor a 0");
            if (parseFloat(this.articulo.precioVenta) > 99999999.99)
                this.errores.push("El costo de adquisición debe ser menor que 100000000");

            if (!this.areaSeleccionada.id)
                this.errores.push("Seleccione un área");
            if (!this.proveedorSeleccionado.id)
                this.errores.push("Seleccione un proveedor");
            return this.errores.length <= 0;
        },
        seleccionarArea(area) {
            this.areaSeleccionada = area;
            this.mostrar.areas = false;
        },
        seleccionaProveedor(proveedor) {
            this.proveedorSeleccionado = proveedor;
            this.mostrar.proveedores = false;
        },
        resetear() {
            this.areas = [];
            this.proveedores = [];
            this.areaSeleccionada = {};
            this.proveedorSeleccionado = {};
            this.articulo = {
                codigo: "",
                factura: "",
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
            this.busquedaProveedor = "";
        },
        buscarArea: debounce(function () {
            if (!this.busqueda) return;
            HTTP.get("/areas/buscar/" + encodeURIComponent(this.busqueda))
                .then(areas => this.areas = areas.data)
        }, 500),
        buscarProveedor: debounce(function () {
            if (!this.busquedaProveedor) return;
            HTTP.get("/proveedores/buscar/" + encodeURIComponent(this.busquedaProveedor))
                .then(proveedores => this.proveedores = proveedores.data)
        }, 500)
    }
});
