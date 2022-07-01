@extends("maestra")
@section("titulo", "Agregar área")
@section("contenido")
    <div class="container">
        <div class="columns">
            <div class="column is-half-tablet">
                <h1 class="is-size-1">Agregar Inventario</h1>
                <form method="POST" action="{{route("guardarInventario",[$articulo])}}">
                    @csrf
                    <div class="field">
                        <label class="label">Fecha de adquisición</label>
                        <div class="control">
                            <input name="fecha_de_adquisicion" autocomplete="off" class="input" type="date" value="{{old('fecha_de_adquisicion')}}">
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Costo de adquisición</label>
                        <div class="control">
                            <input name="costo_de_adquisicion" autocomplete="off" class="input" type="text"
                                   placeholder="Costo de adquisicion" value="{{old('costo_de_adquisicion')}}">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Cantidad</label>
                        <div class="control">
                            <input name="cantidad" autocomplete="off" class="input" type="text"
                                   placeholder="Cantidad" value="{{old('cantidad')}}">
                        </div>
                    </div>
                    @include("errores")
                    @include("notificacion")
                    <button class="button is-success">Guardar</button>
                </form>
                <br>
            </div>
        </div>
    </div>
    <script>
    </script>
@endsection
