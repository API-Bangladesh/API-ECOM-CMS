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
    Route::get('customorder', 'CustomOrderController@index')->name('inventory');
    Route::group(['prefix' => 'customorder', 'as'=>'customorder.'], function () {
        Route::post('datatable-data', 'CustomOrderController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'CustomOrderController@store_or_update_data')->name('store.or.update');
        Route::post('view', 'CustomOrderController@view')->name('view');
        Route::post('edit', 'CustomOrderController@edit')->name('edit');
        Route::post('delete', 'CustomOrderController@delete')->name('delete');
        Route::post('bulk-delete', 'CustomOrderController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'CustomOrderController@change_status')->name('change.order_status');
        Route::post('change-payment-status', 'CustomOrderController@change_payment_status')->name('change.status');
        Route::get('customer-address', 'CustomOrderController@customer_address')->name('customer_address');
        Route::get('district-by-division', 'CustomOrderController@district_by_division')->name('district_by_division');
        Route::get('upazila-by-district', 'CustomOrderController@upazila_by_district')->name('upazila_by_district');
        Route::post('save-customer', 'CustomOrderController@save_customer')->name('save_customer');
        Route::post('save-page', 'CustomOrderController@save_page')->name('save_page');
        Route::get('get-pages', 'CustomOrderController@get_pages')->name('get_pages');
    });
});


    Route::get('ordermessage', 'CustomOrderMessageController@index');
    Route::group(['prefix'=>'ordermessage','as'=>'ordermessage.'],function(){
        Route::post('load-customer', 'CustomOrderMessageController@load_customer')->name('load_customer');
        Route::post('datatable-data', 'CustomOrderMessageController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'CustomOrderMessageController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'CustomOrderMessageController@edit')->name('edit');
        Route::post('delete', 'CustomOrderMessageController@delete')->name('delete');
        Route::post('bulk-delete', 'CustomOrderMessageController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'CustomOrderMessageController@change_status')->name('change.status');
        Route::post('message-store-or-update', 'CustomOrderMessageController@message_store_or_update_data')->name('messagestore.or.update');
        Route::post('message-order-edit', 'CustomOrderMessageController@message_order_edit')->name('message_order_edit');
    });

