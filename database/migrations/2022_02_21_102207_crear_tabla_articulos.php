<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaArticulos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("proveedor_id");
            $table->foreign("proveedor_id")
                ->references("id")
                ->on("proveedores")
                ->onDelete("restrict")
                ->onUpdate("cascade");
            $table->unsignedInteger("responsables_id");
            $table->foreign("responsables_id")
                ->references("id")
                ->on("responsables")
                ->onDelete("restrict")
                ->onUpdate("cascade");
            $table->string("codigo", 255);
            $table->string("factura", 255)->nullable();
            $table->string("descripcion", 255);
            $table->string("marca", 255);
            $table->string("modelo", 255);
            $table->string("serie", 255);
            $table->string("estado", 255);
            $table->string("observaciones", 255)->nullable();
            $table->unsignedDecimal("precio_venta", 20, 4);
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
        Schema::dropIfExists('articulos');
    }
}
