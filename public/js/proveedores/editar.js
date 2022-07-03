new Vue({
    el: "#app",
    data: () => ({
        busqueda: "",
        mostrar: {
            aviso: false,
        },
        proveedor: {
            nombre: "",
            telefono: "",
            id: null,
            rif: "",
            email: ""
        },
        errores: [],
        cargando: false,
        aviso: {},
    }),
    beforeMount() {
        let idProveedor = window.location.href.split("/").pop();
        HTTP.get("/proveedor/" + idProveedor).then(proveedor => {
            if (!proveedor) {
                alert("El proveedor que intentas editar no existe");
                window.location.href = URL_BASE;
                return;
            }
            this.proveedor.id = proveedor.id;
            this.proveedor.nombre = proveedor.nombre;
            this.proveedor.telefono = proveedor.telefono;
            this.proveedor.rif = proveedor.rif;
            this.proveedor.email = proveedor.email;
        });
    },
    methods: {
        guardar() {
            this.mostrar.aviso = false;
            if (!this.validar()) return;
            this.cargando = true;
            HTTP
                .put("/proveedor", {
                    id: this.proveedor.id,
                    nombre: this.proveedor.nombre,
                    telefono: this.proveedor.telefono,
                    rif: this.proveedor.rif,
                    email: this.proveedor.email,
                })
                .then(resultado => {
                    resultado && this.resetear();
                    this.mostrar.aviso = true;
                    this.aviso.mensaje = resultado ? "Cambios guardados con éxito" : "Error editando proveedor. Intenta de nuevo";
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
        seleccionarArea(area) {
            this.areaSeleccionada = area;
            this.mostrar.areas = false;
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