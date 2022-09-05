<?php

namespace App\DataTables\Member;

use App\DataTables\BaseDataTable;

use Illuminate\Support\Facades\Auth;
use App\ManualLoggedCall;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;


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
            ->editColumn('call_status', function ($row) {
                if ($row->call_status == 0) {
                    return "Available";
                } elseif ($row->call_status == 1) {
                    return "Completed";
                } else {
                    return "Follow";
                }
            })
            ->editColumn('outcome', function ($row) {
                if ($row->outcome == 1) {
                    return "In Process";
                } elseif ($row->outcome == 2) {
                    return "Running";
                } elseif ($row->outcome == 3) {
                    return "Both Answered";
                } elseif ($row->outcome == 4) {
                    return "To (Customer) Answered - From (Agent) Unanswered";
                } elseif ($row->outcome == 5) {
                    return "To (Customer) Answered";
                } elseif ($row->outcome == 6) {
                    return "To (Customer) Unanswered - From (Agent) Answered";
                } elseif ($row->outcome == 7) {
                    return "From (Agent) Unanswered";
                } elseif ($row->outcome == 8) {
                    return "To (Customer) Unanswered";
                } elseif ($row->outcome == 9) {
                    return "Both Unanswered";
                } elseif ($row->outcome == 10) {
                    return "From (Agent) Answered";
                } elseif ($row->outcome == 11) {
                    return "Rejected Call";
                } elseif ($row->outcome == 12) {
                    return "Skipped";
                } elseif ($row->outcome == 13) {
                    return "From (Agent) Failed";
                } elseif ($row->outcome == 14) {
                    return "To (Customer) Failed - From (Agent) Answered";
                } elseif ($row->outcome == 15) {
                    return "To (Customer) Failed";
                } elseif ($row->outcome == 16) {
                    return "To (Customer) Answered - From (Agent) Failed";
                } else {
                    return "N/A";
                }
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d-m-Y h:m:s A');
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\App\Member/CallReportDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ManualLoggedCall $report)
    {
        $setting = company();
        $currentDate = Carbon::now()->timezone($setting->timezone)->format('Y-m-d H:i');
        $report = $report->where('created_by', '=', Auth::user()->id);

        if ($this->request()->endDate !== null && $this->request()->endDate != 'null' && $this->request()->endDate != '') {

            if ($this->request()->startDate !== null && $this->request()->startDate != 'null' && $this->request()->startDate != '') {
                $startDate = Carbon::createFromFormat($this->global->date_format, $this->request()->startDate)->toDateString();
                $endDate = Carbon::createFromFormat($this->global->date_format, $this->request()->endDate)->toDateString();
                $report = $report->whereBetween('created_at', [$startDate, $endDate]);
            }
        }
        $report = $report->where('created_by', Auth::user()->id);

        if ($this->request()->call_type != 'all' && $this->request()->call_type != '') {
            $report = $report->where('call_type', $this->request()->call_type);
        }
        if ($this->request()->call_source != 'all' && $this->request()->call_source != '') {
            $report = $report->where('call_source', $this->request()->call_source);
        }
        if ($this->request()->call_outcome != 'all' && $this->request()->call_outcome != '') {
            $report = $report->where('outcome', $this->request()->call_outcome);
        }
        if ($this->request()->campaign_status != 'all' && $this->request()->campaign_status != '') {
            $report = $report->where('call_status', $this->request()->campaign_status);
        }
        if ($this->request()->call_purpose != 'all' && $this->request()->call_purpose != '') {
            $report = $report->where('call_purpose', $this->request()->call_purpose);
        }
        if ($this->request()->campaign_id != 'all' && $this->request()->campaign_id != '') {
            $report = $report->where('campaign_id', $this->request()->campaign_id);
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
                // Button::make('create'),
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
            ['data' => 'call_status', 'name' => 'name', 'title' => 'Call Status'],
            ['data' => 'purpose', 'name' => 'purpose', 'title' => 'Call purpose'],
            ['data' => 'call_type', 'name' => 'call_type', 'title' => 'Call Type'],
            ['data' => 'call_source', 'name' => 'call_source', 'title' => 'Call Source'],
            ['data' => 'outcome', 'name' => 'outcome', 'title' => 'Result'],
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
