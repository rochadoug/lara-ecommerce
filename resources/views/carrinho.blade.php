@extends('layouts.app')

@section('custom_css')
    <link href="{{ asset('css/carrinho.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-header card-header-master border-0">
            <h4 class="mb-0">Meu Carrinho</h4>
        </div>
    </div>

    @include('includes.alerts')

    @if($cart)
        <div class="row mt-4">
            <div class="col-12 col-sm-8">
                <form name="updCart" id="updCart" action="" method="POST" >
                    @csrf
                    <div class="table-responsive bg-white">
                        <table id="cart" class="table table-hover table-condensed">
                            <thead>
                                <tr>
                                    <th style="width:*%">Produto</th>
                                    <th style="width:14%">Preço</th>
                                    <th style="width:8%">Quantitade</th>
                                    <th style="width:90px; min-width: 90px;"></th>
                                    <th style="width:20%" class="text-center">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($cart as $item)
                                <tr class="border-bottom">
                                    <td data-th="Product">
                                        <div class="row">
                                            <div class="col-3 hidden-xs"><img src="{{ Storage::url($item['imagem']) }}" alt="..." width="40" class="img-fluid rounded"/></div>
                                            <div class="col-9"><h6 class="m-1">{{ str_limit($item['nome'], 50) }}</h6></div>
                                        </div>
                                    </td>
                                    <td data-th="Price">R$ {{ number_format($item['valor'], 2, ',', '.') }}</td>
                                    <td data-th="Quantity">
                                        <input name="{{ $item['id'] }}" type="number" class="form-control text-center" value="{{ $item['quantidade'] }}" autocomplete="off">
                                    </td>
                                    <td class="actions" data-th="">
                                        <a href="javascript: void(0);" class="btn btn-info   btn-sm mx-1" id="btn_save"   data-btn-type="put" data-add-action-id="{{ $item['id'] }}"><i class="fas fa-sync"></i></button>
                                        <a href="javascript: void(0);" class="btn btn-danger btn-sm mx-1" id="btn_delete" data-btn-type="delete" data-confirmation='false' data-add-action-id="{{ $item['id'] }}"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                    <td data-th="Subtotal" class="text-center">R$ {{ number_format(($item['valor']*$item['quantidade']), 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td><a href="{{ url('/') }}" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continuar comprando</a></td>
                                    <td colspan="3" class="hidden-xs"></td>
                                    <td class="hidden-xs text-center"><strong>Total R$ {{ number_format($total, 2, ',', '.') }}</strong></td>
                                    <!-- <td><a href="{{ url('/') }}/finishOrder" class="btn btn-success btn-block">Finalizar Pedido <i class="fa fa-angle-right"></i></a></td> -->
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </form>
            </div>
            <div class="col-12 col-sm-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h3 class="my-0 fpmt-weight-normal">Resumo do pedido</h3>
                    </div>
                    <div class="card-body">
                        <div lass="card-text">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Subtotal ({{ count($cart) }} produtos)<br/>R$ {{ number_format($total, 2, ',', '.') }}
                                <li class="list-group-item">Total<br/>R$ {{ number_format($total, 2, ',', '.') }}</li>
                            </ul>
                            <td><a href="{{ url('/') }}/finishOrder" class="btn btn-success btn-block">Finalizar Pedido <i class="fa fa-angle-right"></i></a></td>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card-deck b-3 mt-4" style="flex-flow: row">
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0 font-weight-normal">Carrinho Vazio</h4>
                </div>
                <div class="card-body bg-light">
                    <div class="card-text text-danger">
                        Você ainda não selecionou nenhum produto! Volte e peça alguma coisa!&emsp;
                        <a href="{{ url('/') }}" class="btn btn-primary"><i class="fas fa-undo fa-spin-reverse"></i>&ensp;Voltar</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('custom_js')
    <script src="{{ asset('js/carrinho.js') }}"></script>
@endsection
