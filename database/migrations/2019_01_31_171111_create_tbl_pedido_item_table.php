<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPedidoItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pedido_item', function (Blueprint $table) {
            //$table->increments('id');
            $table->unsignedInteger('id_pedido');
            $table->unsignedInteger('id_produto');
            $table->smallInteger('quantidade');
            $table->decimal('valor');
            $table->timestamps();
            $table->foreign('id_pedido')->references('id')->on('tbl_pedido');
            $table->foreign('id_produto')->references('id')->on('tbl_produto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_pedido_item');
    }
}

/*
    IF creates a model for this, set:
    protected $primaryKey = null;
    public $incrementing = false;
*/