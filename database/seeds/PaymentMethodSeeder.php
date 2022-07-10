<?php

use App\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payment_methods = [
            [
                'nombre' => 'Dólares en Efectivo',
                'divisa_id' => 1
            ],
            [
                'nombre' => 'Pesos Colombianos en Efectivo',
                'divisa_id' => 4
            ],
            [
                'nombre' => 'Bolívares Banco de Venezuela',
                'divisa_id' => 3
            ],
            [
                'nombre' => 'Bolívares Débito',
                'divisa_id' => 3
            ],
            [
                'nombre' => 'Bolívares en Efectivo',
                'divisa_id' => 3
            ],
            [
                'nombre' => 'Zelle',
                'divisa_id' => 1
            ],
            [
                'nombre' => 'Paypal',
                'divisa_id' => 1
            ],
            [
                'nombre' => 'Pago Móvil',
                'divisa_id' => 3
            ],
            [
                'nombre' => 'Euros en Efectivo',
                'divisa_id' => 2
            ]
        ];

        foreach ($payment_methods as $payment_method) {
            PaymentMethod::create($payment_method);
        }
    }
}
