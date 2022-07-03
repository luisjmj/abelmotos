@extends("maestra")
@section("titulo", "Agregar cliente")
@section("contenido")
    <div class="container" id="app">
        <div class="columns">
            <div class="column is-half-tablet">
                <h1 class="is-size-1">Agregar cliente</h1>
                <div class="field">
                    <label class="label">Cedula</label>
                    <div class="control">
                        <input v-model="cliente.dni" autocomplete="off" name="dni" class="input" type="text"
                               placeholder="Cedula del cliente">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Nombre completo</label>
                    <div class="control">
                        <input v-model="cliente.nombre" autocomplete="off" name="nombre" class="input" type="text"
                               placeholder="Nombre del cliente">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Dirección</label>
                    <div class="control">
                        <textarea v-model="cliente.direccion" class="textarea"
                            placeholder="Dirección del cliente" name="direccion"
                            id="direccion" cols="30" rows="3"></textarea>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Correo</label>
                    <div class="control">
                        <input v-model="cliente.email" autocomplete="off" name="email" class="input" type="email"
                               placeholder="Correo del cliente">
                    </div>
                </div>
                @verbatim
                <div v-show="errores.length > 0" class="notification is-danger">
                    <h5 class="is-size-5">Por favor, valida los siguientes errores:</h5>
                    <ul>
                        <li v-for="error in errores">
                            {{error}}
                        </li>
                    </ul>
                </div>
                <div v-show="mostrar.aviso" class="notification" :class="aviso.tipo">
                    {{aviso.mensaje}}
                </div>
                @endverbatim
                @include("errores")
                @include("notificacion")
                @verbatim
                    <button :class="{'is-loading':cargando}" @click="guardar()" class="button is-success">Guardar
                    </button>
                @endverbatim
                <a class="button is-primary" href="{{route("clientes")}}">Ver todos</a>
                <br>
            </div>
        </div>
    </div>
    <script src="{{url("/js/clientes/agregar.js?q=") . time()}}"></script>
@endsection
