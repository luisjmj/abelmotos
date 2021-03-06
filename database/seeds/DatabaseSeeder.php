<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            AreaSeeder::class,
            ProveedorSeeder::class,
            ArticuloSeeder::class,
            InventarioSeeder::class,
            RemitenteFacturaSeeder::class,
            DivisaSeeder::class,
            PaymentMethodSeeder::class,
            ClienteSeeder::class
        ]);
    }
}
