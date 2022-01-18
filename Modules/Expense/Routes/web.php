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
			'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
		], function(){

	Route::group(['namespace' => '\Modules\Expense\Http\Controllers', 'prefix' => 'admin', 'middleware' => ['auth', 'can:view_backend']], function () {

		$module_name = 'expense';
		Route::get("$module_name/create", 'ExpenseController@create')->name('expense.create');


		Route::get($module_name .'/gettable', 'ExpenseController@gettable')->name('expense.gettable');
		Route::get($module_name .'/gettable/{type?}', 'ExpenseController@gettable')->name('expense.gettable');
		Route::get($module_name .'/getUsersList', 'ExpenseController@getUsersList')->name('expense.getUsersList');
		Route::get($module_name .'/getRoomsList', 'ExpenseController@getRoomsList')->name('expense.getRoomsList');
		Route::get($module_name .'/getItemsList', 'ExpenseController@getItemsList')->name('expense.getItemsList');

		Route::get("$module_name/store", 'ExpenseController@store')->name('expense.store');
		Route::get("$module_name/update", 'ExpenseController@update')->name('expense.update');

		Route::get("$module_name/destroy/{id?}", 'ExpenseController@destroy')->name('expense.destroy');
		Route::get("$module_name/edit/{id?}", 'ExpenseController@edit')->name('expense.edit');
		Route::get("$module_name/{type?}", 'ExpenseController@index' )->name('expense.index');

    });
});
