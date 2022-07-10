<?php

namespace App;

use App\RemitenteFactura;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factura extends Model
{
    use SoftDeletes;

    protected $table = 'facturas';

    protected $fillable = [
        'cliente_id',
        'remitente_id',
    ];

    // RELATIONSHIPS

    public function remitente()
    {
        return $this->belongsTo('App\RemitenteFactura', 'remitente_id', 'id');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'cliente_id', 'id');
    }

    public function lineas_factura()
    {
        return $this->hasMany('App\LineaFactura', 'factura_id', 'id');
    }

    public function divisas_factura()
    {
        return $this->hasMany('App\DivisaFactura', 'factura_id', 'id');
    }


    // METHODS

    public function getTotal()
    {
        $total = 0;
        foreach ($this->lineas_factura as $linea) {
            $total += $linea->sub_total;
        }
        return $total;
    }

    public static function createWithAll($data)
    {
        $factura = Factura::create([
            'remitente_id' => RemitenteFactura::first()->id,
            'cliente_id' => $data['cliente_id'],
        ]);

        foreach($data['lineas_factura'] as $linea) {
            $factura->lineas_factura()->create([
                'cantidad' => $linea['cantidad'],
                'articulo_id' => $linea['articulo_id'],
                'sub_total' => $linea['sub_total'],
            ]);
        }

        foreach($data['divisas_factura'] as $divisa) {
            $factura->divisas_factura()->create([
                'divisa_id' => $divisa['divisa_id'],
                'payment_method_id' => $divisa['payment_method_id'],
                'monto' => $divisa['monto'],
                'tasa' => $divisa['tasa'],
            ]);
        }
    }
}
