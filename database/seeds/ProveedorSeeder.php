<?php

use App\Proveedor;
use Illuminate\Database\Seeder;

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Proveedor::create(
            [
                'nombre' => 'BERA',
                'rif' => 'J-231212121-3',
                'telefono' => '02763330002',
                'email' => 'bera@motos.com'
            ]
        );
    }
}
