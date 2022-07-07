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


Route::group(['middleware' => 'auth'], function () {

    // Admin routes
    Route::group(
        ['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['role:admin']],
        function () {

            Route::group(
                ['prefix' => 'settings'],
                function () {
                    Route::resource('sms', 'SmsSettingsController');
                }
            );
        }
    );

    // Super Admin routes
    Route::group(
        ['prefix' => 'super-admin', 'as' => 'super-admin.', 'middleware' => ['super-admin']],
        function () {

            Route::group(
                ['prefix' => 'settings'],
                function () {
                    Route::get('sms/sendTestMessage', ['uses' => 'SuperAdminSmsSettingsController@sendTestMessage'])->name('sms.sendTestMessage');
                    Route::resource('sms', 'SuperAdminSmsSettingsController');
                }
            );
        }
    );

});
