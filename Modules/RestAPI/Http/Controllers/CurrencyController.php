<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Currency;

class CurrencyController extends ApiBaseController
{
    protected $model = Currency::class;
}
