new Vue({
    el: "#app",
    data: () => ({
        busqueda: "",
        mostrar: {
            aviso: false,
        },
        proveedor: {
            nombre: "",
            rif: "",
            telefono: "",
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
                .post("/proveedor", {
                    nombre: this.proveedor.nombre,
                    rif: this.proveedor.rif,
                    telefono: this.proveedor.telefono,
                    email: this.proveedor.email
                })
                .then(resultado => {
                    resultado && this.resetear();
                    this.mostrar.aviso = true;
                    this.aviso.mensaje = resultado ? "Proveedor agregado con éxito" : "Error agregando proveedor. Intenta de nuevo";
                    this.aviso.tipo = resultado ? "is-success" : "is-danger";
                })
                .finally(() => this.cargando = false);

        },
        validar() {
            this.errores = [];
            if (!this.proveedor.nombre.trim())
                this.errores.push("Escribe el nombre");
            if (this.proveedor.nombre.length > 255)
                this.errores.push("El nombre no debe contener más de 255 caracteres");
            if (!this.proveedor.telefono.trim())
                this.errores.push("Escribe el telefono");
            if (this.proveedor.telefono.length > 64)
                this.errores.push("El telefono no debe contener más de 64 caracteres");
            if (!this.proveedor.rif.trim())
                this.errores.push("Escribe la cedula");
            if (this.proveedor.rif.length > 64)
                this.errores.push("La cedula no debe contener más de 128 caracteres");
            if (!this.proveedor.email.trim())
                this.errores.push("Escribe el correo");
            if (this.proveedor.email.length > 255)
                this.errores.push("El correo no debe contener más de 255 caracteres");
            return this.errores.length <= 0;
        },
        resetear() {
            this.proveedor.nombre = "";
            this.proveedor.rif = "";
            this.proveedor.telefono = "";
            this.proveedor.email = "";
            this.errores = [];
            this.cargando = false;
            this.busqueda = "";
        },
    }
});