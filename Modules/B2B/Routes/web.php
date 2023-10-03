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
    Route::get('b2b', 'B2BController@index')->name('b2b');
    Route::group(['prefix' => 'b2b', 'as'=>'b2b.'], function () {
        Route::post('datatable-data', 'B2BController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'B2BController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'B2BController@edit')->name('edit');
        Route::post('view', 'B2BController@view')->name('view');
        Route::post('delete', 'B2BController@delete')->name('delete');
        Route::post('bulk-delete', 'B2BController@bulk_delete')->name('bulk.delete');
        Route::post('change-payment-status', 'B2BController@change_payment_status')->name('change.status');
        Route::post('update-status', 'B2BController@update_status')->name('change.update_status');
        Route::post('get-product-price', 'B2BController@get_price')->name('change.product_price');
    });
});
