<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPedidoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // NumeroPedido
        // DtPedido
        // Quantidade
        Schema::create('tbl_pedido', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('numero');
            $table->unsignedInteger('id_cliente');
            $table->unsignedInteger('id_pedido_status')->default(1);
            $table->unsignedInteger('id_usuario_update')->nullable();
            $table->decimal('valor');
            $table->timestamps();
            $table->foreign('id_cliente')->references('id')->on('tbl_cliente');
            $table->foreign('id_pedido_status')->references('id')->on('tbl_pedido_status');
            $table->foreign('id_usuario_update')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_pedido');
    }
}
