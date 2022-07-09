<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LineaFactura extends Model
{
    use SoftDeletes;

    protected $table = 'linea_facturas';
}
