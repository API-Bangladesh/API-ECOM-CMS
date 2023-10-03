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

Route::group(['middleware' => ['auth']], function () {
    Route::get('customers', 'CustomersController@index')->name('ccategory');
    Route::group(['prefix' => 'customers', 'as'=>'customers.'], function () {
        Route::post('datatable-data', 'CustomersController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'CustomersController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'CustomersController@edit')->name('edit');
        Route::post('view', 'CustomersController@view')->name('view');
        Route::post('delete', 'CustomersController@delete')->name('delete');
        Route::post('bulk-delete', 'CustomersController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'CustomersController@change_status')->name('change.status');
    });
});
