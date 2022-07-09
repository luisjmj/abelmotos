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
        $names = [ 'efectivo', 'banco de venezuela', 'debito', 'zelle', 'paypal', 'pago movil'];

        foreach($names as $name){
            PaymentMethod::create([
                'nombre' => $name
            ]);
        }
    }
}
