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
            Route::prefix('assets')->group(function () {
                Route::get('assets/lend/{id}', ['uses' => 'AssetController@lend'])->name('assets.lend');
                Route::post('assets/lend-store/{id}', ['uses' => 'AssetController@lendSubmit'])->name('assets.lend-store');

                Route::resource('assets', 'AssetController');

                Route::get('/asset/{asset}/history/return/{history}', ['uses' => 'AssetHistoryController@returnAsset'])->name('assets.return');
                Route::resource('/asset/{asset}/history', 'AssetHistoryController')->names([
                    'create' => 'history.create',
                    'store' => 'history.store',
                    'edit' => 'history.edit',
                    'update' => 'history.update',
                    'destroy' => 'history.destroy',
                ]);

                Route::post('asset-type/store', ['as' => 'asset-type.store', 'uses' => 'AssetTypeController@store']);
                Route::get('asset-type/create', ['as' => 'asset-type.create', 'uses' => 'AssetTypeController@create']);
                Route::delete('asset-type/destroy/{id}', ['as' => 'asset-type.destroy', 'uses' => 'AssetTypeController@destroy']);

            });

            Route::group(
                ['prefix' => 'settings'],
                function () {

                }
            );
        }
    );
});
