<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientotransferenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientotransferencias', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('movimiento_id')->unsigned();
            $table->foreign('movimiento_id')->references('id')->on('movimientos')->onDelete('cascade');
            $table->bigInteger('cuenta_id')->unsigned();
            $table->foreign('cuenta_id')->references('id')->on('cuentas')->onDelete('cascade');
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
        Schema::dropIfExists('movimientotransferencias');
    }
}
