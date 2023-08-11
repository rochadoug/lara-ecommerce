<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produto;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request  $request)
    {
        $produtos = Produto::
          select('tbl_produto.id', 'tbl_produto.nome', 'tbl_produto.valor', 'tbl_produto.imagem', 'tbl_produto.quantidade')
          ->where('ativo', true)->orderByRaw('(tbl_produto.quantidade > 0) DESC');

        if ($request->input('search')) {
            $produtos = $produtos->where('nome', 'like', '%'. $request->input('search'). '%');
        }

        if ($request->input('orderby')) {
          switch($request->input('orderby')) {
            case 'latest':
              $produtos->orderBy('created_at', 'DESC');
              break;
            case 'best-selling':
              $produtos
                ->leftJoin('tbl_pedido_item', 'tbl_produto.id', '=', 'tbl_pedido_item.id_produto')
                ->groupBy('tbl_produto.id');
              $produtos->orderBy(DB::raw('sum(coalesce(tbl_pedido_item.quantidade, 0))'), 'DESC');
              $produtos->orderBy(DB::raw('tbl_produto.created_at'), 'ASC');
              break;
            case 'price-asc':
              $produtos->orderBy('valor', 'ASC');
              break;
            case 'price-desc':
              $produtos->orderBy('valor', 'DESC');
              break;
            case 'name-asc':
              $produtos->orderBy('nome', 'ASC');
              break;
            case 'name-desc':
              $produtos->orderBy('nome', 'DESC');
              break;
          }

        } else {
            $produtos->orderBy('created_at', 'desc');
        }

        $produtos = $produtos->get();
        return view('index', array('produtos' => $produtos, 'orderby' => $request->input('orderby')));
    }
}
