<?php
use Modules\Ticket\Http\Controllers;
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
		

    Route::group(['namespace' => '\Modules\Ticket\Http\Controllers', 'prefix' => 'admin', 'middleware' => ['auth', 'can:view_backend']], function () {
       
       
       
        /*
     *
     *  Room tickets
     *
     * ---------------------------------------------------------------------
     */ 
            $module_name = 'tickets';
            $controller_name = 'TicketController';
            Route::get("$module_name/", 'TicketController@index' )->name('ticket.index');;
 
            Route::get($module_name.'/gettable', 'TicketController@gettable')->name('ticket.gettable');
            Route::get("$module_name/create/{id?}", 'TicketController@create')->name('ticket.create');
            Route::get("$module_name/store", 'TicketController@store')->name('ticket.store');
            Route::get("$module_name/update", 'TicketController@update')->name('ticket.update');
            Route::get("$module_name/show", 'TicketController@show')->name('ticket.show');

            Route::get("$module_name/destroy/{id?}", 'TicketController@destroy')->name('ticket.destroy');
            Route::get("$module_name/edit/{id?}", 'TicketController@edit')->name('ticket.edit');
       
        /*
            *
            *  Room Routes
            *
            * ---------------------------------------------------------------------
            */
            $module_name = 'ticketsActivities';
            $controller_name = 'TicketActivityController';
            Route::get("$module_name/", 'TicketActivityController@index' )->name('ticketsActivities.index');;
            Route::get("$module_name/show/{id?}", 'TicketActivityController@showTicketLog')->name('ticketsActivities.show');

             Route::get("$module_name/create", 'TicketActivityController@create')->name('ticketsActivities.create');
            Route::get("$module_name/store", 'TicketActivityController@store')->name('ticketsActivities.store');
            Route::get("$module_name/update", 'TicketActivityController@update')->name('ticketsActivities.update');

            Route::get("$module_name/destroy/{id?}", 'TicketActivityController@destroy')->name('ticketsActivities.destroy');
            Route::get("$module_name/edit/{id?}", 'TicketActivityController@edit')->name('ticketsActivities.edit');


                

            
});});
