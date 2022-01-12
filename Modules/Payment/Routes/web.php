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
		

    Route::group(['namespace' => '\Modules\Payment\Http\Controllers', 'prefix' => 'admin', 'middleware' => ['auth', 'can:view_backend']], function () {
       
       
       
        /*
     *
     *  Payment Routes
     *
     * ---------------------------------------------------------------------
     */
            $module_name = 'payment';
            $controller_name = 'PaymentController';
            Route::get("$module_name/", $controller_name.'@index' )->name('payment.index');
 
            Route::get($module_name.'/gettable', $controller_name.'@gettable')->name('payment.gettable');
            Route::get($module_name.'/linegettable/{id?}', $controller_name.'@linegettable')->name('payment.linegettable');
            Route::get("$module_name/create", $controller_name.'@create')->name('payment.create');
            Route::get("$module_name/store", $controller_name.'@store')->name('payment.store');
            Route::get("$module_name/update", $controller_name.'@update')->name('payment.update');
            Route::get("$module_name/getRoomForReservation", $controller_name.'@getRoomForReservation')->name('payment.getRoomForReservation');
            Route::get("$module_name/getRoomBedsForReservation", $controller_name.'@getRoomBedsForReservation')->name('payment.getRoomBedsForReservation');
			
			Route::get("$module_name/show/{id?}", $controller_name.'@show')->name('payment.show');
			Route::get("$module_name/print/{id?}", $controller_name.'@print')->name('payment.print');
			
            Route::get("$module_name/destroy/{id?}", $controller_name.'@destroy')->name('payment.destroy');
            Route::get("$module_name/edit/{id?}", $controller_name.'@edit')->name('payment.edit');
       
        /*
     *
     *  Payment Lines Routes
     *
     * ---------------------------------------------------------------------
     */
			$module_name = 'report';
			$controller_name = 'ReportController';
			Route::get("$module_name/profitloss", $controller_name.'@profitloss' )->name('report.profitloss');
			Route::post("$module_name/profitloss", $controller_name.'@profitloss' )->name('report.profitloss');
			
			Route::get("$module_name/expenses", $controller_name.'@expenses' )->name('report.expenses');
			Route::post("$module_name/expenses", $controller_name.'@expenses' )->name('report.expenses');
	});
});