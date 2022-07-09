<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisaFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('divisa_facturas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('divisa_id');
            $table->foreign('divisa_id')
                ->references('id')
                ->on('divisas');
            $table->unsignedInteger('factura_id');
            $table->foreign('factura_id')
                ->references('id')
                ->on('facturas');
            $table->unsignedInteger('payment_method_id');
            $table->foreign('payment_method_id')
                ->references('id')
                ->on('payment_methods');
            $table->unsignedDecimal("monto", 20, 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('divisa_facturas');
    }
}
