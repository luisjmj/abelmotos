<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Articulo extends Model
{
    protected $table = "articulos";

    public function area(): BelongsTo
    {
        return $this->belongsTo("App\Area", "areas_id");
    }

    public function fotos(): HasMany
    {
        return $this->hasMany("App\FotoDeArticulo", "articulos_id");
    }

    public function inventario()
    {
        return $this->hasMany(ArticuloInventario::class,'articulo_id');
    }
}
