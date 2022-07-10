new Vue({
    el: "#app",
    data: () => ({
        clientes: [],
        cliente: 0,
        articulos: [],
        lineas_factura: [],
        payment_methods: [],
        payment_method: {},
        payment_amount: 1,
        selected_payment_methods: [],
        articulo_seleccionado: {},
        cantidad_seleccionada: 1,
        errores: [],
        cargando: false,
        mostrar: {
            aviso: false,
        },
        aviso: {}
    }),
    beforeMount() {
        this.cargarClientes();
        this.cargarArticulos();
        this.cargarPaymentMethods();
    },
    methods: {
        cargarClientes() {
            HTTP.get("/clientes/todos")
                .then(clientes => {
                    this.clientes = clientes;
                });
        },
        cargarArticulos() {
            HTTP.get("/articulos/todos")
                .then(articulos => {
                    this.articulos = articulos;
                });
        },
        cargarPaymentMethods() {
            HTTP.get("/payment_methods")
                .then(payment_methods => {
                    this.payment_methods = payment_methods;
                });
        },
        agregarLinea() {
            this.lineas_factura.push({
                articulo: this.articulo_seleccionado,
                cantidad: this.cantidad_seleccionada
            });
        },
        removerLinea(index) {
            this.lineas_factura.splice(index, 1);
        },
        total() {
            return this.lineas_factura.reduce(
                (total, linea) => {
                    return total + (linea.articulo.precio_venta * linea.cantidad)
                },
                0
            ).toFixed(2);
        },
        agregarMetodoPago() {
            this.selected_payment_methods.push({
                payment_method: this.payment_method,
                payment_amount: this.payment_amount
            });
        },
        removerMetodoPago(index) {
            this.selected_payment_methods.splice(index, 1);
        },
        totalMetodosPago() {
            return this.selected_payment_methods.reduce(
                (total, selected_payment_method) => {
                    return total + (selected_payment_method.payment_amount / selected_payment_method.payment_method.divisa.tasa)
                },
                0
            ).toFixed(2);
        },
        validar() {
            this.errores = [];
            if (this.cliente <= 0)
                this.errores.push("Seleccione un cliente");
            if (!this.lineas_factura.length)
                this.errores.push("Agregue al menos un artículo");
            if (!this.lineas_factura.every(linea => linea.cantidad > 0))
                this.errores.push("La cantidad debe todos los articulos debe ser mayor a 0");
            if (!this.selected_payment_methods.length)
                this.errores.push("Agregue al menos un método de pago");
            if (!this.selected_payment_methods.every(selected_payment_method => selected_payment_method.payment_amount > 0))
                this.errores.push("El monto debe todos los métodos de pago debe ser mayor a 0");
            if (this.total() !== this.totalMetodosPago())
                this.errores.push("El total de los métodos de pago no coincide con el total de la factura");
            return this.errores.length === 0;
        },
        guardar() {
            this.mostrar.aviso = false;
            if (!this.validar()) return;
            this.cargando = true;
            HTTP
                .post("/facturas", {
                    cliente_id: this.cliente,
                    lineas_factura: this.lineas_factura.map(linea => (
                        { articulo_id: linea.articulo.id, cantidad: linea.cantidad, sub_total: linea.articulo.precio_venta * linea.cantidad }
                    )),
                    divisas_factura: this.selected_payment_methods.map(selected_payment_method => ( {
                        divisa_id: selected_payment_method.payment_method.divisa.id,
                        monto: selected_payment_method.payment_amount,
                        tasa: selected_payment_method.payment_method.divisa.tasa,
                        payment_method_id: selected_payment_method.payment_method.id
                    } )),
                })
                .then(resultado => {
                    this.mostrar.aviso = true;
                    this.aviso.mensaje = !resultado.error ? "Artículo agregado con éxito" : "Error agregando artículo. Intenta de nuevo";
                    this.aviso.tipo = !resultado.error ? "is-success" : "is-danger";
                    if(!resultado || !resultado.error) this.resetear();
                })
                .catch(error => {
                    this.mostrar.aviso = true;
                    this.aviso.mensaje = "Error agregando artículo. Intenta de nuevo";
                    this.aviso.tipo = "is-danger";
                })
                .finally(() => this.cargando = false);

        },
        resetear() {
            this.cliente = {},
            this.lineas_factura = [],
            this.payment_method = {},
            this.payment_amount = 1,
            this.selected_payment_methods = [],
            this.articulo_seleccionado = {},
            this.cantidad_seleccionada = 1,
            this.errores = [],
            this.cargando = false
        }
    },
});