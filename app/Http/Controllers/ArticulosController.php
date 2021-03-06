<?php

namespace App\Http\Controllers;

use App\AdjuntoDeEliminacionDeArticulo;
use App\Articulo;
use App\ArticuloDadoDeBaja;
use App\ArticuloInventario;
use App\FotoDeArticulo;
use App\Http\Requests\AgregarArticuloDeInventarioRequest;
use App\Http\Requests\DarArticuloDeBajaRequest;
use App\Http\Requests\GuardarCambiosDeArticuloRequest;
use App\Http\Requests\SubirFotosDeArticulosRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use function back;
use function json_decode;
use function redirect;
use function str_replace;
use function view;

class ArticulosController extends Controller
{

    private $nombreCarpetaFotos = "fotos";
    private $nombreCarpetaAdjuntos = "adjuntos";
    private $TIPO_ADJUNTOS_BAJA = "BAJA";
    private $nombreFoto404 = "error_404.jpeg";

    public function foto(Request $peticion)
    {
        $nombreFoto = $peticion->nombre;
        $rutaDeImagen = storage_path("app/" . $this->nombreCarpetaFotos . "/" . $this->obtenerRutaSegura($nombreFoto));
        return response()->file($rutaDeImagen);
    }

    public function descargar(Request $peticion)
    {
        $nombreFoto = $peticion->nombre;
        $rutaDeImagen = storage_path("app/" . $this->nombreCarpetaFotos . "/" . $this->obtenerRutaSegura($nombreFoto));
        return response()->download($rutaDeImagen);
    }

    public function eliminarFoto(Request $peticion)
    {
        $datosDecodificados = json_decode($peticion->getContent());
        $nombreFoto = $this->obtenerRutaSegura($datosDecodificados->nombre);
        if ($nombreFoto === $this->nombreFoto404) return [];
        Storage::disk("local")->delete("fotos/$nombreFoto");
        return response()->json([
            "archivo" => Storage::disk("local")->delete("fotos/$nombreFoto"), // Eliminar el archivo físico
            "bd" => FotoDeArticulo::where("ruta", "=", $nombreFoto)->delete() // y quitar el registro de la base de datos
        ]);
    }

    private function obtenerRutaSegura($rutaInsegura)
    {
        # Verifica si la ruta que se quiere leer realmente pertenece a un artículo, en caso de que sí, regresa la
        #misma ruta pero tomada de la base de datos
        $posibleRegistro = FotoDeArticulo::where("ruta", "=", $rutaInsegura)->first();
        if ($posibleRegistro === null) return $this->nombreFoto404;
        return $posibleRegistro->ruta;
    }

    public function agregar(AgregarArticuloDeInventarioRequest $peticion)
    {
        $datosDecodificados = json_decode($peticion->getContent());
        $articulo = new Articulo;
        $articulo->codigo = $peticion->codigo;
        $articulo->factura = $peticion->factura;
        $articulo->descripcion = $peticion->descripcion;
        $articulo->marca = $peticion->marca;
        $articulo->modelo = $peticion->modelo;
        $articulo->serie = $peticion->serie;
        $articulo->estado = $peticion->estado;
        $articulo->observaciones = $peticion->observaciones;
        $articulo->precio_venta = $peticion->precioVenta;
        $articulo->areas_id = $datosDecodificados->areas_id;
        $articulo->proveedor_id = $datosDecodificados->proveedor_id;
        return response()->json($articulo->save());
    }

    public function mostrar()
    {
        return Articulo::orderBy("updated_at", "desc")
            ->orderBy("created_at", "desc")
            ->with(["area", "fotos"])
            ->paginate(Config::get("constantes.paginas_en_paginacion"));
    }

    public function mostrarTodos()
    {
        return response()->json(Articulo::all());
    }

    public function porId(Request $peticion)
    {
        $idArticulo = $peticion->id;
        $articulo = Articulo::where("id", "=", $idArticulo)->with("Area", "Proveedor")->first();
        return response()->json($articulo);
    }

    public function guardarCambios(GuardarCambiosDeArticuloRequest $peticion)
    {
        $datosDecodificados = json_decode($peticion->getContent());
        $articulo = Articulo::findOrFail($datosDecodificados->id);
        $articulo->codigo = $peticion->codigo;
        $articulo->factura = $peticion->factura;
        $articulo->descripcion = $peticion->descripcion;
        $articulo->marca = $peticion->marca;
        $articulo->modelo = $peticion->modelo;
        $articulo->serie = $peticion->serie;
        $articulo->estado = $peticion->estado;
        $articulo->observaciones = $peticion->observaciones;
        $articulo->precio_venta = $peticion->precioVenta;
        $articulo->areas_id = $datosDecodificados->areas_id;
        $articulo->proveedor_id = $datosDecodificados->proveedor_id;
        return response()->json($articulo->save());
    }

    public function administrarFotos(Request $peticion)
    {
        $idArticulo = $peticion->id;
        return view("articulos.fotos", ["articulo" => Articulo::with(["area", "fotos"])->findOrFail($idArticulo)]);
    }

    public function vistaDarDeBaja(Request $peticion)
    {
        $idArticulo = $peticion->id;
        return view("articulos.dar_de_baja", ["articulo" => Articulo::with(["area"])->findOrFail($idArticulo)]);
    }

    public function agregarFotos(SubirFotosDeArticulosRequest $peticion)
    {
        foreach ($peticion->file("fotos") as $foto) {
            $fotoDeArticulo = new FotoDeArticulo;
            $rutaLarga = $foto->store($this->nombreCarpetaFotos);
            # Por defecto devuelve la ruta como fotos/nombre.png pero debemos quitar "fotos/"
            $fotoDeArticulo->ruta = str_replace("$this->nombreCarpetaFotos/", "", $rutaLarga);
            $fotoDeArticulo->articulos_id = $peticion->id;
            $fotoDeArticulo->save();
        }
        return back()
            ->with("mensaje", "Foto(s) agregada(s) con éxito")
            ->with("tipo", "success");
    }


    public function eliminar(DarArticuloDeBajaRequest $peticion)
    {
        $idArticulo = $peticion->id;

        # Recuperar artículo que se va a eliminar
        $articuloParaEliminar = Articulo::with(["area", "fotos"])->findOrFail($idArticulo);

        # Borrar sus fotos
        foreach ($articuloParaEliminar->fotos as $foto) {
            Storage::disk("local")->delete($this->nombreCarpetaFotos . "/" . $foto->ruta);
            Log::debug("Borrando foto", ["foto" => $foto]);
        }


        # Crear nuevo artículo dado de baja/eliminado
        $articuloDadoDeBaja = new ArticuloDadoDeBaja;
        $articuloDadoDeBaja->fecha_adquisicion = $articuloParaEliminar->fecha_adquisicion;
        $articuloDadoDeBaja->codigo = $articuloParaEliminar->codigo;
        $articuloDadoDeBaja->factura = $articuloParaEliminar->factura;
        $articuloDadoDeBaja->descripcion = $articuloParaEliminar->descripcion;
        $articuloDadoDeBaja->marca = $articuloParaEliminar->marca;
        $articuloDadoDeBaja->modelo = $articuloParaEliminar->modelo;
        $articuloDadoDeBaja->serie = $articuloParaEliminar->serie;
        $articuloDadoDeBaja->estado = $articuloParaEliminar->estado;
        $articuloDadoDeBaja->observaciones = $articuloParaEliminar->observaciones;
        $articuloDadoDeBaja->costo_adquisicion = $articuloParaEliminar->costo_adquisicion;
        $articuloDadoDeBaja->areas_id = $articuloParaEliminar->area->id;

        # Guardar el que se da de baja
        $articuloDadoDeBaja->save();
        # Queremos el id del recién dado de baja
        $idArticuloDadoDeBaja = $articuloDadoDeBaja->id;

        // Guardar sus adjuntos
        foreach ($peticion->file("adjuntos") as $archivoAdjunto) {
            Log::debug("adjunto", ["adjunto" => $archivoAdjunto]);
            $adjuntoDeEliminacionDeArticulo = new AdjuntoDeEliminacionDeArticulo;
            $rutaLarga = $archivoAdjunto->store($this->nombreCarpetaAdjuntos);
            # Por defecto devuelve la ruta como adjuntos/nombre.png pero debemos quitar "adjuntos/"
            $adjuntoDeEliminacionDeArticulo->ruta = str_replace("$this->nombreCarpetaAdjuntos/", "", $rutaLarga);
            $adjuntoDeEliminacionDeArticulo->articulos_eliminados_id = $idArticuloDadoDeBaja;
            $adjuntoDeEliminacionDeArticulo->save();
        }


        # Eliminar el original
        $articuloParaEliminar->delete();

        # Y listo ;)
        return redirect()->route("articulos")
            ->with("mensaje", "Artículo dado de baja")
            ->with("tipo", "success");
    }


    public function mostrarInventario(Articulo $articulo)
    {
        $data = [
            'articulo' => $articulo,
            'totalUnidades' => $articulo->unidadesEnInventario(),
        ];

        return view('articulos.mostrar-inventario', $data);
    }

    public function agregarInventario(Articulo $articulo)
    {
        $data = [
            'articulo' => $articulo,
        ];

        return view('articulos.agregar-inventario', $data);
    }

    public function editarInventario(ArticuloInventario $inventario)
    {
        $data = [
            'inventario' => $inventario,
        ];

        return view('articulos.editar-inventario', $data);
    }

    public function buscar($busqueda)
    {
        return Articulo::orderBy("updated_at", "desc")
            ->orderBy("created_at", "desc")
            ->with(["area", "fotos"])
            ->where("descripcion", "LIKE", "%{$busqueda}%")
            ->paginate(Config::get("constantes.paginas_en_paginacion"));
    }

    public function buscarInventario(Articulo $articulo): LengthAwarePaginator
    {
        return $articulo->inventario()
            ->paginate(Config::get("constantes.paginas_en_paginacion"));
    }

    public function buscarPorProveedor($id)
    {
        return Articulo::orderBy("updated_at", "desc")
            ->orderBy("created_at", "desc")
            ->with(["area", "fotos"])
            ->where("proveedor_id", $id)
            ->paginate(Config::get("constantes.paginas_en_paginacion"));
    }

    public function crearInventario(Articulo $articulo, Request $request): RedirectResponse
    {
        $request->validate([
            'fecha_de_adquisicion' => 'required|date',
            'costo_de_adquisicion' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:0'
        ]);

        $inventario = new ArticuloInventario();

        $inventario->articulo()->associate($articulo);

        $inventario->fecha_adquisicion = $request->fecha_de_adquisicion;
        $inventario->precio_adquisicion = $request->costo_de_adquisicion;
        $inventario->cantidad = $request->cantidad;

        $inventario->save();

        return redirect()
            ->route('articulos.inventario', [$articulo])
            ->with("mensaje", "Inventario Agregado con exito")
            ->with("tipo", "success");
    }

    public function editarInventarioPost(ArticuloInventario $inventario, Request $request): RedirectResponse
    {
        $request->validate([
            'fecha_de_adquisicion' => 'required|date',
            'costo_de_adquisicion' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:0'
        ]);

        $inventario->fecha_adquisicion = $request->fecha_de_adquisicion;
        $inventario->precio_adquisicion = $request->costo_de_adquisicion;
        $inventario->cantidad = $request->cantidad;

        $inventario->save();

        return redirect()
            ->route('articulos.inventario', [$inventario->articulo])
            ->with("mensaje", "Inventario Editado con exito")
            ->with("tipo", "success");
    }


    public function eliminarInventario(Articulo $articulo, Request $request)
    {
        $articulo->inventario()->whereIn('id',json_decode($request->get('ids')))->delete();

        return [
            'mensaje' => 'success',
        ];
    }
}
