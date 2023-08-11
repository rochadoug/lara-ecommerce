@extends('admin.layouts.app')

@section('content')

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-start pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h4 mb-3">Produtos</h1>
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
                              <option value="10" {{ old('ftrPage', ($ftrPage ?? 20)) == 10 ? 'selected' : '' }} >10</option>
                              <option value="20" {{ old('ftrPage', ($ftrPage ?? 20)) == 20 ? 'selected' : '' }} >20</option>
                              <option value="30" {{ old('ftrPage', ($ftrPage ?? 20)) == 30 ? 'selected' : '' }} >30</option>
                              <option value="40" {{ old('ftrPage', ($ftrPage ?? 20)) == 40 ? 'selected' : '' }} >40</option>
                              <option value="50" {{ old('ftrPage', ($ftrPage ?? 20)) == 50 ? 'selected' : '' }} >50</option>
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

    {{-- dd($listRecords) --}}

        <div class="card" {!! (getRequestAction()!='index' ? 'style="display: none"': '') !!} id="card-listagem">
            <h6 class="card-header bg-transparent text-muted border-0">
                Listagem de Produtos
                <a href="#" class="btn btn-sm text-info float-right border-info rounded-circle" data-hide="#card-listagem, .btn-toolbar" data-show="#card-cadastro"><i class="fa fa-plus"></i> </a>
            </h6>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped table-borded table-sm">
                      <thead>
                        <tr>
                          <th width="30">#</th>
                          <th width="10%"> @sortablelink('id', 'Código') </th>
                          <th width="20%">@sortablelink('nome', 'Nome')</th>
                          <th width="*%">@sortablelink('descricao', 'Descrição')</th>
                          <th width="10%">@sortablelink('valor', 'Valor (R$)')</th>
                          <th width="10%">@sortablelink('ativo', 'Ativo')</th>
                          <th width="15%">@sortablelink('created_at', 'Cadastrado')</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($listRecords as $key => $produto)
                        <tr id="heading_{{ $key }}" data-toggle="collapse" data-target="#collapse_{{ $key }}" aria-expanded="false" aria-controls="collapse_{{ $key }}" style="cursor: pointer;">
                          <td><a href="{{ url('/') }}/admin/produtos/{{ $produto->id }}"><i class="fa fa-edit"></i></a></td>
                          <td>{{ str_pad($produto->id, 8, '0', STR_PAD_LEFT) }}</td>
                          <td class="text-truncate" title="{{ $produto->nome }}">{{ $produto->nome }}</td>
                          <td class="text-truncate" title="{{ $produto->descricao }}">{{ $produto->descricao }},</td>
                          <td align="right">{{ number_format($produto->valor, 2, ',', '.') }}</td>
                          <td>{{ $produto->ativo? 'Sim':'Não' }}</td>
                          <td>{{ date('d/m/Y', strtotime($produto->created_at)) }}</td>
                        </tr>
                        @endforeach
                        @if ($listRecords->count()==0)
                        {{-- @if (count($listRecords)==0) --}}
                        <tr>
                          <td colspan="7" align="center">Nenhum registro encontrado!</td>
                        </tr>
                        @endif
                      </tbody>
                    </table>
                    {{ $links }}
                </div>
            </div>
        </div>


    {{-- @elseif (getRequestAction() == 'show') --}}


        <div class="card" {!! (getRequestAction()!='show' ? 'style="display: none"': '') !!} id="card-cadastro">
            <form name="updCart" id="updCart" action="{{ Request::url() }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h6 class="card-header bg-transparent text-muted border-0">Cadastro de Produtos</h3>
                <div class="card-body">
                    <div class="card-title border-bottom mb-4 ">
                        <h6><span class="text-primary">Produto Nº.:</span> {{ str_pad($record->id, 8, '0', STR_PAD_LEFT) }}</h6>
                    </div>
                    <div class="form-row align-items-center">
                        <div class="form-group col-12 col-sm-4">
                            <label>Nome</label>
                            <input type="text" class="form-control" name="nome" value="{{ old('nome', ($record->nome ?? '')) }}" autocomplete="off">
                        </div>
                        <div class="form-group col-12 col-sm-8">
                            <label>Descrição</label>
                            <input type="text" class="form-control" name="descricao" value="{{ old('descricao', ($record->descricao ?? '')) }}" autocomplete="off">
                        </div>
                        <div class="form-group col-12 col-sm-4">
                            <label>Valor (R$)</label>
                            <input type="text" class="form-control text-right money" name="valor" value="{{ number_format( old('valor', ($record->valor ?? 0)), 2, ',', '.') }}" autocomplete="off">
                        </div>
                        <div class="form-group col-12 col-sm-8">
                            <label>Imagem</label>
                            <div>
                                @if ($record->imagem != '')
                                    <div class="row" id="exibeImagem">
                                        <div class="col-12 col-sm-auto pr-1">
                                            <img src="{{ Storage::url($record->imagem) }}" width="45" height="45">
                                        </div>
                                        <div class="col-12 col-sm px-1 mt-1">
                                            <input type="text" class="form-control readonly" readonly value="{{ basename($record->imagem) }}">
                                        </div>
                                        <div class="col-12 col-sm-auto pl-1 mt-1">
                                            <button class="btn btn-warning m-0" id="alterarImg" preventEvents data-show="#carregaImagem" data-hide="#exibeImagem">Alterar Imagem</button>
                                        </div>
                                    </div>
                                @endif
                                <div id="carregaImagem" class="w-100" {!! ($record->imagem != '' ? 'style="display: none"' : '') !!}>
                                    <input type="file" class="form-control form-control-file w-100" name="imagem" value="{{ old('imagem', '') }}" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="ativo" id="ativo" {{ (old('ativo', ($record->ativo ?? false)) ? 'checked' : '') }} autocomplete="off">
                            <label class="form-check-label" for="ativo">Ativo</label>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
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
    <!-- <script src="{{ asset('js/admin/produtos.js') }}"></script> -->
@endsection

