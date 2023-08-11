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
            'id_usuario' => 3,
            'nome' => 'Pyetro Costa',
            'cpf' => '36288099830',
            'telefone' => '14997980535',
            'cep' => '17522400',
            'endereco' => 'Rua Virgílio Carvalho Oliveira',
            'numero' => '29',
            'complemento' => null,
            'cidade' => 'Marília',
            'created_at' => 'now()',
        ]);
    }
}
