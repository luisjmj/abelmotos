new Vue({
    el: "#app",
    data: () => ({
        fotos: [],
    }),
    methods: {
        onFotosCambiadas() {
            // Copiar el arreglo de fotos, ya que es inmutable de manera original
            this.fotos = Array.from(this.$refs.fotos.files);
        },
        eliminar(ruta) {
            if (!confirm("¿Realmente deseas eliminar la foto?")) return;
            HTTP
                .post("/eliminar/foto/articulo", {
                    nombre: ruta
                })
                .then(() => {
                    window.location.reload();
                });
        },
    }
});