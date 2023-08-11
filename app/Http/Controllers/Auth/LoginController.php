<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    ///**
     //* Field name for login
     //*
     //* @return string
     //*/
    //protected function username()
    //{
        //return 'name';
    //}

    //protected function validateLogin(Request $request) {
        //$rules = array(
            //'name'       => 'required|alphaNum|min:4', // usuario only be alphanumeric and has to be greater than 3 characters
            //'password'   => 'required|alphaNum|min:4', // senha can only be alphanumeric and has to be greater than 3 characters
        //);

        //$messages = array(
            //'name.required'       => 'O campo Usuário deve ser preenchido!',
            //'name.min'            => 'O campo Usuário deve ter no mínimo 4 caracteres!',
            //'password.required'   => 'O campo Senha deve ser preenchido!',
            //'password.min'        => 'O campo Senha deve ter no mínimo 4 caracteres!',
        //);

        //$this->validate($request, $rules, $messages);
    //}

}
