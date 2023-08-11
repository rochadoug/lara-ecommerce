@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Gráfico Pedidos por Tipo</h1>
        <div class="btn-toolbar mb-2 mb-md-0 w-25">
            <div class="form-group w-100">
                <form id="filterDate" action="{{ url('/') }}/admin/grafico" method="POST">
                    @csrf
                    <input type="text" class="form-control dtrangepicker" name="filtroData" value="{{ old('filtroData', ($filtroData ?? '')) }}" autocomplete="off">
                </form>
            </div>
        </div>
    </div>

    <input type="hidden" id="Aberto" value='{!! $pedAberto !!}'>
    <input type="hidden" id="Pago" value='{!! $pedPago !!}'>
    <input type="hidden" id="Cancelado" value='{!! $pedCancelado !!}'>

    <div class="d-flex flex-column justify-content-between flex-wrap flex-md-nowrap align-items-start py-2">
        <h1 class="h5 w-100 pb-2 border-bottom ">Gráfico Valores</h1>
        <canvas class="my-2 w-100 d-none" id="chartValores" width="900" height="200"></canvas>
    </div>

    <div class="d-flex flex-column justify-content-between flex-wrap flex-md-nowrap align-items-start py-2">
        <h1 class="h5 w-100 pb-2 border-bottom ">Gráfico Quantidades </h1>
        <canvas class="my-2 w-100 d-none" id="chartQuantidades" width="900" height="200"></canvas>
    </div>

@endsection

@section('custom_js')
    <script src="{{ asset('js/admin/dashboard.js') }}"></script>
@endsection
