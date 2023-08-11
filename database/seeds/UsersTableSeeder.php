<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'name' => 'Administrador Senior',
            'email' => 'admin@ecommerce_project.com',
            'password' => bcrypt('123456'),
            'admin' => true,
            'created_at' => 'now()',
            'email_verified_at' => 'now()',
        ]);

        DB::table('users')->insert([
            'name' => 'Primeiro Cliente Ecommerce',
            'email' => 'pclecomm@ecommerce_project.com',
            'password' => bcrypt('123456'),
            'admin' => false,
            'created_at' => 'now()',
            'email_verified_at' => 'now()',
        ]);

        DB::table('users')->insert([
            'name' => 'Segundo Cliente Ecommerce',
            'email' => 'sclecomm@ecommerce_project.com',
            'password' => bcrypt('123456'),
            'admin' => false,
            'created_at' => 'now()',
            'email_verified_at' => 'now()',
        ]);
    }
}
