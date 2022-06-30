<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticuloInventario extends Model
{
    protected $table = 'articulo_inventarios';

    protected $dates = ['fecha_adquisicion'];

    public function articulo(): BelongsTo
    {
        return $this->belongsTo(Articulo::class,'articulo_id');
    }
}
