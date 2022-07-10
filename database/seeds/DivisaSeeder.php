<?php

use App\Divisa;
use Illuminate\Database\Seeder;

class DivisaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $divisas = [
            [
                'nombre' => 'Dolar',
                'tasa' => '1'
            ],
            [
                'nombre' => 'Euro',
                'tasa' => '0.95'
            ],
            [
                'nombre' => 'Bolivar',
                'tasa' => '5.57'
            ],
            [
                'nombre' => 'Peso Colombiano',
                'tasa' => '4400'
            ]
        ];

        foreach ($divisas as $divisa) {
            Divisa::create($divisa);
        }
    }
}
