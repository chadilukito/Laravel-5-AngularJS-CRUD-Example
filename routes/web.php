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
    return view('app');
});

Route::get('books/export/csv/{choice}', 'BooklistController@export_csv');
Route::get('books/export/xml/{choice}', 'BooklistController@export_xml');

Route::group(['middleware' => ['web']], function () {
    Route::resource('books', 'BooklistController');
});

Route::group(array('prefix'=>'/templates/'), function() {
    Route::get('{template}', array( function($template)
    {
        $template = str_replace(".html", "", $template);
        View::addExtension('html', 'php');
        return View::make('templates.'.$template);
    }));
});