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
            id: null,
        },
        errores: [],
        cargando: false,
        aviso: {},
    }),
    beforeMount() {
        // Separar por / y obtener el último elemento
        let idResponsable = window.location.href.split("/").pop();
        HTTP.get("/responsable/" + idResponsable).then(responsable => {
            if (!responsable) {
                alert("El responsable que intentas editar no existe");
                window.location.href = URL_BASE;
                // Sí sí ya sé que lo de arriba igualmente detiene la ejecución
                return;
            }
            this.responsable.id = responsable.id;
            this.responsable.nombre = responsable.nombre;
            this.responsable.direccion = responsable.direccion;
            this.areaSeleccionada.id = responsable.area.id;
            this.areaSeleccionada.nombre = responsable.area.nombre;
        })

    },
    methods: {
        guardar() {
            this.mostrar.aviso = false;
            if (!this.validar()) return;
            this.cargando = true;
            HTTP
                .put("/responsable", {
                    id: this.responsable.id,
                    nombre: this.responsable.nombre,
                    direccion: this.responsable.direccion,
                    areas_id: this.areaSeleccionada.id
                })
                .then(resultado => {
                    resultado && this.resetear();
                    this.mostrar.aviso = true;
                    this.aviso.mensaje = resultado ? "Cambios guardados con éxito" : "Error editando responsable. Intenta de nuevo";
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