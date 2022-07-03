<?php

namespace App\Http\Controllers;

use App\Proveedor;
use App\Http\Requests\AgregarProveedorRequest;
use App\Http\Requests\GuardarCambiosDeProveedorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ProveedorController extends Controller
{
    public function mostrar(Request $request) {
        return Proveedor::orderBy("updated_at", "desc")
            ->orderBy("created_at", "desc")
            ->paginate(Config::get("constantes.paginas_en_paginacion"));
    }

    public function agregar(AgregarProveedorRequest $request) {
        $datosDecodificados = json_decode($request->getContent());
        $proveedor = new Proveedor();
        $proveedor->nombre = $request->nombre;
        $proveedor->rif = $request->rif;
        $proveedor->telefono = $request->telefono;
        $proveedor->email = $request->email;
        return response()->json($proveedor->save());
    }

    public function buscar(Request $request) {
        $busqueda = urldecode($request->busqueda);
        return Proveedor::where("nombre", "like", "%$busqueda%")
            ->paginate(Config::get("constantes.paginas_en_paginacion"));
    }

    public function porId(Request $request)
    {
        $id = $request->id;
        $proveedor = Proveedor::where("id", "=", $id)->first();
        return response()->json($proveedor);
    }

    public function guardarCambios(GuardarCambiosDeProveedorRequest $request)
    {
        $datosDecodificados = json_decode($request->getContent());
        $id = $datosDecodificados->id;
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->nombre = $datosDecodificados->nombre;
        $proveedor->rif = $datosDecodificados->rif;
        $proveedor->telefono = $datosDecodificados->telefono;
        $proveedor->email = $datosDecodificados->email;
        return response()->json($proveedor->save());

    }

    public function eliminar($id) {
        $proveedor = Proveedor::find($id);
        $proveedor->delete();
    }

    public function eliminarMuchos(Request $request)
    {
        $ids = json_decode($request->getContent());
        return Proveedor::destroy($ids);
    }

}
