@extends("maestra")
@section("titulo", "Agregar divisa")
@section("contenido")
    <div class="container">
        <div class="columns">
            <div class="column is-half-tablet">
                <h1 class="is-size-1">Agregar divisa</h1>
                <form method="POST" action="{{route("guardarDivisa")}}">
                    @csrf
                    <div class="field">
                        <label class="label">Nombre</label>
                        <div class="control">
                            <input autocomplete="off" name="nombre" class="input" type="text"
                                   placeholder="Nombre de divisa ex: PESO">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Tasa</label>
                        <div class="control">
                            <input autocomplete="off" name="tasa" class="input" type="text"
                                   placeholder="Tasa en relacion a BS">
                        </div>
                    </div>
                    @include("errores")
                    @include("notificacion")
                    <button class="button is-success">Guardar</button>
                    <a class="button is-primary" href="{{route("divisas")}}">Ver todas</a>
                </form>
                <br>
            </div>
        </div>
    </div>
@endsection
