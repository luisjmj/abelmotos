<?php

namespace App\Http\Controllers;

use App\Divisa;
use App\Http\Requests\GuardarDivisa;
use App\Http\Requests\GuardarCambiosDeDivisa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class DivisasController extends Controller
{
    //
    public function agregar(GuardarDivisa $peticion)
    {
        $divisa = new Divisa;
        $divisa->nombre = $peticion->nombre;
        $divisa->tasa = $peticion->tasa;
        $exitoso = $divisa->save();
        $mensaje = "Divisa agregada correctamente";
        $tipo = "success";
        if (!$exitoso) {
            $mensaje = "Error agregando divisa. Intente más tarde";
            $tipo = "danger";
        }
        return redirect()->route("formularioDivisa")
            ->with("mensaje", $mensaje)
            ->with("tipo", $tipo);
    }

    public function mostrar()
    {
        return Divisa::orderBy("updated_at", "desc")
            ->orderBy("created_at", "desc")
            ->paginate(Config::get("constantes.paginas_en_paginacion"));
    }

    public function buscar(Request $peticion)
    {
        $busqueda = urldecode($peticion->busqueda);
        return Divisa::where("nombre", "like", "%$busqueda%")
            ->paginate(Config::get("constantes.paginas_en_paginacion"));
    }

    public function editar(Request $peticion)
    {
        $id = $peticion->id;
        $divisa = Divisa::findOrFail($id);
        return view("editar_divisa", [
            "divisa" => $divisa,
        ]);
    }

    public function eliminar($id)
    {
        $divisa = Divisa::find($id);
        $divisa->delete();
    }

    public function guardarCambios(GuardarCambiosDeDivisa $peticion)
    {
        $id = $peticion->input("id");
        $divisa = Divisa::findOrFail($id);
        $divisa->nombre = $peticion->input("nombre");
        $divisa->tasa = $peticion->input("tasa");
        $divisa->save();
        return redirect()->route("divisas")->with(["mensaje" => "Divisa editada correctamente", "tipo" => "success"]);

    }

    public function eliminarMuchas(Request $peticion)
    {
        $idsParaEliminar = json_decode($peticion->getContent());
        return Divisa::destroy($idsParaEliminar);
    }
}
