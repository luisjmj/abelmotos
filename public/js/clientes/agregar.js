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
            dni: "",
            email: ""
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
                .post("/cliente", {
                    dni: this.cliente.dni,
                    nombre: this.cliente.nombre,
                    direccion: this.cliente.direccion,
                    email: this.cliente.email
                })
                .then(resultado => {
                    resultado && this.resetear();
                    this.mostrar.aviso = true;
                    this.aviso.mensaje = resultado ? "Cliente agregado con éxito" : "Error agregando cliente. Intenta de nuevo";
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
        resetear() {
            this.cliente.nombre = "";
            this.cliente.direccion = "";
            this.cliente.dni = "";
            this.cliente.email = "";
            this.errores = [];
            this.cargando = false;
            this.busqueda = "";
        },
    }
});