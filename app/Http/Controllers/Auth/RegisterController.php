<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Cliente;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator($data)
    {
        $data['cpf']        = preg_replace('/\D/', '', $data['cpf']);
        $data['telefone']   = preg_replace('/\D/', '', $data['telefone']);
        $data['cep']        = preg_replace('/\D/', '', $data['cep']);
        $data['numero']     = preg_replace('/\D/', '', $data['numero']);

        $customAttributes = ['cpf' => 'CPF', 'cep' => 'CEP'];

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],

            'cpf' => ['required', 'digits:11', 'unique:tbl_cliente'],
            'telefone' => ['required', 'digits_between:10,11'],
            'cep' => ['required', 'digits:8'],
            'endereco' => ['required', 'string', 'min:6', 'max:100'],
            'numero' => ['required', 'digits_between:1,10'],
            'complemento' => ['nullable', 'string', 'max:10'],
            'cidade' => ['required', 'string', 'min:3', 'max:60'],
        ], [], $customAttributes);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $cliente = DB::table('tbl_cliente')->insert([
                'id_usuario' => $user->id,
                'nome' => $data['name'],
                'cpf' => preg_replace('/\D/', '', $data['cpf']),
                'telefone' => preg_replace('/\D/', '', $data['telefone']),
                'cep' => preg_replace('/\D/', '', $data['cep']),
                'endereco' => $data['endereco'],
                'numero' => preg_replace('/\D/', '', $data['numero']),
                'complemento' => $data['complemento'],
                'cidade' => $data['cidade'],
                'created_at' => 'now()',
            ]);

            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors( $e->getMessage() );
        }
    }
}
