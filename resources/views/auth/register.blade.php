@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ __('Registrar') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-row">
                            <div class="form-group col-sm-6">
                                <label for="name" class="col-form-label">{{ __('Nome') }}</label>
                                <input id="name" type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group col-sm-6">
                                <label for="email" class="col-form-label">{{ __('E-mail') }}</label>
                                <input id="email" type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-sm-6">
                                <label for="password" class="col-form-label">{{ __('Senha') }}</label>
                                <input id="password" type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group col-sm-6">
                                <label for="password-confirm" class="col-form-label">{{ __('Confirma Senha') }}</label>
                                 <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-sm-6">
                                <label for="cpf" class="col-form-label">{{ __('CPF') }}</label>
                                <input id="cpf" type="text" class="form-control cpf {{ $errors->has('cpf') ? 'is-invalid' : '' }}" name="cpf" value="{{ old('cpf') }}" maxlength="11" required>
                                @if ($errors->has('cpf'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('cpf') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group col-sm-6">
                                <label for="telefone" class="col-form-label">{{ __('Telefone') }}</label>
                                <input id="telefone" type="text" class="form-control telefone {{ $errors->has('telefone') ? 'is-invalid' : '' }}" name="telefone" value="{{ old('telefone') }}" maxlength="11" required>
                                @if ($errors->has('telefone'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('telefone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-sm-6">
                                <label for="cep" class="col-form-label">{{ __('CEP') }}</label>
                                <input id="cep" type="text" class="form-control {{ $errors->has('cep') ? 'is-invalid' : '' }}" name="cep" value="{{ old('cep') }}" maxlength="8" required>
                                @if ($errors->has('cep'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('cep') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group col-sm-6">
                                <label for="endereco" class="col-form-label">{{ __('Endereço') }}</label>
                                <input id="endereco" type="text" class="form-control {{ $errors->has('endereco') ? 'is-invalid' : '' }}" name="endereco" value="{{ old('endereco') }}" maxlength="100" required>
                                @if ($errors->has('endereco'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('endereco') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-sm-6">
                                <label for="numero" class="col-form-label">{{ __('Número') }}</label>
                                <input id="numero" type="text" class="form-control {{ $errors->has('numero') ? 'is-invalid' : '' }}" name="numero" value="{{ old('numero') }}" maxlength="10" required>
                                @if ($errors->has('numero'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('numero') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group col-sm-6">
                                <label for="complemento" class="col-form-label">{{ __('Complemento') }}</label>
                                <input id="complemento" type="text" class="form-control {{ $errors->has('complemento') ? 'is-invalid' : '' }}" name="complemento" value="{{ old('complemento') }}" maxlength="10">
                                @if ($errors->has('complemento'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('complemento') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col">
                                <label for="cidade" class="col-form-label">{{ __('Cidade') }}</label>
                                <input id="cidade" type="text" class="form-control {{ $errors->has('cidade') ? 'is-invalid' : '' }}" name="cidade" value="{{ old('cidade') }}" maxlength="60" required>
                                @if ($errors->has('cidade'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('cidade') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0 justify-content-center">
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Registrar') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
    <script src="{{ asset('js/registro-usuario.js') }}"></script>
@endsection

