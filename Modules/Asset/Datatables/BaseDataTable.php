<?php

namespace Modules\Asset\Datatables;

use Yajra\DataTables\Services\DataTable;

class BaseDataTable extends DataTable
{

    public function __construct()
    {
        $this->user = user();
    }
}
