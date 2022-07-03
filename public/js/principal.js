const manejarError = error => {
    //TODO: hacer algo con el error
};
const HTTP = {
    get(ruta) {
        return fetch(URL_BASE_API + ruta)
            .then(respuesta => respuesta.json())
            .catch(error => {
                manejarError(error);
            })
    },
    post(ruta, objeto) {
        return fetch(URL_BASE_API + ruta, {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': TOKEN_CSRF,
            },
            body: JSON.stringify(objeto)
        })
            .then(respuesta => respuesta.json())
            .catch(error => {
                manejarError(error);
            })
    },
    put(ruta, objeto) {
        return fetch(URL_BASE_API + ruta, {
            method: "PUT",
            headers: {
                'X-CSRF-TOKEN': TOKEN_CSRF,
            },
            body: JSON.stringify(objeto),
        })
            .then(respuesta => respuesta.json())
            .catch(error => {
                manejarError(error);
            })
    },
    delete(ruta) {
        return fetch(URL_BASE_API + ruta, {
            method: "DELETE",
            headers: {
                'X-CSRF-TOKEN': TOKEN_CSRF,
            },
        })
            .then(respuesta => respuesta.json())
            .catch(error => {
                manejarError(error);
            })
    }
};