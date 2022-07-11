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

    protected $casts = [
        'monto' => 'decimal:2',
        'tasa' => 'decimal:2'
    ];

    protected $with = ['divisa'];

    public function divisa() {
        return $this->belongsTo('App\Divisa', 'divisa_id', 'id');
    }

    public function getCambioAlDolar()
    {
        return number_format($this->monto/$this->tasa, 2, '.', '');
    }
}
