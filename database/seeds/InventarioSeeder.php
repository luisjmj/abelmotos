<?php

use App\Articulo;
use App\ArticuloInventario;
use Illuminate\Database\Seeder;

class InventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inventario = [
            [
                'cantidad' => '2',
            ],
            [
                'cantidad' => '3',
            ]
        ];

        $articulo = Articulo::first();

        $fecha = now();

        foreach ($inventario as $item) {
            $instanciaInventario = new ArticuloInventario();

            $instanciaInventario->fecha_adquisicion = $fecha->subDay();
            $instanciaInventario->precio_adquisicion = 200;
            $instanciaInventario->cantidad = $item['cantidad'];

            $instanciaInventario->articulo()->associate($articulo);

            $instanciaInventario->save();
        }

    }
}
