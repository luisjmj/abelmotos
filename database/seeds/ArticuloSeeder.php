<?php

use App\Area;
use App\Articulo;
use Illuminate\Database\Seeder;

class ArticuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $articulos = [
            [
                'costo' => 100,
            ],
            [
                'costo' => 150,
            ]
        ];

        $area = Area::first();
        $now = now()->subDay(1);

        foreach ($articulos as $index => $articulo) {
            $index ++;
            $instanciaArticulo = new Articulo();

            $instanciaArticulo->fecha_adquisicion = $now;

            $instanciaArticulo->codigo = 'TEST CODIGO';
            $instanciaArticulo->descripcion = "TEST DESCRIPCION #$index";
            $instanciaArticulo->marca = 'TEST MARCA';
            $instanciaArticulo->modelo = "TEST ARTICULO #$index";
            $instanciaArticulo->serie = "TEST SERIE #$index";
            $instanciaArticulo->estado = 'TEST ESTADO';

            $instanciaArticulo->costo_adquisicion = $articulo['costo'];

            $instanciaArticulo->area()->associate($area);

            $instanciaArticulo->save();
        }
    }
}
