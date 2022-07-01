<?php

namespace Modules\Asset\Datatables;

use Modules\Asset\Entities\Asset;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class AssetsDataTable extends BaseDataTable
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
            ->addColumn('action', function ($row) {
                $action = '<div class="btn-group dropdown m-r-10">
                <button aria-expanded="false" data-toggle="dropdown" class="btn dropdown-toggle waves-effect waves-light" type="button"><i class="ti-more"></i></button>
                <ul role="menu" class="dropdown-menu pull-right">
                  <li><a href="' . route('admin.assets.edit', [$row->id]) . '"><i class="fa fa-pencil" aria-hidden="true"></i> ' . trans('app.edit') . '</a></li>';
                if ($row->status == 'available') {
                    $action .= '<li><a href="javascript:;" onclick="lend(' . $row->id . ');return false;"><i class="fa fa-mail-reply" aria-hidden="true"></i> ' . trans('asset::app.lend') . '</a></li>';
                }

                if ($row->status == 'lent') {
                    $action .= '<li><a href="javascript:;" onclick="returnAsset('.$row->history[0]->id .' ,' . $row->id . ');return false;"><i class="fa fa-mail-reply" aria-hidden="true"></i> ' . trans('asset::app.return') . '</a></li>';
                }
                $action .= '<li><a href="javascript:;"  data-user-id="' . $row->id . '"  class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a></li>';

                $action .= '</ul> </div>';

                return $action;
            })
            ->editColumn('name', function ($row) {
                return '<a href="javascript:;" data-asset-id="' . $row->id . '" class="asset-name">'.ucfirst($row->name).'</a>';
            })
            ->editColumn('status', function ($row) {
                $class = ['non-functional' => 'danger', 'lent' => 'warning', 'available' => 'success'];
                return '<label class="label label-'.$class[$row->status].'">'. ucfirst($row->status) .'</label>';

            })
            ->editColumn('history', function ($row) {
                if($row->status == 'lent') {
                    $image = '<img src="' . $row->history[0]->user->image_url . '"alt="user" class="img-circle" width="30" height="30"> ';

                    $designation = ($row->history[0]->user->designation_name) ? ucwords($row->history[0]->user->designation_name) : ' ';

                    return  '<div class="row"><div class="col-sm-3 col-xs-4">' . $image . '</div><div class="col-sm-9 col-xs-8"><a href="' . route('admin.employees.show', $row->history[0]->user->id) . '">' . ucwords($row->history[0]->user->name) . '</a><br><span class="text-muted font-12">' . $designation . '</span></div></div>';

                }
                return '-';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'status', 'history', 'name', 'image']);

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Asset $model)
    {
        $request = $this->request();

        $assets =  $model->with(['asset_type', 'history' => function($query) {
            return $query->orderBy('id', 'desc');
        }, 'history.user'])->select('assets.id', 'name', 'asset_type_id', 'description', 'serial_number', 'status');

        if ($request->asset_type != 'all' && $request->asset_type != '') {
            $assets = $assets->where('asset_type_id', $request->asset_type);
        }

        if ($request->user_id != 'all' && $request->user_id != '') {
            $assets = $assets->join('asset_lending_history', 'asset_lending_history.asset_id', '=', 'assets.id')
                ->where('assets.status', 'lent')
                ->where('asset_lending_history.user_id', $request->user_id)
                ->whereNull('asset_lending_history.date_of_return');
        }

        if ($request->status != 'all' && $request->status != '') {
            $assets = $assets->where('status', $request->status);
        }

        return $assets;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('assets-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-6'l><'col-md-6'Bf>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>")
            ->orderBy(0)
            ->destroy(true)
            ->responsive(true)
            ->serverSide(true)
            ->stateSave(true)
            ->processing(true)
            ->language(__("app.datatable"))
            ->buttons(
                Button::make(['extend'=> 'export','buttons' => ['excel', 'csv']])
            )
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["assets-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    })
                }',
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            '#' => ['data' => 'id', 'name' => 'id', 'visible' => true],
            __('asset::app.assetName') => ['data' => 'name', 'name' => 'name'],
            __('asset::app.lentTo') => ['data' => 'history', 'name' => 'history.user.name', 'width' => '20%'],
            __('asset::app.status') => ['data' => 'status', 'name' => 'status'],
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->width(150)
                ->addClass('text-center')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Assets_' . date('YmdHis');
    }

    public function pdf()
    {
        set_time_limit(0);
        if ('snappy' == config('datatables-buttons.pdf_generator', 'snappy')) {
            return $this->snappyPdf();
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('datatables::print', ['data' => $this->getDataForPrint()]);

        return $pdf->download($this->getFilename() . '.pdf');
    }
}
