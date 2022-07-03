new Vue({
    el: "#app",
    data: () => ({
        busqueda: "",
        mostrar: {
            aviso: false,
        },
        cliente: {
            nombre: "",
            direccion: "",
            id: null,
            dni: "",
            email: ""
        },
        errores: [],
        cargando: false,
        aviso: {},
    }),
    beforeMount() {
        let idCliente = window.location.href.split("/").pop();
        HTTP.get("/cliente/" + idCliente).then(cliente => {
            if (!cliente) {
                alert("El cliente que intentas editar no existe");
                window.location.href = URL_BASE;
                return;
            }
            this.cliente.id = cliente.id;
            this.cliente.nombre = cliente.nombre;
            this.cliente.direccion = cliente.direccion;
            this.cliente.dni = cliente.dni;
            this.cliente.email = cliente.email;
        });
    },
    methods: {
        guardar() {
            this.mostrar.aviso = false;
            if (!this.validar()) return;
            this.cargando = true;
            HTTP
                .put("/cliente", {
                    id: this.cliente.id,
                    nombre: this.cliente.nombre,
                    direccion: this.cliente.direccion,
                    dni: this.cliente.dni,
                    email: this.cliente.email,
                })
                .then(resultado => {
                    resultado && this.resetear();
                    this.mostrar.aviso = true;
                    this.aviso.mensaje = resultado ? "Cambios guardados con éxito" : "Error editando cliente. Intenta de nuevo";
                    this.aviso.tipo = resultado ? "is-success" : "is-danger";
                })
                .finally(() => this.cargando = false);
        },
        validar() {
            this.errores = [];
            if (!this.cliente.nombre.trim())
                this.errores.push("Escribe el nombre");
            if (this.cliente.nombre.length > 255)
                this.errores.push("El nombre no debe contener más de 255 caracteres");
            if (!this.cliente.direccion.trim())
                this.errores.push("Escribe la direccion");
            if (this.cliente.direccion.length > 255)
                this.errores.push("La dirección no debe contener más de 255 caracteres");
            if (!this.cliente.dni.trim())
                this.errores.push("Escribe la cedula");
            if (this.cliente.dni.length > 255)
                this.errores.push("La cedula no debe contener más de 128 caracteres");
            if (!this.cliente.email.trim())
                this.errores.push("Escribe el correo");
            if (this.cliente.email.length > 255)
                this.errores.push("El correo no debe contener más de 255 caracteres");
            return this.errores.length <= 0;
        },
        seleccionarArea(area) {
            this.areaSeleccionada = area;
            this.mostrar.areas = false;
        },
        resetear() {
            this.areas = [];
            this.areaSeleccionada = {};
            this.cliente.nombre = "";
            this.cliente.direccion = "";
            this.errores = [];
            this.cargando = false;
            this.busqueda = "";
        },
    }
});