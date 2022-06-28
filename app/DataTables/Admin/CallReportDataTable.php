<?php

namespace App\DataTables\Admin;

use App\ManualLoggedCall;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\DataTables\BaseDataTable;

class CallReportDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $currentDate = Carbon::today()->format('Y-m-d');
        return datatables()
            ->eloquent($query->with(['purpose']))
            ->editColumn('purpose', function ($row) {
                if ($row->purpose) {
                    return $row->purpose->purpose;
                } else {
                    return "N/A";
                }
            })
            ->editColumn('call_type', function ($row) {
                if ($row->call_type == 0) {
                    return "Manual";
                } else {
                    return "AUTO";
                }
            })
            ->editColumn('call_source', function ($row) {
                if ($row->call_type == 1) {
                    return "Incomming";
                } else {
                    return "Outgoing";
                }
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d-m-Y h:m:s A');
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\App\Admin/CallReportDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ManualLoggedCall $report)
    {
        $setting = company();
        $currentDate = Carbon::now()->timezone($setting->timezone)->format('Y-m-d H:i');
        if ($this->request()->endDate !== null && $this->request()->endDate != 'null' && $this->request()->endDate != '') {

            if ($this->request()->startDate !== null && $this->request()->startDate != 'null' && $this->request()->startDate != '') {
                $startDate = Carbon::createFromFormat($this->global->date_format, $this->request()->startDate)->toDateString();
                $endDate = Carbon::createFromFormat($this->global->date_format, $this->request()->endDate)->toDateString();
                $report = $report->whereBetween('created_at', [$startDate, $endDate]);
            }
        }
        if ($this->request()->agent != 'all' && $this->request()->agent != '') {
            $report = $report->where('created_by', $this->request()->agent);
        }
        if ($this->request()->call_type != 'all' && $this->request()->call_type != '') {
            $report = $report->where('call_type', $this->request()->call_type);
        }
        if ($this->request()->call_source != 'all' && $this->request()->call_source != '') {
            $report = $report->where('call_source', $this->request()->call_source);
        }
        return $report;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('callreport-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0)
            ->buttons(
                Button::make('create'),
                Button::make('export'),
                Button::make('print'),
                // Button::make('reset'),
                Button::make('reload')
            );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [

            ['data' => 'id', 'name' => 'id', 'title' => 'Id'],
            ['data' => 'lead_number', 'name' => 'lead_number', 'title' => 'Lead Number'],
            ['data' => 'agent_number', 'name' => 'agent_number', 'title' => 'Agent Number'],
            ['data' => 'did', 'name' => 'name', 'title' => 'DID Number'],
            ['data' => 'id', 'name' => 'name', 'title' => 'Call Status'],
            ['data' => 'purpose', 'name' => 'purpose', 'title' => 'Call purpose'],
            ['data' => 'call_type', 'name' => 'call_type', 'title' => 'Call Type'],
            ['data' => 'call_source', 'name' => 'call_source', 'title' => 'Call Source'],
            ['data' => 'reason_text', 'name' => 'reason_text', 'title' => 'Result'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created'],
            ['data' => 'duration', 'name' => 'duration', 'title' => 'Call Duration'],


        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'callreport' . date('YmdHis');
    }
}
