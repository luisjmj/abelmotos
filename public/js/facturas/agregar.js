new Vue({
    el: "#app",
    data: () => ({
        clientes: [],
        cliente: {},
        articulos: [],
        lineas_factura: [],
        payment_methods: [],
        articulo_seleccionado: {},
        cantidad_seleccionada: 0,
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
        }
    },
});