@extends('layouts.app')

@section('content')

    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-header card-header-master border-0">
            <h4 class="mb-0">{{ ($action=='historico' ? 'Histórico de Pedidos' : 'Alteração de Pedido') }}</h4>
        </div>
    </div>

    @include('includes.alerts')

    @if ($action == 'historico')

        <form name="searchPedidos" id="searchPedidos" action="{{ url('/').'/historico' }}" method="POST">
            @csrf
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-header border-b pb-2">
                    <h6 class="mb-0">Pesquisa por Data</h6>
                </div>
                <div class="card-body py-2">
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label>Data Inicial</label>
                                <input type="text" name="startDt" class="form-control calendar" value="{{ old('startDt', ($startDt ?? '')) }}" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label>Data Final</label>
                                <input type="text" name="endDt" class="form-control calendar" value="{{ old('endDt', ($endDt ?? '')) }}" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button class="btn btn-info form-control">Pesquisar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        @if (count($pedidos)>0)
        <div class="accordion" id="accordionPedidos">
            @foreach($pedidos as $key => $pedido)

                <div class="card" @if(count($pedidos)==1) style="border-bottom: 1px solid rgba(0, 0, 0, 0.125)" @endif>
                    <div class="card-header" id="heading_{{ $key }}" style="cursor: pointer;">
                        <div class="row" data-toggle="collapse" data-target="#collapse_{{ $key }}" aria-expanded="{{ ($key==0 ? 'true' : 'false') }}" aria-controls="collapse_{{ $key }}">
                            <div class="col-3"><h6 class="m-0"><span class="text-primary">Pedido Nº.:</span> {{ str_pad($pedido->numero, 8, '0', STR_PAD_LEFT) }}</h6></div>
                            <div class="col-3"><h6 class="m-0"><span class="text-primary">Data:</span> {{ date('d/m/Y H:i:s', strtotime($pedido->created_at)) }}</h6></div>
                            <div class="col-3"><h6 class="m-0"><span class="text-primary">Total:</span> R$ {{ number_format($pedido->valor, 2, ',', '.') }}</h6></div>
                            <div class="col-3"><h6 class="m-0"><span class="text-primary">Status:</span> {{ $pedido->PedidoStatus->descricao }}</h6></div>
                        </div>
                    </div>
                    <div id="collapse_{{ $key }}" class="collapse {{ ($key==0 ? 'show' : '') }}" aria-labelledby="heading_{{ $key }}" data-parent="#accordionPedidos">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="cart" class="table table-hover table-condensed m-0">
                                    <thead>
                                        <tr>
                                            <td style="width:*%">Produto</td>
                                            <td style="width:10%">Preço</td>
                                            <td style="width:8%">Quantitade</td>
                                            <td style="width:20%" class="text-center">Subtotal</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($pedido->Produtos as $item)
                                        <tr class="border-bottom">
                                            <td data-th="Product">
                                                <div class="row">
                                                    <div class="col-3 hidden-xs"><img src="{{ Storage::url($item->imagem) }}" alt="..." width="40" class="img-fluid rounded"/></div>
                                                    <div class="col-9"><h6 class="m-1">{{ $item->nome }}</h6></div>
                                                </div>
                                            </td>
                                            <td data-th="Price">R$ {{ number_format($item->PedidoItem->valor, 2, ',', '.') }}</td>
                                            <td data-th="Quantity">{{ $item->PedidoItem->quantidade }}</td>
                                            <td data-th="Subtotal" class="text-center">R$ {{ number_format(($item->PedidoItem->valor*$item->PedidoItem->quantidade), 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    @if (in_array($pedido->PedidoStatus->id, [1, 2, 7]))
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="hidden-xs">
                                                @if ($pedido->PedidoStatus->id == 7)
                                                <span class="text-danger text-small">
                                                    <small>
                                                    O pedido está como "ALTERANDO", caso passe 10 minutos sem completar a alteração, será cancelado!<br/>Tempo passado:
                                                    </small>
                                                    <small class="elapsedTime">{{ $pedido->elapsedTime }}</small>
                                                </span>
                                                @else
                                                <span class="text-danger">
                                                    <small>
                                                    Após começar a Alteração, você tem 10 minutos para finalizar.<br/>
                                                    O pedido, caso passe 10 minutos sem completar a alteração, será cancelado!
                                                    </small>
                                                </span>
                                                @endif
                                            </td>
                                             <td><a href="{{ url('/').'/historico/'.$pedido->id }}" class="btn btn-info"><i class="fa fa-edit"></i> Alterar Pedido </a></td>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <div class="card">
            <div class="card-body">
                <div class="card-text text-center">
                    Você ainda não realizou nenhum pedido!
                </div>
            </div>
        </div>
        @endif
    @else
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-3"><h6 class="m-0"><span class="text-primary">Pedido Nº.:</span> {{ str_pad($pedido->id, 8, '0', STR_PAD_LEFT) }}</h6></div>
                    <div class="col-3"><h6 class="m-0"><span class="text-primary">Data:</span> {{ date('d/m/Y H:i:s', strtotime($pedido->created_at)) }}</h6></div>
                    <div class="col-3"><h6 class="m-0"><span class="text-primary">Total:</span> R$ <span id="totalValor">{{ number_format($pedido->valor, 2, ',', '.') }}</span></h6></div>
                    <div class="col-3"><h6 class="m-0"><span class="text-primary">Status:</span> {{ $pedido->PedidoStatus->descricao }}</h6></div>
                </div>
            </div>
            <div class="card-body">
                <form name="updCart" id="updCart" action="{{ url('/').'/historico/'.$pedido->id }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table id="cart" class="table table-hover table-condensed m-0">
                            <thead>
                                <tr>
                                    <td style="width:*%">Produto</td>
                                    <td style="width:10%">Preço</td>
                                    <td style="width:8%">Quantitade</td>
                                    <td style="width:15%"></td>
                                    <td style="width:20%" class="text-center">Subtotal</td>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($pedido->Produtos as $item)
                                <tr class="border-bottom">
                                    <td data-th="Product">
                                        <div class="row">
                                            <div class="col-3 hidden-xs"><img src="{{ asset('img/'.$item->imagem)}}" alt="..." width="40" class="img-fluid rounded"/></div>
                                            <div class="col-9"><h6 class="m-1">{{ $item->nome }}</h6></div>
                                        </div>
                                    </td>
                                    <td data-th="Price">R$ {{ number_format($item->PedidoItem->valor, 2, ',', '.') }}</td>
                                    <td data-th="Quantity">
                                        <input name="{{ $item->id }}[quantidade]" type="number" class="form-control text-center" value="{{ $item->PedidoItem->quantidade }}" data-oldValue="{{ $item->PedidoItem->quantidade }}" autocomplete="off">
                                        <input name="{{ $item->id }}[valor]" type="hidden" value="{{ $item->PedidoItem->valor }}" autocomplete="off">
                                    </td>
                                    <td class="actions" data-th="">
                                        <a href="javascript: void(0);" data-product="{{ $item->id }}" class="btnRemove btn btn-danger btn-sm mx-1"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                    <td data-th="Subtotal" class="text-center">R$ <span class="subTotal">{{ number_format(($item->PedidoItem->valor*$item->PedidoItem->quantidade), 2, ',', '.') }}</span></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="hidden-xs">
                                        @if ($pedido->PedidoStatus->id == 7)
                                        <span class="text-danger text-small">
                                            <small>
                                            O pedido está como "ALTERANDO", caso passe 10 minutos sem completar a alteração, será cancelado!<br/>Tempo passado:
                                            </small>
                                            <small class="elapsedTime">{{ $pedido->elapsedTime }}</small>
                                        </span>
                                        @endif
                                    </td>
                                     <td><button class="btn btn-info" id="btn_save" data-btn-type="put"><i class="fa fa-edit"></i> Finalizar Alterarção </button></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    @endif
        <!-- </div>
    </div> -->
@endsection

@section('custom_js')
    <script src="{{ asset('js/historico.js') }}"></script>
@endsection
