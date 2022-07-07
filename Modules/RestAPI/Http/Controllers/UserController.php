<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiController;
use Modules\RestAPI\Entities\User;
use Modules\RestAPI\Http\Requests\Employee\IndexRequest;

class UserController extends ApiController
{
    protected $model = User::class;

    protected $indexRequest = IndexRequest::class;
}
