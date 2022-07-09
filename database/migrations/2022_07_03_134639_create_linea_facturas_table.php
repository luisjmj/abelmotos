<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLineaFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linea_facturas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('factura_id');
            $table->foreign('factura_id')
                ->references('id')
                ->on('facturas');
            $table->unsignedInteger('articulo_id');
            $table->foreign('articulo_id')
                ->references('id')
                ->on('articulos');
            $table->unsignedDecimal("sub_total", 20, 4);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('linea_facturas');
    }
}
