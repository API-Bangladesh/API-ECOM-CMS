<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['auth']], function () {
    Route::get('contact', 'ContactController@index')->name('contact');
    Route::group(['prefix' => 'contact', 'as'=>'contact.'], function () {
        Route::post('datatable-data', 'ContactController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'ContactController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'ContactController@edit')->name('edit');
        Route::post('delete', 'ContactController@delete')->name('delete');
        Route::post('bulk-delete', 'ContactController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'ContactController@change_status')->name('change.status');
    });
});

