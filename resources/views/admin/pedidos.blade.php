@extends('admin.layouts.app')

@section('content')

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-end pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h4 mb-3">Pedidos</h1>
        @if (getRequestAction() == 'index' && !old())
            <div lass="btn-toolbar mb-2 mb-md-0 w-50">
                <div class="form-group w-100 m-0">
                    <form id="filterDate" action="{{ Request::url() }}" method="GET">
                        {{-- @csrf
                        @method('patch') --}}
                        @foreach(request()->except('ftrDate', 'ftrPage') as $key => $value)
                          <input type="hidden" name="{{$key}}" value="{{$value}}"/>
                        @endforeach
                        <div class="row" style="min-width: 400px">
                          <div class="col-12 col-sm-4">
                            <select class="form-control page_selector" name="ftrPage" autocomplete="off">
                              <option value="10" {{ old('ftrPage', ($ftrPage ?? null)) == 10 ? 'selected' : '' }} >10</option>
                              <option value="20" {{ old('ftrPage', ($ftrPage ?? null)) == 20 ? 'selected' : '' }} >20</option>
                              <option value="30" {{ old('ftrPage', ($ftrPage ?? null)) == 30 ? 'selected' : '' }} >30</option>
                              <option value="40" {{ old('ftrPage', ($ftrPage ?? null)) == 40 ? 'selected' : '' }} >40</option>
                              <option value="50" {{ old('ftrPage', ($ftrPage ?? null)) == 50 ? 'selected' : '' }} >50</option>
                            </select>
                          </div>
                          <div class="col-12 col-sm-8">
                            <input type="text" class="form-control dtrangepicker" name="ftrDate" value="{{ old('ftrDate', ($ftrDate ?? '')) }}" placeholder="Filtro Data" autocomplete="off">
                          </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    @include('includes.alerts')

    {{-- @if (getRequestAction() == 'index') --}}


        <div class="card" {!! (getRequestAction()!='index' ? 'style="display: none"': '') !!} id="card-listagem">
            <h6 class="card-header bg-transparent text-muted border-0">
                Listagem de Pedidos
                <a href="#" class="btn btn-sm text-info float-right border-info rounded-circle" data-hide="#card-listagem, .btn-toolbar" data-show="#card-cadastro"><i class="fa fa-plus"></i> </a>
            </h6>
            <div class="card-body">
                <div class="accordion" id="accordionPedidos">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped table-borded table-sm">
                          <thead>
                            <tr>
                              <th width="10">#</th>
                              <th width="10">#</th>
                              <th width="15%">@sortablelink('numero', 'Pedido Nº')</th>
                              <th width="*%"> @sortablelink('Cliente.nome', 'Cliente')</th>
                              <th width="15%">@sortablelink('created_at', 'Data Cad.')</th>
                              <th width="15%">@sortablelink('valor', 'Valor (R$)')</th>
                              <th width="15%">@sortablelink('PedidoStatus.descricao', 'Status')</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($listRecords as $key => $pedido)
                            <tr id="heading_{{ $key }}" data-toggle="collapse" data-target="#collapse_{{ $key }}" aria-expanded="false" aria-controls="collapse_{{ $key }}" style="cursor: pointer;">
                              <td><a href="{{ url('/') }}/admin/pedidos/{{ $pedido->id }}"><i class="fa fa-edit"></i></a></td>
                              <td><a href="{{ url('/') }}/admin/clientes/{{ $pedido->id_cliente }}"><i class="fa fa-user"></i></a></td>
                              <td>{{ str_pad($pedido->id, 8, '0', STR_PAD_LEFT) }}</td>
                              <td class="text-truncate">{{ $pedido->Cliente->nome }}</td>
                              <td>{{ date('d/m/Y', strtotime($pedido->created_at)) }}</td>
                              <td align="right">{{ number_format($pedido->valor, 2, ',', '.') }}</td>
                              <td>{{ $pedido->PedidoStatus->descricao }}</td>
                            </tr>
                            <tr><!-- Nothing --></tr>
                            <tr id="collapse_{{ $key }}" class="collapse bg-secondary" aria-labelledby="heading_{{ $key }}" data-parent="#accordionPedidos">
                                <td colspan="7">
                                    <table class="table table-striped table-bordered table-hover table-dark table-sm m-0">
                                        <thead>
                                            <tr class="font-weight-bold">
                                                <td width="*%">Produto</td>
                                                <td width="15%">Valor Un. (R$)</td>
                                                <td width="15%">Quantidade</td>
                                                <td width="15%">SubTotal (R$)</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pedido->Produtos as $produto)
                                            <tr class="">
                                                <td>{{ $produto->nome }}</td>
                                                <td align="right">{{ number_format($produto->PedidoItem->valor, 2, ',', '.') }}</td>
                                                <td>{{ $produto->PedidoItem->quantidade }}</td>
                                                <td align="right">{{ number_format(($produto->PedidoItem->valor*$produto->PedidoItem->quantidade), 2, ',', '.') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            @endforeach
                            @if (count($listRecords)==0)
                            <tr>
                              <td colspan="6" align="center">Nenhum registro encontrado!</td>
                            </tr>
                            @endif
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    {{-- @elseif (getRequestAction() == 'show') --}}


        <div class="card" {!! (getRequestAction()!='show' ? 'style="display: none"': '') !!} id="card-cadastro">
            <form name="updCart" id="updCart" action="{{ Request::url() }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h6 class="card-header bg-transparent text-muted border-0">Cadastro de Pedidos</h6>
                <div class="card-body">
                    <div class="card-title border-bottom mb-4 ">
                        <div class="row">
                            <div class="col-3"><h6 class="m-0"><span class="text-primary">Pedido Nº.:</span> {{ str_pad($record->id, 8, '0', STR_PAD_LEFT) }}</h6></div>
                            <div class="col-3"><h6 class="m-0"><span class="text-primary">Data:</span> {{ date('d/m/Y H:i', strtotime($record->created_at ?? date('Y-m-d H:i'))) }}</h6></div>
                            <div class="col-3"><h6 class="m-0"><span class="text-primary">Total:</span> R$ <span id="totalValor">{{ number_format($record->valor, 2, ',', '.') }}</span></h6></div>
                            <div class="col-3"><h6 class="m-0"><span class="text-primary">Status:</span> {{ $record->PedidoStatus->descricao ?? '' }}</h6></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12 col-sm-6">
                            <label>Cliente</label>
                            <input type="hidden" class="form-control" name="cliente" value="{{ $record->id_cliente }}" autocomplete="off">
                            <input type="text" class="form-control" id="clienteNome" value="{{ $record->Cliente->nome ?? '' }}" @if (getRequestAction()=='show') disabled @endif autocomplete="off" preventEvents >
                        </div>
                        <div class="form-group col-12 col-sm-6">
                            <label>Adicionar Produto</label>
                            <input type="text" class="form-control" id="produtoNome" value="" autocomplete="off" preventEvents>
                        </div>
                    </div>
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
                            @foreach($record->Produtos as $item)
                                <tr class="border-bottom">
                                    <td data-th="Product">
                                        <div class="row">
                                            <div class="col-auto hidden-xs"><img src="{{ Storage::url($item->imagem) }}" alt="..." width="40" class="img-fluid rounded"/></div>
                                            <div class="col"><h6 class="m-1">{{ $item->nome }}</h6></div>
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
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-transparent">
                    @if (getRequestAction()=='show')
                    <div class="col alert text-sm text-danger text-center mb-0 p-1">
                        Excluir o Pedido apenas altera seu Status para cancelado!
                    </div>
                    @endif
                    <div class="form-row justify-content-center">
                        <button class="btn btn-success mx-2 mb-1" id="btn_save" {!! (getRequestAction()=='show' ? 'data-btn-type="put"': '') !!}><i class="fa fa-edit"></i> Salvar</button>
                        @if (getRequestAction()=='show')
                        <button class="btn btn-danger mx-2 mb-1" id="btn_delete" data-btn-type="delete"><i class="fa fa-trash-alt"></i> Excluir</button>
                        @endif
                        <a class="btn btn-light border mx-2 mb-1" href="{{ url('/').'/'.Request::segment(1).'/'.Request::segment(2) }}"><i class="fa fa-undo"></i> Cancelar</a>
                    </div>
                </div>
            </form>
        </div>


    {{-- @endif --}}


@endsection

@section('custom_js');
    <script src="{{ asset('js/admin/pedidos.js') }}"></script>
@endsection

