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

Route::get('/', function () {
    return view('index');
});


Route::resource('/todos' , 'TodoController');
Route::put('/todos/makedone/{id}' , 'TodoController@makeDone');
Route::put('/todos/makeundone/{id}' , 'TodoController@makeUnDone');
Route::post('todos/updateorder' , 'TodoController@UpdateOrder');