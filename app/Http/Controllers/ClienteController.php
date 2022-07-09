<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Http\Requests\AgregarClienteRequest;
use App\Http\Requests\GuardarCambiosDeClienteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ClienteController extends Controller
{
    public function mostrar(Request $request) {
        return Cliente::orderBy("updated_at", "desc")
            ->orderBy("created_at", "desc")
            ->paginate(Config::get("constantes.paginas_en_paginacion"));
    }

    public function mostrarTodos(Request $request) {
        return response()->json(Cliente::all());
    }

    public function agregar(AgregarClienteRequest $request) {
        $datosDecodificados = json_decode($request->getContent());
        $cliente = new Cliente();
        $cliente->dni = $request->dni;
        $cliente->nombre = $request->nombre;
        $cliente->direccion = $request->direccion;
        $cliente->email = $request->email;
        return response()->json($cliente->save());
    }

    public function buscar(Request $request) {
        $busqueda = urldecode($request->busqueda);
        return Cliente::where("nombre", "like", "%$busqueda%")
            ->paginate(Config::get("constantes.paginas_en_paginacion"));
    }

    public function porId(Request $request)
    {
        $id = $request->id;
        $cliente = Cliente::where("id", "=", $id)->first();
        return response()->json($cliente);
    }

    public function guardarCambios(GuardarCambiosDeClienteRequest $request)
    {
        $datosDecodificados = json_decode($request->getContent());
        $id = $datosDecodificados->id;
        $cliente = Cliente::findOrFail($id);
        $cliente->nombre = $datosDecodificados->nombre;
        $cliente->direccion = $datosDecodificados->direccion;
        $cliente->dni = $datosDecodificados->dni;
        $cliente->email = $datosDecodificados->email;
        return response()->json($cliente->save());

    }

    public function eliminar($id) {
        $cliente = Cliente::find($id);
        $cliente->delete();
    }

    public function eliminarMuchos(Request $request)
    {
        $ids = json_decode($request->getContent());
        return Cliente::destroy($ids);
    }

}
