<?php

use App\Cliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clientes = [
            [
                'nombre' => 'EDGARDO ZAMBRANO',
                'dni' => '24356449',
                'direccion' => 'SAN JOSE, COSTA RICA',
                'email' => 'eadaggerz@gmail.com'
            ]
        ];

        foreach($clientes as $cliente) {
            Cliente::create($cliente);
        }
    }
}
