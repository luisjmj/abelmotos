new Vue({
    el: "#app",
    data: () => ({
        clientes: [],
        cliente: {},
        articulos: [],
        lineas_factura: [],
        payment_methods: [],
        payment_method: {},
        payment_amount: 1,
        selected_payment_methods: [],
        articulo_seleccionado: {},
        cantidad_seleccionada: 1,
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
        }
    },
});