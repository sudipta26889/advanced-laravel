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


Route::prefix('/')->group(function () {
	Route::get('', 'Website\PageController@landingPage')->name('landing');
	// Route::get('register', function () { return redirect(''); }); // Disable register page to open
	Route::get('switch_website', 'Website\PageController@switchPage')->name('switch_website');
	Route::get('change_site/{entity_id}', 'Website\PageController@changeSite')->name('change_site');
	Route::get('change_lang/{lang_id}', 'Website\PageController@changeLanguage')->name('change_lang');
	Auth::routes();
	Route::get('logout', 'Auth\LoginController@logout')->name('logout' );
	Route::middleware(['auth'])->group(function () {
		Route::get('api', 'Website\PageController@apiPage')->name('api_page');
		Route::get('api/redirect/{client_id?}', 'Api\ApiAuthController@redirect')->name('api_redirect');
	    Route::get('api/callback', 'Api\ApiAuthController@callback')->name('api_callback');
	    Route::get('api/callback-received', 'Api\ApiAuthController@callbackReceived')->name('api_callback_received');
	    Route::get('api/refresh-token', 'Api\ApiAuthController@refresh')->name('api_refresh_token');
        Route::prefix('admin/')->middleware(['role:admin'])->group(function () {
            Route::get('dashboard', 'Admin\PageController@adminDashboardPage')->name('admin::dashboard');
            Route::get('edit-profile', 'Admin\PageController@adminEditProfilePage')->name('admin::edit_profile');
        });
	});
});