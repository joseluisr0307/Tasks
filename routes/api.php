<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login','Api\PassportController@login');
Route::post('register','Api\PassportController@register');
Route::get('documentos','Api\DocumentoController@index');
Route::get('archivos/{tarea}','Api\ArchivoController@index');

Route::middleware('auth:api')->post('details','Api\PassportController@getDetails');

Route::group( ['prefix' => '/v1', 'namespace' => 'Api', 'as' => 'api.', 'middleware' => 'auth:api'], function () {
    Route::resource('tasks', 'TareaController', ['except' => ['create', 'edit']]);
    Route::get('tasks/pagination/{pagina}', 'TareaController@pagination');
    Route::post('tasks/uploadfiles','ArchivoController@store');
    Route::delete('tasks/deletefiles/{archivo}','ArchivoController@destroy');     
});