@extends('admin.layouts.app')

@section('content')

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-start pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h4 mb-3">Clientes</h1>
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


        <div class="card" {!! (getRequestAction()!='index' || !!old() ? 'style="display: none"': '') !!} id="card-listagem">
            <h6 class="card-header bg-transparent text-muted border-0">
                Listagem de Clientes
                <a href="#" class="btn btn-sm text-info float-right border-info rounded-circle" data-hide="#card-listagem, .btn-toolbar" data-show="#card-cadastro"><i class="fa fa-plus"></i> </a>
            </h6>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped table-borded table-sm">
                      <thead>
                        <tr>
                          <th width="10">#</th>
                          <th width="12%">@sortablelink('id', 'Código')</th>
                          <th width="*%">@sortablelink('nome', 'Nome')</th>
                          <th width="15%">@sortablelink('cpf', 'CPF')</th>
                          <th width="15%">@sortablelink('telefone', 'Telefone')</th>
                          <th width="25%">@sortablelink('endereco', 'Endereço')</th>
                          <th width="10%">@sortablelink('numero', 'Número')</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($listRecords as $key => $cliente)
                        <tr style="cursor: pointer;">
                          <td><a href="{{ url('/') }}/admin/clientes/{{ $cliente->id }}"><i class="fa fa-edit"></i></a></td>
                          <td>{{ str_pad($cliente->id, 6, '0', STR_PAD_LEFT) }}</td>
                          <td class="text-truncate" title="{{ $cliente->nome }}">{{ $cliente->nome }}</td>
                          <td class="text-truncate" title="{{ $cliente->cpf }}">{{ $cliente->cpf }}</td>
                          <td class="text-truncate" title="{{ $cliente->nome }}">{{ $cliente->telefone }}</td>
                          <td class="text-truncate" title="{{ $cliente->endereco }}">{{ $cliente->endereco }}</td>
                          <td>{{ $cliente->numero }}</td>
                        </tr>
                        @endforeach
                        @if (count($listRecords)==0)
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


        <div class="card" {!! (getRequestAction()!='show' && !old() ? 'style="display: none"': '') !!} id="card-cadastro">
            <form name="updCart" id="updCart" action="{{ Request::url() }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h6 class="card-header bg-transparent text-muted border-0">Cadastro de Clientes</h3>
                <div class="card-body">
                    <div class="card-title border-bottom mb-4 ">
                        <h6><span class="text-primary">Cliente Nº.:</span> {{ str_pad($record->id, 8, '0', STR_PAD_LEFT) }}</h6>
                    </div>
                    <div class="form-row align-items-center">
                        <div class="form-group col-12 col-sm-6 col-md-4">
                            <label>Nome</label>
                            <input type="text" class="form-control" name="nome" value="{{ old('nome', ($record->nome ?? '' )) }}"  max="100" autocomplete="off">
                        </div>
                        <div class="form-group col-12 col-sm-6 col-md-4">
                            <label>E-mail</label>
                            <input type="text" class="form-control" name="email" value="{{ old('email', ($record->User->email ?? '' )) }}" max="255" autocomplete="off">
                        </div>
                        <div class="form-group col-12 col-sm-6 col-md-4">
                            <label>CPF</label>
                            <input type="text" class="form-control cpf" name="cpf" value="{{ old('cpf', ($record->cpf ?? '' )) }}" max="14" autocomplete="off">
                        </div>
                        <div class="form-group col-12 col-sm-6 col-md-4">
                            <label>Telefone</label>
                            <input type="text" class="form-control telefone" name="telefone" value="{{ old('telefone', ($record->telefone ?? '' )) }}" max="15" autocomplete="off">
                        </div>
                        <div class="form-group col-12 col-sm-6 col-md-4">
                            <label>CEP</label>
                            <input type="text" class="form-control" name="cep" value="{{ old('cep', ($record->cep ?? '' )) }}" data-mask="00000000" max="7" autocomplete="off">
                        </div>
                        <div class="form-group col-12 col-sm-6 col-md-4">
                            <label>Cidade</label>
                            <input type="text" class="form-control" name="cidade" value="{{ old('cidade', ($record->cidade ?? '' )) }}" max="60" autocomplete="off">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Endereço</label>
                            <input type="text" class="form-control" name="endereco" value="{{ old('endereco', ($record->endereco ?? '' )) }}" max="100" autocomplete="off">
                        </div>
                        <div class="form-group col-12 col-sm-6 col-md-3">
                            <label>Número</label>
                            <input type="text" class="form-control" name="numero" value="{{ old('numero', ($record->numero ?? '' )) }}" data-mask="0000000000" max="10" autocomplete="off">
                        </div>
                        <div class="form-group col-12 col-sm-6 col-md-3">
                            <label>Complemento</label>
                            <input type="text" class="form-control" name="complemento" value="{{ old('complemento', ($record->complemento ?? '' )) }}" max="10" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="form-row justify-content-center">
                        <button class="btn btn-success mx-2 mb-1" id="btn_save" {!! (getRequestAction()=='show' ? 'data-btn-type="put"': '') !!}><i class="fa fa-edit"></i> Salvar</button>
                        @if (getRequestAction()=='show')
                        {{-- <!-- <button class="btn btn-danger mx-2 mb-1" id="btn_delete" data-btn-type="delete"><i class="fa fa-trash-alt"></i> Excluir</button> --> --}}
                        @endif
                        <a class="btn btn-light border mx-2 mb-1" href="{{ url('/').'/'.Request::segment(1).'/'.Request::segment(2) }}"><i class="fa fa-undo"></i> Cancelar</a>
                    </div>
                </div>
            </form>
        </div>


    {{-- @endif --}}


@endsection

@section('custom_js');
    <!-- <script src="{{ asset('js/admin/clientes.js') }}"></script> -->
@endsection

