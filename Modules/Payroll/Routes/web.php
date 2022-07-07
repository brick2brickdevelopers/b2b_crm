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
            Route::prefix('payroll')->group(function () {
                Route::post('payroll/generate', ['uses' => 'PayrollController@generatePaySlip'])->name('payroll.generatePaySlip');
                Route::post('payroll/updateStatus', ['uses' => 'PayrollController@updateStatus'])->name('payroll.updateStatus');
                Route::get('payroll/data', ['uses' => 'PayrollController@data'])->name('payroll.data');
                Route::get('payroll/download/{id}', ['uses' => 'PayrollController@downloadPdf'])->name('payroll.downloadPdf');
                Route::post('payroll/cycle-data', ['uses' => 'PayrollController@getCycleData'])->name('payroll.get-cycle-data');
                Route::resource('payroll', 'PayrollController');

                Route::get('employee-salary/data', ['uses' => 'EmployeeMonthlySalaryController@data'])->name('employee-salary.data');
                Route::post('employee-salary/payroll-cycle', ['uses' => 'EmployeeMonthlySalaryController@employeePayrollCycle'])->name('employee-salary.payroll-cycle');
                Route::post('employee-salary/payroll-status', ['uses' => 'EmployeeMonthlySalaryController@employeePayrollStatus'])->name('employee-salary.payroll-status');
                Route::resource('employee-salary', 'EmployeeMonthlySalaryController');
            });

            Route::group(
                ['prefix' => 'settings'],
                function () {
                    Route::resource('salary-groups', 'SalaryGroupController');
                    
                    Route::post('salary-tds/status', ['uses' => 'SalaryTdsController@status'])->name('salary-tds.status');
                    Route::resource('salary-tds', 'SalaryTdsController');
                    
                    Route::get('salary-components/data', ['uses' => 'SalaryComponentController@data'])->name('salary-components.data');
                    Route::resource('salary-components', 'SalaryComponentController');
                    
                    Route::resource('payment-methods', 'SalaryPaymentMethodController');
                    
                    Route::post('employee-salary-groups/data', ['uses' => 'EmployeeSalaryGroupController@data'])->name('employee-salary-groups.data');
                    Route::resource('employee-salary-groups', 'EmployeeSalaryGroupController');
                    Route::resource('payroll-setting', 'PayrollSettingController');
                }
            );
        }
    );

    // Employee routes
    Route::group(
        ['prefix' => 'member', 'as' => 'member.', 'middleware' => ['role:employee']],
        function () {
            Route::prefix('payroll')->group(function () {
                Route::get('payroll/data', ['uses' => 'MemberPayrollController@data'])->name('payroll.data');
                Route::get('payroll/download/{id}', ['uses' => 'MemberPayrollController@downloadPdf'])->name('payroll.downloadPdf');
                Route::resource('payroll', 'MemberPayrollController');

            });

        }
    );
});
