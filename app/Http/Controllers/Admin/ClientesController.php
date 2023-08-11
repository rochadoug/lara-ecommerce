<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Cliente;
use App\User;
use App\Notifications\UserWelcomePasswordNotification;

class ClientesController extends Controller
{

    //use VerifiesEmails;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $filterDate = false;
        $ftrPage = $request->input('ftrPage') ?? 20;

        if ($request->input('ftrDate')) {
            $filterDate = true;
            $date = explode(' - ', $request->input('ftrDate'));
        } else {
            $date = array(date('Y-m-d', strtotime('-7 day')), date('Y-m-d'));
        }

        $listRecords = Cliente::withoutGlobalScopes();

        if ($filterDate)
            $listRecords->whereDate('created_at', '>=', $date[0])
                        ->whereDate('created_at', '<=', $date[1]);

        $listRecords = $listRecords->sortable(['id' => 'desc'])->paginate($ftrPage);
        $links = $listRecords->appends(request()->except('page'))->links();

        $params = array(
            //'action'        => 'index',
            'listRecords'   => $listRecords,
            'record'        => new Cliente,
            'links'         => $links,
            'ftrPage'       => $ftrPage,
            'ftrDate'       => $filterDate ? (implode('/', array_reverse(explode('-', $date[0]))).' - '.implode('/', array_reverse(explode('-', $date[1])))) : ''
        );

        return view('admin/clientes', $params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->except('_token', '_method');

        $data['cpf']        = preg_replace('/\D/', '', $data['cpf']);
        $data['telefone']   = preg_replace('/\D/', '', $data['telefone']);
        $data['cep']        = preg_replace('/\D/', '', $data['cep']);
        $data['numero']     = preg_replace('/\D/', '', $data['numero']);

        try {
            DB::beginTransaction();

            $user = new User;
            $cliente = new Cliente;

            // Valida campos
            $this->validator($data);

            $user->email                = $data['email'];
            $user->name                 = $data['nome'];
            $password                   = str_random(8);
            $user->password             = Hash::make($password);
            $user->email_verified_at    = 'now()';
            $user->save();

            $cliente->id_usuario        = $user->id;
            $cliente->nome              = $data['nome'];
            $cliente->cpf               = $data['cpf'];
            $cliente->telefone          = $data['telefone'];
            $cliente->cep               = $data['cep'];
            $cliente->endereco          = $data['endereco'];
            $cliente->numero            = $data['numero'];
            $cliente->complemento       = $data['complemento'];
            $cliente->cidade            = $data['cidade'];
            $cliente->save();

            // Envio de e-mail de boas vindas
            //$user->notify(new UserWelcomePasswordNotification($invoice));
            $user->notify(new UserWelcomePasswordNotification($password));

            // Envio de e-mail de confirmação de e-mail
            // Cadastrado pelo Admin, não é necessário verificar!
            //$user->sendEmailVerificationNotification();

            DB::commit();

            return redirect('admin/clientes')->withSuccess('Alterações feitas com sucesso!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $record = Cliente::with('User')->find($id);
        //d( $record );

        $params = array(
            //'action'        => 'show',
            'listRecords'   => Cliente::paginate(0),
            'links'         => '',
            'record'        => $record,
        );

        return view('admin/clientes', $params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $data = $request->except('_token', '_method');

        $data['cpf']        = preg_replace('/\D/', '', $data['cpf']);
        $data['telefone']   = preg_replace('/\D/', '', $data['telefone']);
        $data['cep']        = preg_replace('/\D/', '', $data['cep']);
        $data['numero']     = preg_replace('/\D/', '', $data['numero']);

        try {
            DB::beginTransaction();

            $cliente = Cliente::find($id);

            // Valida campos
            $this->validator($data, $cliente);

            $cliente->nome          = $data['nome'];
            $cliente->cpf           = $data['cpf'];
            $cliente->telefone      = $data['telefone'];
            $cliente->cep           = $data['cep'];
            $cliente->endereco      = $data['endereco'];
            $cliente->numero        = $data['numero'];
            $cliente->complemento   = $data['complemento'];
            $cliente->cidade        = $data['cidade'];
            $cliente->save();

            $cliente->User->email   = $data['email'];
            $cliente->User->name    = $data['nome'];
            $cliente->User->save();

            DB::commit();

            return redirect('admin/clientes')->withSuccess('Alterações feitas com sucesso!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $cliente = $id;

        try {

            $cliente = Cliente::find($id);

            if (!$cliente)
                redirect()->back()->withErrors('Cliente não encontrado!');

            $cliente->delete();

            return redirect('admin/clientes');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    protected function validator($data, $record=null)
    {
        $customAttributes = ['cpf' => 'CPF', 'cep' => 'CEP'];

        return Validator::make($data, [
            'nome'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'.($record?',email,'.$record->User->id:'')],

            'cpf'           => ['required', 'digits:11', 'unique:tbl_cliente'.($record?',cpf,'.$record->id:'')],
            'telefone'      => ['required', 'digits_between:10,11'],
            'cep'           => ['required', 'digits:8'],
            'endereco'      => ['required', 'string', 'min:6', 'max:100'],
            'numero'        => ['required', 'digits_between:1,10'],
            'complemento'   => ['nullable', 'string', 'max:10'],
            'cidade'        => ['required', 'string', 'min:3', 'max:60'],
        ], [], $customAttributes)->validate();
    }

    public function ajaxSearch(Request $request)
    {
        //
        //dd( $request->cliente, $request->input('cliente') );

        //$this->validator($request);

        $term = $request->cliente;

        if ($term === null)
            return [];

        if (ctype_digit($term)) {
            $term = ltrim($term, '0');
            $clientes = [Cliente::select('id', 'nome')->find($term)];
        } else
            $clientes = Cliente::select('id', 'nome')->where('nome', 'ilike', $term.'%')->get();

        return $clientes;
    }
}

//// New Request Methods
//$newRequest = new Request;

//// For POST method
//$newRequest->setMethod('POST');
//$newRequest->request->add(['user' => $user]);

//// By the way using $newRequest->query->add() you can add data to a GET request.
//$newRequest->replace(['foo' => 'bar']);

//// Other method
//$newRequest->merge(['foo' => 'bar']);