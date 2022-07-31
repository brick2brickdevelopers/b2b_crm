<?php

use Modules\RestAPI\Http\Controllers\CampaignController;

ApiRoute::group(['namespace' => 'Modules\RestAPI\Http\Controllers'], function () {

    ApiRoute::get('app', ['as' => 'api.app', 'uses' => 'AppController@app']);

    // Forgot Password
    ApiRoute::post(
        'auth/forgot-password',
        ['as' => 'api.auth.forgotPassword', 'uses' => 'AuthController@forgotPassword']
    );
    // Auth routes
    ApiRoute::post('auth/login', ['as' => 'api.auth.login', 'uses' => 'AuthController@login']);
    ApiRoute::post('auth/logout', ['as' => 'api.auth.logout', 'uses' => 'AuthController@logout']);
    ApiRoute::post('auth/reset-password', ['as' => 'api.auth.resetPassword', 'uses' => 'AuthController@resetPassword']);
    ApiRoute::get('auth/refresh', ['as' => 'api.auth.refresh', 'uses' => 'AuthController@refresh']);
    // File view does not require Auth
    ApiRoute::get('/file/{name}', ['as' => 'file.show', 'uses' => 'FileController@download']);

    // We public file uploads, but only for certain types, which we will check in request
    ApiRoute::post('/file', ['as' => 'file.store', 'uses' => 'FileController@upload']);
    ApiRoute::get('/lang', ['as' => 'lang', 'uses' => 'LanguageController@lang']);
});

ApiRoute::group(['namespace' => 'Modules\RestAPI\Http\Controllers', 'middleware' => 'api.auth'], function () {

    // ApiRoute::get('dashboard', ['as' => 'api.dashboard', 'uses' => 'DashboardController@dashboard']);
    ApiRoute::get('dashboard/me', ['as' => 'api.dashboard', 'uses' => 'DashboardController@myDashboard']);
    ApiRoute::get('auth/me', ['as' => 'api.auth.me', 'uses' => 'AuthController@me']);
    ApiRoute::get('/project/me', ['as' => 'project.me', 'uses' => 'ProjectController@me']);

    ApiRoute::get('company', ['as' => 'api.app', 'uses' => 'CompanyController@company']);
    ApiRoute::post('/project/{project_id}/members', ['as' => 'project.member', 'uses' => 'ProjectController@members']);
    ApiRoute::delete(
        '/project/{project_id}/member/{id}',
        [
            'as' => 'project.member.delete',
            'uses' => 'ProjectController@memberRemove'
        ]
    );

 //Campaign api route tarting from here 
    ApiRoute::get('campaign', 'CampaignController@campign_list');
    ApiRoute::get('user_lead', 'CampaignController@user_lead');
    ApiRoute::post('update_lead_status', 'CampaignController@update_lead_status');
    ApiRoute::post('call_disposal', 'CampaignController@call_disposal');
    ApiRoute::get('call_purpose', 'CampaignController@call_purpose');
    ApiRoute::get('event_list', 'CampaignController@event_list');
    ApiRoute::get('call_log_reports', 'CampaignController@call_log_reports');
    
//ending the compaign route here
//Updated dashboard api route starting from here
    ApiRoute::get('dashboard','CampaignController@dashboard');
    ApiRoute::get('employee_dashboard','CampaignController@employee_dashboard');
    ApiRoute::get('test','CampaignController@test');



//updated dashboard api route ending here


    ApiRoute::resource('project', 'ProjectController');
    ApiRoute::resource('project-category', 'ProjectCategoryController');
    ApiRoute::resource('currency', 'CurrencyController');

    ApiRoute::get('/task/me', ['as' => 'task.me', 'uses' => 'TaskController@me']);
    ApiRoute::get('/task/remind/{id}', ['as' => 'task.remind', 'uses' => 'TaskController@remind']);

    ApiRoute::resource('/task/{task_id}/subtask', 'SubTaskController');
    ApiRoute::resource('task', 'TaskController');
    ApiRoute::resource('task-category', 'TaskCategoryController');
    ApiRoute::resource('taskboard-columns', 'TaskboardColumnController');

    ApiRoute::get('/lead/me', ['as' => 'lead.me', 'uses' => 'LeadController@me']);
    ApiRoute::resource('lead', 'LeadController');
    ApiRoute::resource('lead-category', 'LeadCategoryController');
    ApiRoute::resource('lead-source', 'LeadSourceController');
    ApiRoute::resource('lead-agent', 'LeadAgentController');
    ApiRoute::resource('lead-status', 'LeadStatusController');
    ApiRoute::resource('client', 'ClientController');
    ApiRoute::resource('client-category', 'ClientCategoryController');
    ApiRoute::resource('client-sub-category', 'ClientSubCategoryController');
    ApiRoute::resource('department', 'DepartmentController');
    ApiRoute::resource('designation', 'DesignationController');

    ApiRoute::resource('holiday', 'HolidayController');

    ApiRoute::resource('contract-type', 'ContractTypeController');
    ApiRoute::resource('contract', 'ContractController');

    ApiRoute::resource('notice', 'NoticeController');
    ApiRoute::resource('event', 'EventController');
    ApiRoute::get('/me/calendar', 'EventController@me');

    ApiRoute::get('/estimate/send/{id}', ['as' => 'estimate.send', 'uses' => 'EstimateController@sendEstimate']);
    ApiRoute::resource('estimate', 'EstimateController');

    ApiRoute::get('/invoice/send/{id}', ['as' => 'invoice.send', 'uses' => 'InvoiceController@sendInvoice']);
    ApiRoute::get(
        '/invoice/payment-reminder/{id}',
        ['as' => 'invoice.payment-reminder', 'uses' => 'InvoiceController@remindForPayment']
    );
    ApiRoute::resource('invoice', 'InvoiceController');

    ApiRoute::get('userchat/message-setting', 'UserChatController@messageSetting');
    ApiRoute::get('userchat/user-list', 'UserChatController@userList');
    ApiRoute::get('userchat/messages/{userid}', 'UserChatController@getMessages');
    ApiRoute::resource('userchat', 'UserChatController');

    ApiRoute::get('timelog/me', 'TimeLogController@me');
    ApiRoute::resource('timelog', 'TimeLogController', ['only' => ['index', 'store', 'update']]);

    ApiRoute::get('/ticket/me', ['as' => 'ticket.me', 'uses' => 'TicketController@me']);
    ApiRoute::resource('ticket', 'TicketController');
    ApiRoute::post('ticket-reply-file', 'TicketReplyController@ticketReplyFile');
    ApiRoute::resource('ticket-reply', 'TicketReplyController');
    ApiRoute::resource('ticket-group', 'TicketGroupController', ['only' => ['index']]);
    ApiRoute::resource('ticket-channel', 'TicketChannelController', ['only' => ['index']]);
    ApiRoute::resource('ticket-type', 'TicketTypeController', ['only' => ['index']]);

    ApiRoute::resource('product', 'ProductController');
    ApiRoute::get(
        '/employee/last-employee-id',
        [
            'as' => 'employee.last-employee-id',
            'uses' => 'EmployeeController@lastEmployeeID'
        ]
    );
    ApiRoute::resource('employee', 'EmployeeController');

    ApiRoute::resource('user', 'UserController', ['only' => ['index']]);

    ApiRoute::resource('expense', 'ExpenseController');

    ApiRoute::resource('leave', 'LeaveController');
    ApiRoute::get('leave-type', 'LeaveTypeController@index');

    ApiRoute::post('/device/register', ['as' => 'device.register', 'uses' => 'DeviceController@register']);
    ApiRoute::post('/device/unregister', ['as' => 'device.unregister', 'uses' => 'DeviceController@unregister']);


    ApiRoute::get('/attendance/today', ['as' => 'attendance.today', 'uses' => 'AttendanceController@today']);
    ApiRoute::post('/attendance/clock-in', ['as' => 'attendance.clockIn', 'uses' => 'AttendanceController@clockIn']);
    ApiRoute::post(
        '/attendance/clock-out/{attendance}',
        [
            'as' => 'attendance.clockOut',
            'uses' => 'AttendanceController@clockOut'
        ]
    );
    ApiRoute::resource('/attendance', 'AttendanceController');

    ApiRoute::resource('/tax', 'TaxController', ['only' => ['index']]);
});
