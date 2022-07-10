<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DivisaFactura extends Model
{
    protected $table = 'divisa_facturas';

    protected $fillable = [
        'factura_id',
        'divisa_id',
        'payment_method_id',
        'monto',
        'tasa'
    ];
}
