<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produto;
use App\Pedido;
use Session;

class CarrinhoCompraController extends Controller
{
    //
    public function __construct() {
        //$this->middleware('auth');
    }

    public function showCart(Request $request)
    {
        $cart = Session::get('cart');

        if (!$cart)
            $cart = [];

        $total = 0;
        foreach($cart as $item) {
            $total += $item['quantidade']*$item['valor'];
        }

        return view('carrinho', array('cart' => $cart, 'total' => $total));
    }

    public function addCart(Request $request, $id)
    {
        $product = Produto::find($id);

        $cart = Session::get('cart');

        if (isset($cart[$product->id]))
            return redirect('/carrinho');

        $cart[$product->id] = array(
            'id'    => $product->id,
            'nome'          => $product->nome,
            'valor'         => $product->valor,
            'imagem'        => $product->imagem,
            'quantidade'    => 1,
        );

        Session::put('cart', $cart);
        //Session::flash('success', 'Produto adicionado ao seu carrinho!');

        return redirect('/carrinho');
    }

    public function updateCart(Request $cartData, $id)
    {
        try {
            $cart = Session::get('cart');
            foreach ($cartData->except('_token') as $id => $val)
            {
                if ($val > 0) {
                    $cart[$id]['quantidade'] = $val;
                } else {
                    unset($cart[$id]);
                }
            }
            Session::put('cart', $cart);
            return redirect()->back()->withSuccess('Alterações feitas com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function deleteCart($id)
    {
        try {
            $cart = Session::get('cart');
            unset($cart[$id]);
            Session::put('cart', $cart);
            return redirect()->back()->withSuccess('Item removido com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function finishOrder()
    {
        //$this->middleware('auth');
        if (!auth()->check() )
            return redirect('/login');

        $cart = Session::get('cart');

        if (!$cart)
            return view('carrinho', array('cart' => [], 'total' => 0))->withErrors('Ocorreu um erro ao Finalizar Pedido: "Nenhum item no seu carrinho!"');

        try {
            $total = 0;
            foreach($cart as $item) {
                $total += $item['quantidade']*$item['valor'];
            }

            // Test for array 1 dimension
            //$cart = ['id'=>12390, 'teste' => 'alalal', 'nome' => 'oi', 'valor' => 956.45 ];
            //array_unset_recursive_key($cart, ['id', 'nome', 'imagem']);
            //dd( $cart );

            // Altera o Array Cart para os campos da tabela Pivot
            array_unset_recursive_key($cart, ['id', 'nome', 'imagem'], 2);

            foreach($cart as $k => $v) {
                $cart[$k]['created_at'] = 'now()';
                $cart[$k]['updated_at'] = 'now()';
            }

            $pedido = new Pedido();
            $pedido->id_cliente = auth()->user()->Cliente->id;
            $pedido->valor = $total;
            $numero = Pedido::latest()->first();
            $pedido->numero = ($numero ? $numero->numero : 0)+1;
            $pedido->save();

            $pedido->Produtos()->attach($cart);

            Session::forget('cart');

            return redirect('/historico');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
