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

Route::prefix('restapi')->group(function () {
    Route::get('/', 'RestAPIController@index');
});
Route::group(['middleware' => 'auth'], function () {

    // For Application setting
    // Admin routes
    Route::group(
        ['prefix' => 'super-admin', 'as' => 'super-admin.', 'middleware' => ['super-admin']],
        function () {
            Route::group(
                ['prefix' => 'settings'],
                function () {
                    Route::get(
                        'rest-api-setting/test-push/{platform}',
                        ['uses' => 'RestAPISettingController@testPush']
                    )->name('rest-api.test-push');
                    Route::post(
                        'rest-api-setting/send-push',
                        ['uses' => 'RestAPISettingController@sendPush']
                    )->name('rest-api.send-push');
                    Route::resource('rest-api-setting', 'RestAPISettingController', ['only' => ['index', 'update']]);
                /*                    Route::get('regenerate-secret/{id}',
                        ['uses' => 'ApplicationSettingController@regenerateSecret'])->name('application-setting.regenerate-secret');
                    Route::resource('application-setting', 'ApplicationSettingController'); */
                }
            );
        }
    );
});
