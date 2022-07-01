<?php

namespace Modules\Payroll\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Payroll\Entities\SalaryGroup;
use Modules\Payroll\Entities\SalaryComponent;
use Modules\Payroll\Http\Requests\StoreSalaryComponent;
use Yajra\DataTables\Facades\DataTables;

class SalaryComponentController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('app.menu.payroll') . ' ' . __('app.menu.settings');
        $this->pageIcon = 'icon-settings';
        $this->middleware(function ($request, $next) {
            if (!in_array('payroll', $this->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('payroll::salary-components.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('payroll::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(StoreSalaryComponent $request)
    {
        SalaryComponent::create($request->all());
        return Reply::success(__('messages.recordSaved'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('payroll::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $this->salaryComponent = SalaryComponent::find($id);
        return view('payroll::salary-components.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(StoreSalaryComponent $request, $id)
    {
        SalaryComponent::where('id', $id)->update([
            'component_name' => $request->component_name,
            'component_type' => $request->component_type,
            'component_value' => $request->component_value,
            'weekly_value' => $request->weekly_value,
            'biweekly_value' => $request->biweekly_value,
            'semimonthly_value' => $request->semimonthly_value,
            'value_type' => $request->value_type,
        ]);

        return Reply::success(__('messages.updatedSuccessfully'));
    }

    public function data(Request $request)
    {

        $users = SalaryComponent::where('id', '>', 0);

        if ($request->component_type_filter != 'all' && $request->component_type_filter != '') {
            $users = $users->where('component_type', $request->component_type_filter);
        }

        if ($request->value_type_filter != 'all' && $request->value_type_filter != '') {
            $users = $users->where('value_type', $request->value_type_filter);
        }

        $users = $users->get();

        return DataTables::of($users)
            ->addColumn('action', function ($row) {
                $action = '<div class="btn-group dropdown m-r-10">
                <button aria-expanded="false" data-toggle="dropdown" class="btn dropdown-toggle waves-effect waves-light" type="button"><i class="ti-more"></i></button>
                <ul role="menu" class="dropdown-menu pull-right">
                  <li><a href="javascript:;" class="edit-type"  data-type-id="' . $row->id . '" data-original-title="'.trans('app.edit') .'"><i class="fa fa-pencil" aria-hidden="true"></i> ' . trans('app.edit') . '</a></li>
                  <li><a  href="javascript:;" class="delete-type"
                  data-toggle="tooltip" data-type-id="' . $row->id . '" data-original-title="'.trans('app.delete') .'"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a></li>';

                $action .= '</ul> </div>';

                return $action;
            })
            ->editColumn(
                'component_type',
                function ($row) {
                    if ($row->component_type == 'earning') {
                        return '<label class="label label-success">' . __('payroll::modules.payroll.earning') . '</label>';
                    } else {
                        return '<label class="label label-danger">' . __('payroll::modules.payroll.deduction') . '</label>';
                    }
                }
            )
            ->editColumn(
                'value_type',
                function ($row) {
                    return ucfirst(__('payroll::modules.payroll.' . $row->value_type));
                }
            )
            ->editColumn(
                'component_value',
                function ($row) {
                    $rec =  '<p>'.__('app.monthly') .' - '. $row->component_value .'</p>';
                    $rec .=  '<p>'.__('app.weekly') .' - '. $row->weekly_value .'</p>';
                    $rec .=  '<p>'.__('payroll::app.menu.biweekly') .' - '. $row->biweekly_value .'</p>';
                    $rec .=  '<p>'.__('payroll::app.menu.semimonthly') .' - '. $row->semimonthly_value .'</p>';
                    return $rec;
                }
            )
            ->addIndexColumn()
            ->rawColumns(['action', 'component_type', 'component_value'])
            ->make(true);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        SalaryComponent::destroy($id);
        return Reply::success(__('messages.deleteSuccess'));
    }
}
