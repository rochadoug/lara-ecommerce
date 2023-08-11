<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblProdutoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // NomeProduto
        // CodBarras
        // ValorUnitario
        Schema::create('tbl_produto', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 255);
            $table->text('descricao');
            $table->string('cod_barras', 20);
            $table->decimal('valor')->default(0);
            $table->boolean('ativo')->default(true);
            $table->string('imagem', 255);
            $table->integer('quantidade')->default(0);
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
        Schema::dropIfExists('tbl_produto');
    }
}
