<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('tbl_admin')->insert([
            'id_usuario' => 1,
            'nome' => 'Emmerson Ribeiro Becker',
            'created_at' => 'now()',
        ],
        [
            'id_usuario' => 2,
            'nome' => 'Décio Lehmkuhl',
            'created_at' => 'now()',
        ]);
    }
}
