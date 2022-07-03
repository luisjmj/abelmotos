@extends("maestra")
@section("titulo", "Editar divisa")
@section("contenido")
    <div class="container">
        <div class="columns">
            <div class="column is-half-tablet">
                <h1 class="is-size-1">Editar divisa</h1>
                <form method="POST" action="{{route("guardarCambiosDeDivisa")}}">
                    @method("put")
                    @csrf
                    <input type="hidden" value="{{$divisa->id}}" name="id">
                    <div class="field">
                        <label class="label">Nombre</label>
                        <div class="control">
                            <input value="{{$divisa->nombre}}" autocomplete="off" name="nombre" class="input" type="text"
                                   placeholder="Nombre de divisa">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Tasa</label>
                        <div class="control">
                            <input value="{{$divisa->tasa}}" autocomplete="off" name="tasa" class="input" type="text"
                                   placeholder="Tasa de divisa">
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
@endsection
