<?php

use App\RemitenteFactura;
use Illuminate\Database\Seeder;

class RemitenteFacturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RemitenteFactura::create(
            [
                'nombre' => 'ABEL MOTOS',
                'rif' => "J-99912121-1",
                'email' => 'abel@motos.com'
            ]
        );
    }
}
