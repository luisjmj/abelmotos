<?php

namespace App\Http\Controllers;

use App\Factura;
use Illuminate\Http\Request;

class FacturasController extends Controller
{
    public function mostrar(Request $request)
    {
        return Factura::orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(Config::get('constantes.paginas_en_paginacion'));
    }
}
