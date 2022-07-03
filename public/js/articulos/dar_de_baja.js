new Vue({
    el: "#app",
    data: () => ({adjuntos: []}),
    methods: {
        onAdjuntosCambiados() {
            this.adjuntos = Array.from(this.$refs.adjuntos.files);
        }
    }
});