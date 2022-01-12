<?php
use Modules\Item\Http\Controllers;
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


    Route::group(['namespace' => '\Modules\Room\Http\Controllers', 'prefix' => 'admin', 'middleware' => ['auth', 'can:view_backend']], function () {



        /*
     *
     *  Room Routes
     *
     * ---------------------------------------------------------------------
     */
            $module_name = 'rooms';
            $controller_name = 'RoomController';
            Route::get("$module_name/", 'RoomController@index' )->name('room.index');;
            //
            Route::get("$module_name/get", 'RoomController@getNotify' )->name('room.getNotify');;

            //
            Route::get($module_name.'/gettable', 'RoomController@gettable')->name('room.gettable');
            Route::get("$module_name/create", 'RoomController@create')->name('room.create');
            Route::get("$module_name/store", 'RoomController@store')->name('room.store');
            Route::get("$module_name/update", 'RoomController@update')->name('room.update');

            Route::get("$module_name/destroy/{id?}", 'RoomController@destroy')->name('room.destroy');
            Route::get("$module_name/edit/{id?}", 'RoomController@edit')->name('room.edit');





        /*
     *
     *  bed Routes
     *
     * ---------------------------------------------------------------------
     */
                $module_name = 'rooms/beds';

                Route::get("$module_name/", 'RoomBedsController@index' )->name('bed.index');;

                Route::get($module_name.'/gettable', 'RoomBedsController@gettable')->name('bed.gettable');
                Route::get("$module_name/create", 'RoomBedsController@create')->name('bed.create');
                Route::get("$module_name/store", 'RoomBedsController@store')->name('bed.store');
                Route::get("$module_name/update", 'RoomBedsController@update')->name('bed.update');

                Route::get("$module_name/destroy/{id?}", 'RoomBedsController@destroy')->name('bed.destroy');
                Route::get("$module_name/edit/{id?}", 'RoomBedsController@edit')->name('bed.edit');
      /*
     *
     *  RoomCategory Routes
     *
     * ---------------------------------------------------------------------
            */
            $module_name = 'rooms/RoomCategory';

            Route::get("$module_name/", 'RoomCategoriesController@index' )->name('RoomCategory.index');;

            Route::get($module_name.'/gettable', 'RoomCategoriesController@gettable')->name('RoomCategory.gettable');
            Route::get("$module_name/create", 'RoomCategoriesController@create')->name('RoomCategory.create');
            Route::get("$module_name/store", 'RoomCategoriesController@store')->name('RoomCategory.store');
            Route::get("$module_name/update", 'RoomCategoriesController@update')->name('RoomCategory.update');

            Route::get("$module_name/destroy/{id?}", 'RoomCategoriesController@destroy')->name('RoomCategory.destroy');
            Route::get("$module_name/edit/{id?}", 'RoomCategoriesController@edit')->name('RoomCategory.edit');




});});
