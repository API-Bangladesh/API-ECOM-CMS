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
    Route::get('paymentmethod', 'PaymentMethodController@index')->name('combocategory');
    Route::group(['prefix' => 'paymentmethod', 'as'=>'paymentmethod.'], function () {
        Route::post('datatable-data', 'PaymentMethodController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'PaymentMethodController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'PaymentMethodController@edit')->name('edit');
        Route::post('delete', 'PaymentMethodController@delete')->name('delete');
        Route::post('bulk-delete', 'PaymentMethodController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'PaymentMethodController@change_status')->name('change.status');
    });
});
