<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => true]);

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('migrate', function() {
    Artisan::call('migrate:fresh');
    Artisan::call('db:seed --force');
    echo 'Migrate conclúido!';
});

// User Default Routes
Route::middleware(['userDefault'])->group(function () {

    // Index/Home Routes
    Route::get('/', 'HomeController@index')->name('index');
    Route::get('/home', 'HomeController@index')->name('home');

    // Carrinho Allow Guest Routes
    Route::get('carrinho', 'CarrinhoCompraController@showCart');
    Route::get('carrinho/{productId}', 'CarrinhoCompraController@addCart');            //Route::get('cart/add/{productId}', 'CarrinhoCompraController@addCart');
    Route::put('carrinho/{productId}', 'CarrinhoCompraController@updateCart');         //Route::post('cart/upd', 'CarrinhoCompraController@updateCart');
    Route::delete('carrinho/{productId}', 'CarrinhoCompraController@deleteCart');      //Route::post('cart/del/{productId}', 'CarrinhoCompraController@deleteCart');

    // User Default Authenticated Routes
    Route::middleware(['auth', 'verified'])->group(function () {

        // Carrinho Only Auth Routes
        Route::get('finishOrder', 'CarrinhoCompraController@finishOrder');

        // Historico Only Auth Routes
        Route::get('historico', 'HistoricoPedidosController@index');
        Route::post('historico', 'HistoricoPedidosController@index');
        Route::get('historico/{id}', 'HistoricoPedidosController@show');
        Route::put('historico/{id}', 'HistoricoPedidosController@update');
        //Route::delete('historico/{id}', 'HistoricoPedidosController@destroy');
        //------------------------------------------------
        //Route::get('historico/{startDt}/{endDt}', 'HistoricoPedidosController@index');
        //Route::resource('historico', 'HistoricoPedidosController');
    });
});

// Admin Only Authenticated Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    Route::view('/', 'admin/index')->name('admin');

    Route::get('grafico', 'Admin\GraficoController@index');
    Route::post('grafico', 'Admin\GraficoController@index');

    Route::get('clientes', 'Admin\ClientesController@index');
    // Route::patch('clientes', 'Admin\ClientesController@index'); // Consulta Data
    Route::get('clientes/{id}', 'Admin\ClientesController@show');
    Route::post('clientes', 'Admin\ClientesController@store');
    Route::put('clientes/{id}', 'Admin\ClientesController@update');
    Route::delete('clientes/{id}', 'Admin\ClientesController@destroy');

    Route::get('produtos', 'Admin\ProdutosController@index');
    // Route::patch('produtos', 'Admin\ProdutosController@index'); // Consulta Data
    Route::get('produtos/{id}', 'Admin\ProdutosController@show');
    Route::post('produtos', 'Admin\ProdutosController@store');
    Route::put('produtos/{id}', 'Admin\ProdutosController@update');
    Route::delete('produtos/{id}', 'Admin\ProdutosController@destroy');

    Route::get('pedidos', 'Admin\PedidosController@index');
    // Route::patch('pedidos', 'Admin\PedidosController@index'); // Consulta Data
    Route::post('pedidos', 'Admin\PedidosController@store');
    Route::get('pedidos/{id}', 'Admin\PedidosController@show');
    Route::put('pedidos/{id}', 'Admin\PedidosController@update');
    Route::delete('pedidos/{id}', 'Admin\PedidosController@destroy');

    Route::get('previewnotification/UserWelcomePasswordNotification', function () {
        $message = (new \App\Notifications\UserWelcomePasswordNotification('TeStPaSsWoRd'))->toMail('test@email.com');
        $markdown = new \Illuminate\Mail\Markdown(view(), config('mail.markdown'));
        return $markdown->render('vendor.notifications.welcome_password_email', $message->data());
    });

    Route::prefix('ajax')->group(function() {
        Route::get('clientes', 'Admin\ClientesController@ajaxSearch');
        Route::get('produtos', 'Admin\ProdutosController@ajaxSearch');
    });
});

