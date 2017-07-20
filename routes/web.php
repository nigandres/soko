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

use App\Http\Middleware\VerificaEdad;
use App\Http\Middleware\Jerarquia;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
// Route::resource('/user','UserController');
// Route::resource('/dato','DatoController');
//esto se crea un middleware que restringe a ciertos ususarios en algunos enlaces
Route::group(['middleware' => VerificaEdad::class], function ()
	{
		Route::resource('/dato','DatoController');
	});
Route::group(['middleware' => Jerarquia::class], function ()
	{
		Route::resource('/user','UserController');
	});
// Route::get('/config/{id}/excel','DatoController@exportExcel');
// Route::get('/download/{tabla}','ExcelController@exportar');
// Route::get('/upload','ExcelController@impotar');
Route::group(['prefix' => 'importar-archivos-momo'/*, 'middleware' => ['auth']*/], function()
{
    Route::post('/excel/validar','ExcelController@validar');
    Route::get('/excel/importar','ExcelController@importarExcel');
    Route::get('/excel/exportar','ExcelController@exportar');
});

Route::group(['prefix' => 'personas-momo'/*, 'middleware' => ['auth']*/], function()
{
    Route::resource('/importadas','PersonaController');
});
Route::group(['prefix' => 'archivo'/*, 'middleware' => ['auth']*/], function()
{
    Route::get('/formulario','StorageController@index');
    Route::post('/guardar','StorageController@save');
});

