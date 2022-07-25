<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\User;
use App\EmployeeDetails;
use App\CallPurpose;
use App\Campaign;
use App\CampaignLead;

use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class LeadDashboardDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'admin/leaddashboard.action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\App\Admin/LeadDashboard $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        $this->campaigns = Campaign::get();
        $this->agents = EmployeeDetails::get();
        $this->leads = CampaignLead::get();
        $this->callPurposes = CallPurpose::all();
        dd($this->data);
        // return $this->data;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('leaddashboard-table')
            ->columns($this->processTitle($this->getColumns()))
            ->minifiedAjax()
            ->dom("<'row'<'col-md-6'l><'col-md-6'Bf>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>")
            ->orderBy(0)
            ->destroy(true)
            ->responsive(true)
            ->serverSide(true)
            ->stateSave(true)
            ->processing(true)
            ->language(__('app.datatable'));
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->width(150)
                ->addClass('text-center'),
            Column::make('id'),
            Column::make('name'),
            Column::make('phone'),
            Column::make('campaign status'),
            Column::make('lead status'),
            Column::make('action'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Admin/LeadDashboard_' . date('YmdHis');
    }
}
