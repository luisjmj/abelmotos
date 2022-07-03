<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = "clientes";

    public function articulos()
    {
        return $this->hasMany("App\Articulo", "id");
    }
}
