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

        Route::group( //start LOCALIZED Routes 
            [
                'prefix' => LaravelLocalization::setLocale(),
                'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function(){ 
                

            Route::group(['namespace' => '\Modules\Item\Http\Controllers', 'prefix' => 'admin', 'middleware' => ['auth', 'can:view_backend']], function () {
            
        $module_name = 'item';
                    
        Route::get("$module_name/", 'ItemController@index' )->name('item.index');;

        Route::get($module_name .'/gettable', 'ItemController@gettable')->name('item.gettable');
        Route::get("$module_name/create", 'ItemController@create')->name('item.create');
        Route::get("$module_name/store", 'ItemController@store')->name('item.store');
        Route::get("$module_name/update", 'ItemController@update')->name('item.update');

        Route::get("$module_name/destroy/{id?}", 'ItemController@destroy')->name('item.destroy');
        Route::get("$module_name/edit/{id?}", 'ItemController@edit')->name('item.edit');
     

        $module_name = 'roomItems';
                    
        Route::get("$module_name/index/{id?}", 'RoomItemsController@index' )->name('roomItems.index');;
        Route::get($module_name .'/gettable/{id?}', 'RoomItemsController@gettable')->name('roomItems.gettable');
        Route::get("$module_name/create", 'RoomItemsController@create')->name('roomItems.create');
        Route::get("$module_name/store", 'RoomItemsController@store')->name('roomItems.store');
        Route::get("$module_name/update", 'RoomItemsController@update')->name('roomItems.update');
        

        Route::get("$module_name/destroy/{id?}", 'RoomItemsController@destroy')->name('roomItems.destroy');
        Route::get("$module_name/edit/{id?}", 'RoomItemsController@edit')->name('roomItems.edit');
        Route::get("$module_name/getAvailableItems/{id?}", 'RoomItemsController@getAvailableItems')->name('roomItems.getAvailableItems');



        });});
