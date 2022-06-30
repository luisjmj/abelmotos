<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticuloInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulo_inventarios', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('articulo_id');
            $table->foreign('articulo_id')->references('id')->on('articulos');

            $table->dateTime('fecha_adquisicion');
            $table->unsignedDecimal('precio_adquisicion',24,4);
            $table->unsignedInteger('cantidad');

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
        Schema::dropIfExists('articulo_inventarios');
    }
}
