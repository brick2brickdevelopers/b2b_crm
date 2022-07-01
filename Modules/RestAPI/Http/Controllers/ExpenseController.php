<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiController;
use Modules\RestAPI\Entities\Expense;
use Modules\RestAPI\Http\Requests\Expense\IndexRequest;
use Modules\RestAPI\Http\Requests\Expense\CreateRequest;
use Modules\RestAPI\Http\Requests\Expense\ShowRequest;
use Modules\RestAPI\Http\Requests\Expense\UpdateRequest;
use Modules\RestAPI\Http\Requests\Expense\DeleteRequest;

class ExpenseController extends ApiBaseController
{

    protected $model = Expense::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function modifyIndex($query)
    {
        return $query->visibility()
            ->join(
                \DB::raw('(SELECT `id` as `a_user_id`, `name` as `employee_name` FROM `users`) as `a`'),
                'a.a_user_id',
                '=',
                'expenses.user_id'
            );
    }
}
