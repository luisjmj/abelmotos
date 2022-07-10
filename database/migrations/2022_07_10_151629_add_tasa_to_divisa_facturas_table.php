<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTasaToDivisaFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('divisa_facturas', function (Blueprint $table) {
            $table->unsignedDecimal("tasa", 20, 4);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('divisa_facturas', function (Blueprint $table) {
            $table->dropColumn("tasa");
        });
    }
}
