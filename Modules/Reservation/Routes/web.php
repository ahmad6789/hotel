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


    Route::group(['namespace' => '\Modules\Reservation\Http\Controllers', 'prefix' => 'admin', 'middleware' => ['auth', 'can:view_backend']], function () {



        /*
     *
     *  reservation Routes
     *
     * ---------------------------------------------------------------------
     */
            $module_name = 'reservation';
            $controller_name = 'ReservationController';
            Route::get("$module_name/", $controller_name.'@index' )->name('reservation.index');
            Route::get("$module_name/showrooms", $controller_name.'@indexrooms' )->name('reservation.indexrooms');

            Route::get($module_name.'/gettable', $controller_name.'@gettable')->name('reservation.gettable');
            Route::get($module_name.'/showroomsgettable', $controller_name.'@showroomsgettable')->name('reservation.showroomsgettable');
            Route::get("$module_name/create/{id?}", $controller_name.'@create')->name('reservation.create');
            Route::get("$module_name/store", $controller_name.'@store')->name('reservation.store');
            Route::get("$module_name/update", $controller_name.'@update')->name('reservation.update');
            Route::get("$module_name/checkout/{id?}", $controller_name.'@checkout')->name('reservation.checkout');
            Route::get("$module_name/getRoomCountStatus", $controller_name.'@getRoomCountStatus')->name('reservation.getRoomCountStatus');
            Route::get("$module_name/getRoomForReservation", $controller_name.'@getRoomForReservation')->name('reservation.getRoomForReservation');
            Route::get("$module_name/getRoomBedsForReservation", $controller_name.'@getRoomBedsForReservation')->name('reservation.getRoomBedsForReservation');

            Route::get("$module_name/destroy/{id?}", $controller_name.'@destroy')->name('reservation.destroy');
            Route::get("$module_name/edit/{id?}", $controller_name.'@edit')->name('reservation.edit');

        /*
     *
     *  Reservation Routes
     *
     * ---------------------------------------------------------------------
     */
			$module_name = 'customer';
			$controller_name = 'CustomerController';
			Route::get("$module_name/", $controller_name.'@index' )->name('customer.index');;

			Route::get($module_name.'/gettable', $controller_name.'@gettable')->name('customer.gettable');
			Route::get("$module_name/create", $controller_name.'@create')->name('customer.create');
			Route::get("$module_name/store", $controller_name.'@store')->name('customer.store');
			Route::get("$module_name/update", $controller_name.'@update')->name('customer.update');

			Route::get("$module_name/destroy/{id?}", $controller_name.'@destroy')->name('customer.destroy');
            Route::get("$module_name/edit/{id?}", $controller_name.'@edit')->name('customer.edit');
            Route::get("$module_name/blacklist/{id?}", $controller_name.'@blackList')->name('customer.blackList');
	});
});
