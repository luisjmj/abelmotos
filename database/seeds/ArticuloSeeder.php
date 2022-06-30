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
                'precio' => 100,
            ],
            [
                'precio' => 150,
            ]
        ];

        $area = Area::first();

        foreach ($articulos as $index => $articulo) {
            $index ++;
            $instanciaArticulo = new Articulo();

            $instanciaArticulo->codigo = 'TEST CODIGO';
            $instanciaArticulo->descripcion = "TEST DESCRIPCION #$index";
            $instanciaArticulo->marca = 'TEST MARCA';
            $instanciaArticulo->modelo = "TEST ARTICULO #$index";
            $instanciaArticulo->serie = "TEST SERIE #$index";
            $instanciaArticulo->estado = 'regular';

            $instanciaArticulo->precio_venta = $articulo['precio'];

            $instanciaArticulo->area()->associate($area);

            $instanciaArticulo->save();
        }
    }
}
