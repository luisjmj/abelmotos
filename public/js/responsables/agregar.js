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
        responsable: {
            nombre: "",
            direccion: "",
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
                .post("/responsable", {
                    nombre: this.responsable.nombre,
                    direccion: this.responsable.direccion,
                    areas_id: this.areaSeleccionada.id
                })
                .then(resultado => {
                    resultado && this.resetear();
                    this.mostrar.aviso = true;
                    this.aviso.mensaje = resultado ? "Responsable agregado con éxito" : "Error agregando responsable. Intenta de nuevo";
                    this.aviso.tipo = resultado ? "is-success" : "is-danger";
                })
                .finally(() => this.cargando = false);

        },
        validar() {
            this.errores = [];
            if (!this.responsable.nombre.trim())
                this.errores.push("Escribe el nombre");
            if (this.responsable.nombre.length > 255)
                this.errores.push("El nombre no debe contener más de 255 caracteres");
            if (!this.responsable.direccion.trim())
                this.errores.push("Escribe la direccion");
            if (this.responsable.direccion.length > 255)
                this.errores.push("La dirección no debe contener más de 255 caracteres");
            if (!this.areaSeleccionada.id)
                this.errores.push("Selecciona un área");
            return this.errores.length <= 0;
        },
        seleccionarArea(area) {
            console.log("SEleccionas:", this.areaSeleccionada);
            this.areaSeleccionada = area;
            this.mostrar.areas = false;
        },
        resetear() {
            this.areas = [];
            this.areaSeleccionada = {};
            this.responsable.nombre = "";
            this.responsable.direccion = "";
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