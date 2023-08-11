<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pedido;
use Illuminate\Support\Facades\DB;

class PedidosController extends Controller
{
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

        $listRecords = Pedido::with('Produtos', 'PedidoStatus', 'Cliente');

        if ($filterDate)
            $listRecords->whereDate('created_at', '>=', $date[0])
                        ->whereDate('created_at', '<=', $date[1]);

        $listRecords = $listRecords->sortable(['id' => 'desc'])->paginate($ftrPage);
        $links = $listRecords->appends(request()->except('page'))->links();

        $params = array(
            //'action'        => 'index',
            'listRecords'   => $listRecords,
            'record'        => new Pedido,
            'links'         => $links,
            'ftrPage'       => $ftrPage,
            'ftrDate'       => $filterDate ? (implode('/', array_reverse(explode('-', $date[0]))).' - '.implode('/', array_reverse(explode('-', $date[1])))) : ''
        );

        return view('admin/pedidos', $params);
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
        $cliente = $request->cliente;

        $pedidoItens = $request->except('_token', '_method', 'cliente');

        try {
            $total = 0;
            foreach($pedidoItens as $key => $item) {
                if ($item['quantidade'] == 0)
                    unset($pedidoItens[$key]);
                else
                    $total += $item['quantidade']*$item['valor'];
            }

            // Altera o Array Cart para os campos da tabela Pivot
            //array_unset_recursive_key($pedidoItens, ['valor'], 2);

            $pedido = new Pedido;
            $pedido->id_cliente = $cliente;
            $pedido->id_usuario_update = auth()->user()->id;
            $pedido->id_pedido_status = 1;
            $pedido->valor = $total;
            $pedido->save();

            $pedido->Produtos()->sync($pedidoItens);

            return redirect('admin/pedidos')->withSuccess('Alterações feitas com sucesso!');
        } catch (Exception $e) {
            return $e->getMessage();
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
        $record = Pedido::with('Produtos', 'PedidoStatus', 'Cliente')->find($id);
        //d( $record );

        $record->id_pedido_status = 7;
        $record->save();
        $record->refresh();

        $params = array(
            //'action'        => 'show',
            'listRecords'   => new Pedido,
            'record'        => $record,
        );

        return view('admin/pedidos', $params);
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
        $pedidoItens = $request->except('_token', '_method', 'cliente');

        try {
            $total = 0;
            foreach($pedidoItens as $key => $item) {
                if ($item['quantidade'] == 0)
                    unset($pedidoItens[$key]);
                else
                    $total += $item['quantidade']*$item['valor'];
            }

            // Altera o Array Cart para os campos da tabela Pivot
            //array_unset_recursive_key($pedidoItens, ['valor'], 2);

            foreach($pedidoItens as $k => $v) {
                $pedidoItens[$k]['updated_at'] = 'now()';
            }

            $pedido = Pedido::find($id);
            $pedido->id_usuario_update = auth()->user()->id;
            $pedido->id_pedido_status = 1;
            $pedido->valor = $total;
            $pedido->save();

            $pedido->Produtos()->sync($pedidoItens);

            return redirect('admin/pedidos')->withSuccess('Alterações feitas com sucesso!');
        } catch (Exception $e) {
            return $e->getMessage();
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
        $pedido = $id;

        try {

            $pedido = Pedido::find($id);

            if (!$pedido)
                redirect()->back()->withErrors('Pedido não encontrado!');

            $pedido->id_usuario_update = auth()->user()->id;
            $pedido->id_pedido_status = 6;
            $pedido->save();

            return redirect('admin/pedidos');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    protected function validator($data, $record=null)
    {
        //$customAttributes = ['' => '', '' => ''];

        return Validator::make($data, [
            ''          => ['required'],
        ], []/* , $customAttributes */)->validate();
    }
}
