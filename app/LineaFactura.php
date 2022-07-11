<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LineaFactura extends Model
{
    use SoftDeletes;

    protected $table = 'linea_facturas';

    protected $fillable = [
        'factura_id',
        'articulo_id',
        'cantidad',
        'sub_total',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'sub_total' => 'decimal:2',
    ];

    public function articulo() {
        return $this->belongsTo('App\Articulo', 'articulo_id', 'id');
    }

    public function getPrecioUnitario()
    {
        return number_format($this->sub_total/$this->cantidad, 2, '.', '');
    }
}
