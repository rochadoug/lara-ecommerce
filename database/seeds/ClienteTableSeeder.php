<?php

use Illuminate\Database\Seeder;

class ClienteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('tbl_cliente')->insert([
            'id_usuario' => 2,
            'nome' => 'Primeiro Cliente',
            'cpf' => '12345678901',
            'telefone' => '11987654321',
            'cep' => '01002900',
            'endereco' => 'Viaduto do Chá',
            'numero' => '15',
            'complemento' => '8 andar',
            'cidade' => 'São Paulo',
            'created_at' => 'now()',
        ]);

        DB::table('tbl_cliente')->insert([
            'id_usuario' => 3,
            'nome' => 'Segundo Cliente',
            'cpf' => '12345678902',
            'telefone' => '11987654321',
            'cep' => '01002900',
            'endereco' => 'Viaduto do Chá',
            'numero' => '15',
            'complemento' => '8 andar',
            'cidade' => 'São Paulo',
            'created_at' => 'now()',
        ]);
    }
}
