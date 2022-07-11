<?php

namespace App\Http\Controllers;

use App\Factura;
use App\RemitenteFactura;
use App\Http\Requests\GuardarFacturaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class FacturasController extends Controller
{
    public function mostrar(Request $request)
    {
        return Factura::orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(Config::get('constantes.paginas_en_paginacion'));
    }

    public function store(GuardarFacturaRequest $request)
    {
        try {
            Factura::createWithAll($request->all());
            return response()->json(['error' => false], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Error al crear la factura, intente más tarde', 'server_error' => $e->getMessage()], 400);
        }
    }

    public function eliminar(Factura $factura)
    {
        $factura->delete();
        return response()->json(['error' => false], 200);
    }

    public function ver(Factura $factura)
    {
        $data = [
            'factura' => $factura,
        ];

        return view('facturas.ver')->with($data);
    }
}
