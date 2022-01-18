<?php

/*
*
* Frontend Routes
*
* --------------------------------------------------------------------
*/





Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {





    // Same thing for static pages like your about page
    Route::view(trans('routes.about'), 'about')->name('page.about');

    // Route::get('/', 'FrontendController@index')->name('index');

    Route::get('/', function () {
        return redirect('/admin');
    });

    Route::get('home', 'FrontendController@index')->name('home');
    Route::get('privacy', 'FrontendController@privacy')->name('privacy');
    Route::get('terms', 'FrontendController@terms')->name('terms');

    Route::group(['middleware' => ['auth']], function () {

        /*
        *
        *  Users Routes
        *
        * ---------------------------------------------------------------------
        */

        $module_name = 'users';
        $controller_name = 'UserController';
        Route::get("profile/{id}", ['as' => "$module_name.profile", 'uses' => "$controller_name@profile"]);
        Route::get('profile/{id}/edit', ['as' => "$module_name.profileEdit", 'uses' => "$controller_name@profileEdit"]);
        Route::patch('profile/{id}/edit', ['as' => "$module_name.profileUpdate", 'uses' => "$controller_name@profileUpdate"]);
        Route::get("$module_name/emailConfirmationResend/{id}", ['as' => "$module_name.emailConfirmationResend", 'uses' => "$controller_name@emailConfirmationResend"]);
        Route::get("profile/changePassword/{username}", ['as' => "$module_name.changePassword", 'uses' => "$controller_name@changePassword"]);
        Route::patch("profile/changePassword/{username}", ['as' => "$module_name.changePasswordUpdate", 'uses' => "$controller_name@changePasswordUpdate"]);
        Route::delete('users/userProviderDestroy', ['as' => 'users.userProviderDestroy', 'uses' => 'UserController@userProviderDestroy']);

        //labrere

        Route::get($module_name.'/index', ['as' => "$module_name.index", 'uses' => "$controller_name@labrereindex"]);
        Route::get($module_name .'/gettable', ['as' => "$module_name.gettable", 'uses' => "$controller_name@gettable"]);
        Route::get($module_name .'/createlabrere', ['as' => "$module_name.createlabrere", 'uses' => "$controller_name@createlabrere"]);
        Route::post($module_name .'/storelabrere', ['as' => "$module_name.storelabrere", 'uses' => "$controller_name@storelabrere"]);
        Route::get($module_name .'/editlabrere/{id?}', ['as' => "$module_name.editlabrere", 'uses' => "$controller_name@editlabrere"]);
        Route::get($module_name .'/deletelabrere/{id?}', ['as' => "$module_name.deletelabrere", 'uses' => "$controller_name@deletelabrere"]);
        Route::post($module_name .'/updatelabrere/{id?}', ['as' => "$module_name.updatelabrere", 'uses' => "$controller_name@updatelabrere"]);


        Route::get($module_name .'/RewardOrPunishment/{type}', ['as' => "$module_name.RewardOrPunishment", 'uses' => "$controller_name@RewardOrPunishment"]);
        Route::get($module_name .'/showReward', ['as' => "$module_name.showReward", 'uses' => "$controller_name@showReward"]);
        Route::get($module_name .'/showPunishment', ['as' => "$module_name.showPunishment", 'uses' => "$controller_name@showPunishment"]);






        Route::get($module_name .'/createPunishment', ['as' => "$module_name.createPunishment", 'uses' => "$controller_name@createPunishment"]);
        Route::get($module_name .'/createReward', ['as' => "$module_name.createReward", 'uses' => "$controller_name@createReward"]);

        Route::post($module_name .'/storePunishment', ['as' => "$module_name.storePunishment", 'uses' => "$controller_name@storePunishment"]);
        Route::post($module_name .'/storeReward', ['as' => "$module_name.storeReward", 'uses' => "$controller_name@storeReward"]);

        Route::get($module_name .'/RewardorPunishmentDelete/{id?}', ['as' => "$module_name.RewardorPunishmentDelete", 'uses' => "$controller_name@RewardorPunishmentDelete"]);




    });
});
