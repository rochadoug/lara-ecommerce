<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pedido;
use Illuminate\Support\Facades\DB;

class GraficoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->except('_token', '_method')) {
            $date = explode(' - ', $request->input('filtroData'));
        } else {
            $date = array(date('Y-m-d', strtotime('-7 day')), date('Y-m-d'));
        }

        //dd( $date );
        $pedidos = Pedido::selectRaw(' created_at::date, count(id), sum(valor)')/* with('Produtos', 'PedidoStatus')-> */
                                ->groupBy(DB::raw('created_at::date'))
                                ->whereDate('created_at', '>=', $date[0])
                                ->whereDate('created_at', '<=', $date[1]);

        $pedAberto    = (clone $pedidos)->where('id_pedido_status', 1)->get()->toArray();
        $pedPago      = (clone $pedidos)->where('id_pedido_status', 2)->get()->toArray();
        $pedCancelado  (clone $pedidos)->where('id_pedido_status', 3)->get()->toArray();

        $params = array(
            'pedAberto'     => $pedAberto,
            'pedPago'       => $pedPago,
            'pedCancelado'  => $pedCancelado
        );
        //dd ($pedAberto);

        // Cria um Array com todas as Datas das consultas e ordena
        $dateArray = [];
        foreach ($params as $grafico) {
            foreach ($grafico as $idx => $linha) {
                array_push($dateArray, ['date' => $linha['created_at'], 'dateLabel' => date('d/m/Y', strtotime($linha['created_at']))]);
            }
        }
        usort($dateArray, function ($a, $b) {
                $t1 = strtotime($a['date']);
                $t2 = strtotime($b['date']);
                return $t1 - $t2;
            }
        );
        //dd( $dateArray );

        foreach ($params as $status => &$grafico) {
            $grafOriginal = $grafico;
            foreach($dateArray as $dateKey => $dateValue) {
                foreach ($grafOriginal as $idx => $linha) {
                    if ($dateValue['date'] == $linha['created_at']) {
                        $grafico['count'][$dateKey] = $linha['count'];
                        $grafico['sum'][$dateKey]   = $linha['sum'];
                        break;
                    } else {
                        $grafico['count'][$dateKey] = 0;
                        $grafico['sum'][$dateKey]   = 0;
                    }
                }
                unset($grafico[$idx]);
            }
            if (!$grafico) {
                foreach ($dateArray as $value) {
                    $grafico['count'][] = 0;
                    $grafico['sum'][] = 0;
                }
            }
            $grafico = json_encode($grafico, true);
        }
        $params['filtroData'] = (implode('/', array_reverse(explode('-', $date[0]))).' - '.implode('/', array_reverse(explode('-', $date[1]))));
        $params['arrayLabels'] = json_encode(array_map(function($val) { return $val['dateLabel']; }, $dateArray), true);
        //dd($params);

        return view('admin/graficos', $params);
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
