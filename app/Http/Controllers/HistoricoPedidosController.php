<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pedido;

class HistoricoPedidosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $filtros = $request->except('_token');

        if ($filtros) {

            if ( strtotime( implode('-', array_reverse(explode('/', $filtros['startDt'])))) > strtotime( implode('-', array_reverse(explode('/', $filtros['endDt'])))) )
                return redirect()->back()->withErrors('A data inicial não pode ser maior que a data final!')->withInput();

            if ((!isset($filtros['startDt']) or (isset($filtros['startDt']) and $filtros['startDt'] == ''))
                    or
                    (!isset($filtros['endDt']) or (isset($filtros['endDt']) and $filtros['endDt'] == '')))
                return redirect()->back()->withErrors('É necessário preencher A data inicial e a data final!')->withInput();
        } else {
            $filtros['startDt'] = null;
            $filtros['endDt'] = null;
        }

        $pedidos = Pedido::with('Produtos', 'PedidoStatus')->where('id_cliente', auth()->user()->Cliente->id);

        if ($filtros['startDt'] and $filtros['endDt']) {
            $pedidos->whereDate('created_at', '>=', $filtros['startDt'])->whereDate('created_at', '<=', $filtros['endDt']);
        }

        $pedidos = $pedidos->orderBy('created_at', 'desc')->get();

        foreach ($pedidos as $k => $v) {
            $from = new \DateTime($v->updated_at);
            $diff = $from->diff(new \DateTime(date("Y-m-d H:i:s")));

            $minutes = $diff->days * 24 * 60;
            $minutes += $diff->h * 60;
            $minutes += $diff->i;

            $pedidos[$k]->elapsedTime = str_pad($minutes, 2, '0', STR_PAD_LEFT).':'.str_pad($diff->s, 2, '0', STR_PAD_LEFT);
        }

        return view('historico', array('action' => 'historico', 'pedidos' => $pedidos, 'startDt' => $filtros['startDt'], 'endDt' => $filtros['endDt']));
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
        $pedido = Pedido
                        ::where('id', $id)
                        ->where('id_cliente', auth()->user()->Cliente->id)
                        ->whereIn('id_pedido_status', [1, 2, 7])
                        ->first();

        if ($pedido) {
            $pedido->id_pedido_status = 7;
            $pedido->save();

            $pedido->refresh();

            $from = new \DateTime($pedido->updated_at);
            $diff = $from->diff(new \DateTime(date("Y-m-d H:i:s")));

            $minutes = $diff->days * 24 * 60;
            $minutes += $diff->h * 60;
            $minutes += $diff->i;

            $pedido->elapsedTime = str_pad($minutes, 2, '0', STR_PAD_LEFT).':'.str_pad($diff->s, 2, '0', STR_PAD_LEFT);;
        } else {
            return redirect()->back()->withErrors('O Pedido já não pode mais ser alterado!');
        }

        $pedido->load('Produtos', 'PedidoStatus');

        return view('historico', array('action' => 'alteracao', 'pedido' => $pedido));
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
        $pedidoItens = $request->except('_token', '_method');

        try {
            $total = 0;
            foreach($pedidoItens as $key => $item) {
                if ($item['quantidade'] == 0)
                    unset($pedidoItens[$key]);
                $total += $item['quantidade']*$item['valor'];
            }

            // Altera o Array Cart para os campos da tabela Pivot
            array_unset_recursive_key($pedidoItens, ['valor'], 2);

            foreach($pedidoItens as $k => $v) {
                $pedidoItens[$k]['updated_at'] = 'now()';
            }

            $pedido = Pedido::find($id);
            $pedido->id_usuario_update = auth()->user()->id;
            $pedido->id_pedido_status = 1;
            $pedido->valor = $total;
            $pedido->save();

            $pedido->Produtos()->sync($pedidoItens);

            return redirect('/historico')->withSuccess('Pedido Nro: '.str_pad($id, 8, '0', STR_PAD_LEFT).' alterado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
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
    }
}
