<?php
use Illuminate\Support\Facades\Hash;
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

Route::get('/',function(){
	return redirect('login');
	//return view('auth.login');
});




Route::get('password', function () {
    $pass = Hash::make('admin');
    return $pass;
});

Auth::routes();


Route::group(['prefix'=>'app'],function(){
	Route::get('/', 'HomeController@index');
    Route::get('nuevo','ClienteController@nuevo');
	Route::resource('clientes','ClienteController');
	Route::get('generar_facturas','MedicionController@generar_factura');
    Route::resource('medicion','MedicionController');
    Route::resource('pago','PagoController');
    Route::get('credito/lista/{id}','CreditoController@lista');
    Route::resource('credito', 'CreditoController');
    Route::resource('punto','PuntoAguaController');
    Route::get('medidor/estado/{id}','MedidorControlle@estado');
    Route::resource('medidor','MedidorController');

    //
    Route::resource('reportes','ReporteController');
    Route::resource('usuario','UserController');
    //
    Route::get('lectura/eliminar/{id}','MedicionController@listar_lecturas');
    Route::get('lectura/eliminar/confirm/{id}','MedicionController@destroy');
    //
    Route::get('editar/credito/{id}/{valor}','CreditoController@actualizar');
    Route::get('eliminar/credito/{id}','CreditoController@destroy');

    Route::get('update','AcuerdoController@index');
});


Route::get('update','AppController@update');

