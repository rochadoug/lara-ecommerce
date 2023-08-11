<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblClienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // NomeCliente
        // CPF
        // Email
        Schema::create('tbl_cliente', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_usuario');
            $table->string('nome', 100);
            $table->string('cpf', 11)->unique();
            // $table->string('email', 100)->unique();
            $table->string('telefone', 11);
            $table->string('cep', 8);
            $table->string('endereco', 100);
            $table->string('numero', 10);
            $table->string('complemento', 10)->nullable();
            $table->string('cidade', 60);
            $table->timestamps();
            $table->foreign('id_usuario')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_cliente');
    }
}