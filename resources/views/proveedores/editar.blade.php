@extends("maestra")
@section("titulo", "Editar proveedor")
@section("contenido")
    <div class="container" id="app">
        <div class="columns">
            <div class="column is-half-tablet">
                <h1 class="is-size-1">Editar proveedor</h1>
                <div class="field">
                    <label class="label">Nombre completo</label>
                    <div class="control">
                        <input v-model="proveedor.nombre" autocomplete="off" name="nombre" class="input" type="text"
                               placeholder="Nombre del proveedor">
                    </div>
                </div>
                <div class="field">
                    <label class="label">RIF</label>
                    <div class="control">
                        <input v-model="proveedor.rif" autocomplete="off" name="rif" class="input" type="text"
                               placeholder="RIF del proveedor">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Telefono</label>
                    <div class="control">
                        <input v-model="proveedor.telefono" autocomplete="off" name="telefono" class="input" type="text"
                            placeholder="Telefono del proveedor">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Correo</label>
                    <div class="control">
                        <input v-model="proveedor.email" autocomplete="off" name="email" class="input" type="email"
                               placeholder="Correo del proveedor">
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
                <a class="button is-primary" href="{{route("proveedores")}}">Ver todos</a>
                <br>
            </div>
        </div>
    </div>
    <script src="{{url("/js/proveedores/editar.js?q=") . time()}}"></script>
@endsection
