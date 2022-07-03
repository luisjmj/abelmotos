<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticuloClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulo_cliente', function (Blueprint $table) {
            $table->unsignedInteger("articulo_id");
            $table->foreign("articulo_id")
                ->references("id")
                ->on("articulos")
                ->onDelete("restrict")
                ->onUpdate("cascade");
            $table->unsignedInteger("cliente_id");
            $table->foreign("cliente_id")
                ->references("id")
                ->on("clientes")
                ->onDelete("restrict")
                ->onUpdate("cascade");
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
        Schema::dropIfExists('articulo_cliente');
    }
}
